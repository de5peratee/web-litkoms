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
        $sortOrder = $request->get('sort', 'desc');
        $perPage = 6;

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

        $categories = Tag::orderBy('name')->get();

        return view('events.index', compact('events', 'categories'));
    }

    public function get_event(Event $event)
    {
        return view('events.event', compact('event'));
    }
}
