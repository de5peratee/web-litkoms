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

    public function index(Request $request)
    {
        $perPage = 8; // Количество элементов на страницу

        // Получаем данные, как в первом варианте
        $comicsQuery = AuthorComics::with(['createdBy', 'genres'])
            ->where('is_published', true)
            ->where('is_moderated', 'successful');

        if (auth()->check()) {
            $subscribedAuthorIds = auth()->user()->subscriptions()->pluck('subscribed_to_id');
            if ($subscribedAuthorIds->isNotEmpty()) {
                $comicsQuery->whereIn('created_by', $subscribedAuthorIds);
            } else {
                $comicsQuery->whereRaw('1 = 0'); // Пустой результат, если нет подписок
            }
        }

        $comics = $comicsQuery->get();
        $events = Event::with(['tags'])->get();
        $posts = MultimediaPost::with(['createdBy', 'medias'])->get();

        // Собираем все элементы в коллекцию
        $allItems = collect();

        // Комиксы
        foreach ($comics as $comic) {
            $allItems->push([
                'type' => 'comic',
                'item' => $comic,
                'sort_date' => $comic->published_at ?? $comic->created_at
            ]);
        }

        // Мероприятия (по created_at)
        foreach ($events as $event) {
            $allItems->push([
                'type' => 'event',
                'item' => $event,
                'sort_date' => $event->created_at
            ]);
        }

        // Посты
        foreach ($posts as $post) {
            $allItems->push([
                'type' => 'post',
                'item' => $post,
                'sort_date' => $post->created_at
            ]);
        }

        // Сортировка всех элементов по sort_date (от новых к старым)
        $allItems = $allItems->sortByDesc('sort_date');

        // Пагинация коллекции
        $currentPage = $request->input('page', 1);
        $paginatedItems = $allItems->slice(($currentPage - 1) * $perPage, $perPage)->values();
        $totalItems = $allItems->count();
        $hasMorePages = $totalItems > $currentPage * $perPage;

        // Для AJAX-запроса возвращаем только partials
        if ($request->ajax()) {
            return response()->view('partials.news-cards', [
                'items' => $paginatedItems
            ])->header('Cache-Control', 'no-store, no-cache, must-revalidate');
        }

        return view('news', [
            'allItems' => $paginatedItems,
            'comics' => $comics,
            'events' => $events,
            'posts' => $posts,
            'hasMorePages' => $hasMorePages,
            'currentPage' => $currentPage,
            'perPage' => $perPage,
            'totalItems' => $totalItems
        ]);
    }
}
