<?php

namespace App\Http\Controllers\Editor;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Models\Event;
use App\Models\Guest;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class EditorEventController extends Controller
{
    public function index()
    {
        $events = Event::with(['tags', 'guests'])
            ->orderBy('start_date', 'desc')
            ->get();
        return view('editor.events.list', compact('events'));
    }

    public function create()
    {
        return view('editor.events.create');
    }

    public function store(StoreEventRequest $request)
    {
        try {
            DB::beginTransaction();

            $coverPath = $request->file('cover')->store('event_covers', 'public');

            $event = Event::create([
                'created_by' => auth()->id(),
                'name' => $request->name,
                'description' => $request->description,
                'start_date' => $request->start_date . ' ' . $request->time,
                'cover' => $coverPath,
            ]);

            $this->syncGuests($event, $request->guests ?? '');
            $this->syncTags($event, $request->tags);

            DB::commit();

            return redirect()->route('editor.events_index')->with('success', trans('messages.event_created'));
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['cover' => 'Ошибка при создании мероприятия: ' . $e->getMessage()]);
        }
    }

    public function update(UpdateEventRequest $request, Event $event)
    {
        try {
            DB::beginTransaction();

            if ($request->hasFile('cover')) {
                if ($event->cover) {
                    Storage::disk('public')->delete($event->cover);
                }
                $event->cover = $request->file('cover')->store('event_covers', 'public');
            }

            $event->update([
                'name' => $request->name,
                'description' => $request->description,
                'start_date' => $request->start_date . ' ' . $request->time,
                'cover' => $event->cover,
            ]);

            $this->syncGuests($event, $request->guests ?? '');
            $this->syncTags($event, $request->tags);

            DB::commit();

            return redirect()->route('editor.events_index')->with('success', trans('messages.event_updated'));
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['cover' => 'Ошибка при обновлении мероприятия: ' . $e->getMessage()]);
        }
    }

    public function destroy(Event $event)
    {
        try {
            DB::beginTransaction();

            if ($event->cover) {
                Storage::disk('public')->delete($event->cover);
            }

            $event->guests()->detach();
            $event->tags()->detach();
            $event->delete();

            Guest::doesntHave('events')->delete();
            Tag::doesntHave('events')->delete();

            DB::commit();

            return redirect()->route('editor.events_index')->with('success', trans('messages.event_deleted'));
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Ошибка при удалении мероприятия: ' . $e->getMessage()]);
        }
    }

    private function syncGuests(Event $event, string $guestsString)
    {
        $names = array_filter(array_map('trim', explode(',', $guestsString)));
        $ids = Guest::whereIn('name', $names)->pluck('id')->toArray();

        foreach ($names as $name) {
            if (!Guest::where('name', $name)->exists()) {
                $guest = Guest::create(['name' => $name]);
                $ids[] = $guest->id;
            }
        }

        $event->guests()->sync($ids);

        Guest::doesntHave('events')->delete();
    }

    private function syncTags(Event $event, string $tagsString)
    {
        $tags = array_filter(array_map('trim', explode(',', $tagsString)));
        $ids = Tag::whereIn('name', $tags)->pluck('id')->toArray();

        foreach ($tags as $name) {
            if (!Tag::where('name', $name)->exists()) {
                $tag = Tag::create(['name' => $name]);
                $ids[] = $tag->id;
            }
        }

        $event->tags()->sync($ids);

        Tag::doesntHave('events')->delete();
    }
}