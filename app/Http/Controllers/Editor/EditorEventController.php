<?php

namespace App\Http\Controllers\Editor;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Models\Event;
use App\Models\Guest;
use App\Models\Tag;
use App\Services\ImageCompressionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class EditorEventController extends Controller
{
    protected $imageCompressionService;

    public function __construct(ImageCompressionService $imageCompressionService)
    {
        $this->imageCompressionService = $imageCompressionService;
    }

//    public function index()
//    {
//        $events = Event::with(['tags', 'guests'])
//            ->orderBy('start_date', 'desc')
//            ->get();
//        return view('editor.events.list', compact('events'));
//    }
    public function index(Request $request)
    {
        $perPage = 10;
        $search = $request->input('search', '');

        $events = Event::with(['tags', 'guests'])
            ->when($search, function ($query) use ($search) {
                return $query->where('name', 'like', '%' . $search . '%');
            })
            ->orderBy('start_date', 'desc')
            ->take($perPage)
            ->get();

        $total = Event::when($search, function ($query) use ($search) {
            return $query->where('name', 'like', '%' . $search . '%');
        })->count();

        return view('editor.events.list', compact('events', 'total', 'search'));
    }

    public function loadMore(Request $request)
    {
        $page = $request->input('page', 2);
        $perPage = 10;
        $search = $request->input('search', '');

        $events = Event::with(['tags', 'guests'])
            ->when($search, function ($query) use ($search) {
                return $query->where('name', 'like', '%' . $search . '%');
            })
            ->orderBy('start_date', 'desc')
            ->skip(($page - 1) * $perPage)
            ->take($perPage)
            ->get();

        $html = view('partials.editor_lists.events_items', [
            'events' => $events,
            'page' => $page - 1 // Для корректной нумерации
        ])->render();

        return response()->json([
            'html' => $html,
            'hasMore' => $events->count() === $perPage,
            'nextPage' => $page + 1,
        ]);
    }

    public function create()
    {
        return view('editor.events.create');
    }

    public function store(StoreEventRequest $request)
    {
//        dd($request->start_date . ' ' . $request->start_time);
        try {
            DB::beginTransaction();

            $coverPath = $request->file('cover')->store('event_covers', 'public');
            $this->imageCompressionService->compressImage(storage_path("app/public/$coverPath"));

            $event = Event::create([
                'created_by' => auth()->id(),
                'name' => $request->name,
                'description' => $request->description,
                'start_date' => $request->start_date . ' ' . $request->start_time ,
                'end_date' => $request->end_date . ' ' . $request->end_time,
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
                $this->imageCompressionService->compressImage(storage_path("app/public/$event->cover"));
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
