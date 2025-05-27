<?php

namespace App\Http\Controllers\AuthorComics;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthorComicRequest;
use App\Http\Requests\AuthorComicUpdateRequest;
use App\Models\AuthorComics;
use App\Models\Genre;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AuthorComicsController extends Controller
{
    /**
     * Store a new author comic.
     */
    public function index()
    {
        $comics = AuthorComics::where('created_by', auth()->id())
            ->with('genres')
            ->orderBy('published_at', 'desc')
            ->get()
            ->map(function ($comic) {
                $comic->genres_string = $comic->genres->pluck('name')->implode(', ') ?: 'Не указаны';
                $comic->status = match (true) {
                    $comic->is_published => 'Опубликован',
                    $comic->is_moderated === 'under review' => 'На модерации',
                    $comic->is_moderated === 'unsuccessful' => 'Не принят',
                    $comic->is_moderated === 'successful' => 'Принят',
                    default => 'Черновик',
                };
                return $comic;
            });

        return view('user.author_comics.list', compact('comics'));
    }

    public function showModerationConfirm(AuthorComics $comic)
    {
        if ($comic->created_by !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }
        if ($comic->is_moderated === 'successful' && $comic->is_published) {
            return redirect()->route('user.author_comics')->with('error', 'Комикс уже опубликован.');
        }

        return view('user.author_comics.moderation-confirm-comics', compact('comic'));
    }

    public function create()
    {
        return view('user.author_comics.create');
    }

    public function store(AuthorComicRequest $request)
    {
        $validated = $request->validated();
        try {
            DB::beginTransaction();

            $coverPath = $request->file('cover')->store('author_comics_covers', 'public');
            $comicFilePath = $request->file('comic_file')->store('comics_files', 'public');

            $comic = AuthorComics::create([
                'created_by' => auth()->id(),
                'name' => $validated['title'],
                'slug' => Str::slug($validated['title']) . '-' . uniqid(),
                'description' => $validated['description'],
                'cover' => $coverPath,
                'comics_file' => $comicFilePath,
                'age_restriction' => $validated['age_restriction'],
                'is_moderated' => 'under review',
                'is_published' => false,
            ]);

            $this->syncGenres($comic, $validated['genres'] ?? '');
            DB::commit();

            return redirect()->route('user.moderation-confirm-comics', ['comic' => $comic])
                ->with('success', 'Комикс успешно отправлен на модерацию!');

        } catch (\Throwable $e) {
            DB::rollBack();

            if (isset($coverPath)) {
                Storage::disk('public')->delete($coverPath);
            }

            if (isset($comicFilePath)) {
                Storage::disk('public')->delete($comicFilePath);
            }

            return redirect()->back()
                ->withErrors(['error' => 'Произошла ошибка при сохранении комикса: ' . $e->getMessage()]);
        }
    }

    public function update(AuthorComicUpdateRequest $request, AuthorComics $comic)
    {

        Log::info('Request Data', ['request' => $request->all()]);

        if ($comic->created_by !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }
        $validated = $request->validated();

        try {
            DB::beginTransaction();

            $data = [
                'name' => $validated['title'],
                'description' => $validated['description'],
                'age_restriction' => $validated['age_restriction'],
                'is_moderated' => 'under review',
                'feedback' => '',
            ];

            if ($request->hasFile('cover')) {
                if ($comic->cover) {
                    Storage::disk('public')->delete($comic->cover);
                }
                $data['cover'] = $request->file('cover')->store('author_comics_covers', 'public');
            }

            if ($request->hasFile('comic_file')) {
                if ($comic->comics_file) {
                    Storage::disk('public')->delete($comic->comics_file);
                }
                $data['comics_file'] = $request->file('comic_file')->store('comics', 'public');
            }

            $comic->update($data);
            $this->syncGenres($comic, $validated['genres'] ?? '');

            DB::commit();

            return redirect()->route('user.author_comics')
                ->with('success', 'Комикс успешно обновлен!');
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()
                ->withErrors(['error' => 'Произошла ошибка при обновлении комикса: ' . $e->getMessage()]);
        }
    }

    public function destroy(AuthorComics $comic)
    {
        if ($comic->created_by !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        try {
            DB::beginTransaction();
            $comic->comments()->delete();
            $comic->ratings()->delete();

            if ($comic->cover) {
                Storage::disk('public')->delete($comic->cover);
            }
            if ($comic->comics_file) {
                Storage::disk('public')->delete($comic->comics_file);
            }
            $comic->genres()->detach();

            $comic->delete();

            DB::commit();

            return redirect()->route('user.author_comics')
                ->with('success', 'Комикс и связанные с ним данные успешно удалены!');
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Error deleting comic and related data: ' . $e->getMessage());

            return redirect()->back()
                ->withErrors(['error' => 'Произошла ошибка при удалении комикса: ' . $e->getMessage()]);
        }
    }

    public function publish(AuthorComics $comic)
    {
        if ($comic->created_by !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        if ($comic->is_moderated !== 'successful') {
            return redirect()->back()->withErrors(['error' => 'Комикс не прошел модерацию и не может быть опубликован.']);
        }

        try {
            DB::beginTransaction();
            $comic->update(['is_published' => true]);
            $comic->update(['published_at' => now()]);
            DB::commit();
            return redirect()->route('user.moderation-confirm-comics', $comic->slug)
                ->with('success', 'Комикс успешно опубликован!');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Error publishing comic: ' . $e->getMessage());
            return redirect()->back()
                ->withErrors(['error' => 'Произошла ошибка при публикации комикса: ' . $e->getMessage()]);
        }
    }

    private function syncGenres(AuthorComics $comic, string $genresString): void
    {
        $genres = array_filter(array_map('trim', explode(',', $genresString)));

        $ids = Genre::whereIn('name', $genres)->pluck('id')->toArray();

        foreach ($genres as $name) {
            if (!Genre::where('name', $name)->exists()) {
                $genre = Genre::create(['name' => $name]);
                $ids[] = $genre->id;
            }
        }
        $comic->genres()->sync($ids);
//        dd($comic->toArray(), $ids);

        Genre::whereDoesntHave('comics')
            ->whereDoesntHave('catalogs')
            ->delete();
    }
}

