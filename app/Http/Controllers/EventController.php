<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Tag;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $events = $this->fetchEvents($request);

        if ($request->ajax()) {
            return $this->ajaxResponse($events);
        }

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

    private function fetchEvents(Request $request)
    {
        $search = $request->get('search');
        $selectedCategories = $request->get('categories', []);
        $sortOrder = $request->input('sort', 'asc') === 'desc' ? 'desc' : 'asc';
        $perPage = 6;

        $query = Event::with(['tags', 'guests'])
            ->orderByRaw('CASE 
                WHEN (end_date IS NOT NULL AND end_date < NOW()) OR 
                     (end_date IS NULL AND start_date < NOW()) THEN 1 
                ELSE 0 
            END')
            ->orderBy('start_date', $sortOrder);

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        if (!empty($selectedCategories)) {
            $query->whereHas('tags', function ($q) use ($selectedCategories) {
                $q->whereIn('name', $selectedCategories);
            });
        }

        return $query->paginate($perPage);
    }

    private function ajaxResponse($events)
    {
        return response()->json([
            'html' => view('partials.event_cards', ['events' => $events])->render(),
            'has_more' => $events->hasMorePages(),
        ]);
    }
}
