<?php

namespace App\Http\Controllers\Editor;

use App\Http\Controllers\Controller;
use App\Http\Requests\AcceptAuthorComicsRequest;
use App\Models\AuthorComics;
use Illuminate\Http\Request;


class EditorModerationController extends Controller
{
    public function index()
    {
        $comics = AuthorComics::with('createdBy')
            ->where('is_moderated', 'under review')
            ->get();

        return view('editor.comics.submissions', [
            'comics' => $comics,
            'comics_count' => $comics->count()
        ]);
    }

    public function show($slug)
    {
        $comic = AuthorComics::with('createdBy')
            ->where('slug', $slug)
            ->firstOrFail();

        if ($comic->is_moderated !== 'under review') {
            return back();
        }

        return view('editor.comics.moderation', compact('comic'));
    }

    public function update(AcceptAuthorComicsRequest $request, $slug)
    {
        $comic = AuthorComics::where('slug', $slug)->firstOrFail();

        $comic->update([
            'age_restriction' => $request->age_restriction,
            'is_moderated' => $request->moderation_status,
            'feedback' => $request->moderation_status === 'unsuccessful' ? $request->feedback : null,
        ]);

        $message = $request->moderation_status === 'successful'
            ? 'Комикс успешно принят.'
            : 'Комикс отклонен, фидбек сохранен.';

        return redirect()->route('editor.comics_submissions_index')->with('success', $message);
    }

}
