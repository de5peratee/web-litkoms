<?php

namespace App\Http\Controllers\Editor;

use App\Http\Controllers\Controller;
use App\Models\AuthorComics;
use App\Models\Event;
use App\Models\Catalog;
use App\Models\MultimediaPost;
use App\Models\User;
use App\MoonShine\Resources\AuthorComicsResource;

class EditorDashboardController extends Controller
{
    public function index()
    {
        $eventsCount = Event::count();
        $mediaPostsCount = MultimediaPost::count();
        $catalogsCount = Catalog::count();
        $submissionsCount = AuthorComics::where('is_moderated', 'under review')->count();

        $editors = User::where('role', 'editor')->get();

        return view('editor.dashboard', compact(
            'eventsCount',
            'mediaPostsCount',
            'catalogsCount',
            'submissionsCount',
            'editors'
        ));
    }
}