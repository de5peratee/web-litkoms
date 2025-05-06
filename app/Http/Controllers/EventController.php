<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function show(Event $event)
    {
//        dd($event->toArray());
        return view('events.event', compact('event'));
    }

    public function index()
    {
        $events = Event::with(['tags', 'guests'])->get();
//        dd($events->toArray());
        return view('events.index', compact('events'));
    }

}
