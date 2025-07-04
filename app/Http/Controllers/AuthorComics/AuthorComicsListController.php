<?php

namespace App\Http\Controllers\AuthorComics;

use App\Http\Controllers\Controller;
use App\Models\AuthorComics;
use App\Models\Genre;
use App\Services\ViewCounterService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AuthorComicsListController extends Controller
{
    protected $viewCounterService;

    public function __construct(ViewCounterService $viewCounterService)
    {
        $this->viewCounterService = $viewCounterService;
    }

//    public function library(Request $request)
//    {
//        $search = $request->query('search');
//
//        $genre = $request->query('genre');
//
//        $comics = AuthorComics::where('is_published', true)
//            ->where('is_moderated', 'successful')
//            ->when($search, function ($query, $search) {
//                return $query->where('name', 'like', '%' . $search . '%');
//            })
//            ->when($genre, function ($query, $genre) {
//                return $query->whereHas('genres', function ($q) use ($genre) {
//                    $q->where('slug', $genre);
//                });
//            })
//            ->with(['genres', 'createdBy'])
//            ->paginate(12);
//
//        $genres = Genre::all();
//
//        return view('authors_comics.library', compact('comics', 'search', 'genres'));
//    }
    public function library(Request $request)
    {
        if (!$request->ajax()) {
            session(['catalog_url' => $request->fullUrl()]);
        }

        $query = AuthorComics::with(['genres', 'createdBy'])
            ->where('is_published', true)
            ->where('is_moderated', 'successful');

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where('name', 'like', "%{$search}%");
        }

        $genres = $request->input('genres', []);

        if (!empty($genres)) {
            if (!is_array($genres)) {
                $genres = [$genres];
            }
            foreach ($genres as $genre) {
                $query->whereHas('genres', function ($q) use ($genre) {
                    $q->where('genres.name', $genre);
                });
            }
        }

        $sort = $request->input('sort', 'date-desc');
        if ($sort === 'date-asc') {
            $query->orderBy('published_at', 'asc');
        } elseif ($sort === 'rating-desc') {
            $query->orderBy('average_assessment', 'desc');
        } elseif ($sort === 'rating-asc') {
            $query->orderBy('average_assessment', 'asc');
        } else {
            $query->orderBy('published_at', 'desc');
        }

        $comics = $query->paginate(12);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('partials.authors_comics', ['comics' => $comics])->render(),
                'has_more' => $comics->hasMorePages()
            ]);
        }

        return view('authors_comics.library', [
            'comics' => $comics,
            'genres' => Genre::all()
        ]);
    }

    public function show(AuthorComics $authorComic)
    {
        if (!$authorComic->is_published || $authorComic->is_moderated !== 'successful') {
            abort(404, 'Комикс не найден или не опубликован.');
        }

        $viewLimitInterval = 5;

        $shouldIncrement = $this->viewCounterService->handleView($authorComic, $viewLimitInterval);

        $averageRating = $authorComic->ratings()->avg('grade') ?? 0.0;
        $userRating = Auth::check() ? $authorComic->ratings()->where('graded_by', Auth::id())->first()?->grade : 0;
        $comments = $authorComic->comments()->with('createdBy')->latest()->paginate(10);

        $isSub = Auth::check() && Auth::user()->subscriptions()
                ->where('subscribed_to_id', $authorComic->createdBy->id)
                ->exists();

        return view('authors_comics.comic', compact('authorComic', 'averageRating', 'userRating', 'comments', 'isSub'));
    }

    public function rate(Request $request, AuthorComics $authorComic)
    {
        if (!$authorComic->is_published || $authorComic->is_moderated !== 'successful') {
            abort(404, 'Комикс не найден или не опубликован.');
        }

        $request->validate([
            'rating' => 'required|integer|min:0|max:5',
        ]);

        $user = Auth::user();
        $rating = $request->rating;

        if ($rating === 0) {
            $authorComic->ratings()->where('graded_by', $user->id)->delete();
        } else {
            $authorComic->ratings()->updateOrCreate(
                ['graded_by' => $user->id],
                ['grade' => $rating]
            );
        }

        $average = $authorComic->ratings()->avg('grade') ?? 0;
        $authorComic->update(['average_assessment' => round($average, 1)]);

        return response()->json([
            'success' => true,
            'average_rating' => number_format($average, 1),
            'user_rating' => $rating
        ]);
    }

    public function comment(Request $request, string $slug)
    {
        $comic = AuthorComics::where('slug', $slug)->firstOrFail();

        $request->validate([
            'comment' => 'required|string|max:500',
        ]);

        $comment = $comic->comments()->create([
            'comment' => $request->comment,
            'created_by' => auth()->id(),
        ]);

        $comment->load('createdBy');

        return response()->json([
            'message' => 'Комментарий добавлен.',
            'commentHtml' => view('partials._comment', ['comment' => $comment])->render(),
        ]);
    }

    public function getComments(Request $request, AuthorComics $authorComic)
    {
        if (!$authorComic->is_published || $authorComic->is_moderated !== 'successful') {
            abort(404, 'Комикс не найден или не опубликован.');
        }

        $perPage = 10;
        $comments = $authorComic->comments()
            ->with('createdBy')
            ->latest()
            ->paginate($perPage);

        $commentsHtml = '';
        foreach ($comments as $comment) {
            $commentsHtml .= view('partials._comment', ['comment' => $comment])->render();
        }

        return response()->json([
            'commentsHtml' => $commentsHtml,
            'nextPageUrl' => $comments->nextPageUrl(),
            'totalComments' => $comments->total(),
        ]);
    }

    public function download(AuthorComics $authorComic)
    {
        if (
            !$authorComic->is_published ||
            $authorComic->is_moderated !== 'successful' ||
            !$authorComic->comics_file ||
            !Storage::disk('public')->exists($authorComic->comics_file)
        ) {
            abort(404, 'Файл комикса не найден или не опубликован.');
        }

        $filePath = $authorComic->comics_file;
        $downloadName = $authorComic->slug . '.pdf';
        return Storage::disk('public')->download($filePath, $downloadName);
    }


}
