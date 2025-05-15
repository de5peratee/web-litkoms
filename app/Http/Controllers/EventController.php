<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Tag;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $selectedCategories = $request->get('categories', []);
        $sortOrder = $request->get('sort', 'asc');
        $perPage = 6;

        // Основной список
        $query = Event::with(['tags', 'guests'])->orderBy('start_date', $sortOrder);

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        if (!empty($selectedCategories)) {
            $query->whereHas('tags', function ($q) use ($selectedCategories) {
                $q->whereIn('name', $selectedCategories);
            });
        }

        $events = $query->paginate($perPage);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('partials.event_cards', ['events' => $events])->render(),
                'has_more' => $events->hasMorePages(),
            ]);
        }

        // Ближайшие 3 мероприятия для слайдера
        $upcomingEvents = Event::with('tags')
            ->where('start_date', '>=', now())
            ->orderBy('start_date', 'asc')
            ->limit(3)
            ->get();

        $categories = Tag::orderBy('name')->get();

        return view('events.index', compact('events', 'categories', 'upcomingEvents'));
    }


    public function get_event(Event $event)
    {
        return view('events.event', compact('event'));
    }
}
