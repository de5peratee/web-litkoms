<?php

namespace App\Http\Controllers;

use App\Models\AuthorComics;
use App\Models\Event;
use App\Models\MultimediaPost;
use Illuminate\Http\Request;

class NewsFeedController extends Controller
{
    public function index()
    {
        $comics = AuthorComics::with(['createdBy', 'genres'])->where('is_published', true)->where('is_moderated', 'successful')->get();
        $events = Event::with(['tags'])->get();
        $posts = MultimediaPost::with(['createdBy', 'medias'])->get();
        return view('news', compact('comics', 'events', 'posts'));
    }
}
