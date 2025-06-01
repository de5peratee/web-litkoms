<?php

namespace App\Http\Controllers;

use App\Models\AuthorComics;
use App\Models\Event;
use App\Models\MultimediaPost;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class NewsFeedController extends Controller
{
//    public function index()
//    {
//        $comics = AuthorComics::with(['createdBy', 'genres'])->where('is_published', true)->where('is_moderated', 'successful')->get();
//        $events = Event::with(['tags'])->get();
//        $posts = MultimediaPost::with(['createdBy', 'medias'])->get();
//        return view('news', compact('comics', 'events', 'posts'));
//    }

//    public function index()
//    {
//        $comics = AuthorComics::with(['createdBy', 'genres'])
//            ->where('is_published', true)
//            ->where('is_moderated', 'successful')
//            ->get();
//        $events = Event::with(['tags'])->get();
//        $posts = MultimediaPost::with(['createdBy', 'medias'])->get();
//
//        // Собираем и сортируем элементы
//        $allItems = new Collection();
//        foreach ($comics as $comic) {
//            if (auth()->check() && auth()->user()->isSubscribedTo($comic->created_by)) {
//                $allItems->push(['type' => 'comic', 'item' => $comic, 'created_at' => $comic->published_at]);
//            }
//        }
//        foreach ($events as $event) {
//            $allItems->push(['type' => 'event', 'item' => $event, 'created_at' => $event->created_at]);
//        }
//        foreach ($posts as $post) {
//            $allItems->push(['type' => 'post', 'item' => $post, 'created_at' => $post->created_at]);
//        }
//        $allItems = $allItems->sortByDesc('created_at');
//
//        return view('news', compact('comics', 'events', 'posts', 'allItems'));
//    }

    public function index()
    {
        $comics = AuthorComics::with(['createdBy', 'genres'])
            ->where('is_published', true)
            ->where('is_moderated', 'successful')
            ->get();

        $events = Event::with(['tags'])->get();
        $posts = MultimediaPost::with(['createdBy', 'medias'])->get();

        // Собираем и сортируем элементы
        $allItems = collect();

        // Комиксы с фильтрацией подписок
        foreach ($comics as $comic) {
            if (!auth()->check() || auth()->user()->isSubscribedTo($comic->created_by)) {
                $allItems->push(['type' => 'comic', 'item' => $comic, 'sort_date' => $comic->published_at]);
            }
        }

        // Разделяем события на будущие и прошедшие
        $futureEvents = $events->filter(function ($event) {
            return $event->start_date && $event->start_date->isFuture();
        })->sortBy('start_date'); // Сортировка будущих по возрастанию (от ближайшего к дальнему)
        $pastEvents = $events->filter(function ($event) {
            return $event->start_date && $event->start_date->isPast();
        })->sortByDesc('start_date'); // Сортировка прошедших по убыванию (от недавнего к старому)

        // Добавляем будущие события (сначала ближайшие)
        foreach ($futureEvents as $event) {
            $allItems->push(['type' => 'event', 'item' => $event, 'sort_date' => $event->start_date]);
        }

        // Добавляем прошедшие события (затем старые)
        foreach ($pastEvents as $event) {
            $allItems->push(['type' => 'event', 'item' => $event, 'sort_date' => $event->start_date]);
        }

        // Посты
        foreach ($posts as $post) {
            $allItems->push(['type' => 'post', 'item' => $post, 'sort_date' => $post->created_at]);
        }

        // Убираем общую сортировку, так как порядок уже задан
        // $allItems = $allItems->sortByDesc('sort_date'); // Убрано

        return view('news', compact('comics', 'events', 'posts', 'allItems'));
    }
}
