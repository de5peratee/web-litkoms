@foreach($events as $event)
    <a href="{{ route('events.get_event', $event->id) }}" class="event-card">
        <div class="cover_wrapper">
            <img src="{{ $event->cover ? Storage::url('events/' . $event->cover) : asset('images/default_template/event-cover.svg') }}" alt="event_cover">
        </div>

        <div class="event-description">
            <div class="event-categories" data-tags="{{ $event->tags->pluck('name')->join(',') }}">
                @foreach($event->tags as $tag)
                    <span class="event-tag text-small">{{ $tag->name }}</span>
                @endforeach
            </div>

            <div class="event-title-block">
                <h3>{{ $event->name }}</h3>
                <img src="{{ asset('images/icons/blue-arrow-link.svg') }}" class="icon-24" alt="icon">
            </div>

            <p>{{ Str::limit($event->description, 100) }}</p>
            <p>{{ $event->start_date->format('d.m.Y H:i') }}</p>
        </div>
    </a>
@endforeach
