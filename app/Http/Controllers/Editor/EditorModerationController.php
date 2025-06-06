<?php

namespace App\Http\Controllers\Editor;

use App\Http\Controllers\Controller;
use App\Http\Requests\AcceptAuthorComicsRequest;
use App\Models\AuthorComics;
use Illuminate\Http\Request;

class EditorModerationController extends Controller
{
    public function index(Request $request)
    {
        $query = AuthorComics::with('createdBy')->orderBy('created_at', 'DESC');;

        if ($search = $request->input('search')) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        $status = $request->input('status', 'under review');
        if (in_array($status, ['under review', 'successful', 'unsuccessful'])) {
            $query->where('is_moderated', $status);
        }

        $perPage = 10;
        $comics = $query->paginate($perPage);

        if ($request->ajax()) {
            return response()->view('partials.editor_lists.submissions_items', [
                'comics' => $comics,
                'comics_count' => $comics->total(),
                'status' => $status
            ])->header('Cache-Control', 'no-store, no-cache, must-revalidate, private');
        }

        return view('editor.comics.submissions', [
            'comics' => $comics,
            'comics_count' => $comics->total(),
            'status' => $status
        ]);
    }

    public function show($slug)
    {
        $comic = AuthorComics::with('createdBy')
            ->where('slug', $slug)
            ->firstOrFail();

        if ($comic->is_moderated !== 'under review') {
            return back()->with('error', 'Комикс не находится на модерации.');
        }

        return view('editor.comics.moderation', compact('comic'));
    }

    public function update(AcceptAuthorComicsRequest $request, $slug)
    {
        $comic = AuthorComics::where('slug', $slug)->firstOrFail();

        $validAgeRestrictions = [6, 12, 16, 18];

        $comic->update([
            'age_restriction' => in_array($request->age_restriction, $validAgeRestrictions)
                ? $request->age_restriction
                : $comic->age_restriction,
            'is_moderated' => $request->moderation_status,
            'feedback' => $request->moderation_status === 'unsuccessful'
                ? $request->feedback
                : null,
        ]);

        $message = $request->moderation_status === 'successful'
            ? 'Комикс успешно принят.'
            : 'Комикс отклонен, фидбек сохранен.';

        if ($request->ajax()) {
            return response()->json([
                'message' => $message,
                'status' => 'success',
            ]);
        }

        return redirect()
            ->route('editor.comics_submissions_index')
            ->with('success', $message);
    }


}
