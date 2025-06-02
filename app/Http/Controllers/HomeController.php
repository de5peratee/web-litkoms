<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\MultimediaPost;
use App\Models\Catalog;
use App\Services\NewsFormatterService as News;

class HomeController extends Controller
{
    public function index()
    {

        $eventNews = Event::where('start_date', '>=', now())
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(fn($e) => News::formatEvent($e));

        $mediaNews = MultimediaPost::orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(fn($m) => News::formatMultimedia($m));

        $eventNews = collect($eventNews ?? []);
        $mediaNews = collect($mediaNews ?? []);
        $news = $eventNews->merge($mediaNews)
            ->sortByDesc('date')
            ->take(3)
            ->values();

        $events = Event::where('start_date', '>=', now())
            ->orderBy('start_date', 'asc')
            ->take(4)
            ->get();

        $comics = Catalog::orderBy('created_at', 'desc')
            ->take(10)
            ->get();

//        dd($comics->toArray(), $events->toArray(), $news->toArray());
        return view('home', [
            'news' => $news,
            'events' => $events,
            'comics' => $comics,
        ]);
    }

}
