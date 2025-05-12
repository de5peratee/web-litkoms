<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{

    public function index(Request $request)
    {
        $search = $request->get('search');
        $perPage = 4;

        $query = Event::with(['tags', 'guests'])->orderBy('start_date');

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        $events = $query->paginate($perPage);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('partials.event_cards', ['events' => $events])->render(),
                'has_more' => $events->hasMorePages(),
            ]);
        }

        return view('events.index', compact('events'));
    }


    public function get_event(Event $event)
    {
//        dd($event->toArray());
        return view('events.event', compact('event'));
    }

}
