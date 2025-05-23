<?php

namespace App\Http\Controllers\AuthorComics;

use App\Http\Controllers\Controller;
use App\Models\AuthorComics;
use App\Models\User;

class AuthorComicsLandingController extends Controller
{
    public function index()
    {
        $newComics = AuthorComics::with(['genres', 'createdBy'])
            ->where('is_moderated', 'successful')
            ->where('is_published', true)
            ->latest()
            ->take(5)
            ->get();

        $topAuthors = User::whereHas('authorComics')
            ->withCount('authorComics')
            ->orderByDesc('created_at')
            ->take(10)
            ->get();

        $subscribedComics = auth()->check()
            ? AuthorComics::whereIn('created_by', auth()->user()->subscriptions()->pluck('subscribed_to_id'))
                ->where('is_moderated', 'successful')
                ->where('is_published', true)
                ->with(['genres', 'createdBy'])
                ->latest()
                ->take(5)
                ->get()
            : collect();

        return view('authors_comics.landing', compact('newComics', 'topAuthors', 'subscribedComics'));
    }
}