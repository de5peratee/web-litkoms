@foreach($events as $event)
    <a href="{{ route('events.get_event', $event->id) }}" class="event-card">
        @if(\Carbon\Carbon::parse($event->start_date)->isPast() && \Carbon\Carbon::parse($event->end_date)->isPast())
            <div class="past-sign">
                <img src="{{ asset('images/icons/lock-secondary.svg') }}" class="icon-20" alt="icon">
                <p class="text-hint">Уже прошло</p>
            </div>
        @endif


        <div class="cover_wrapper">
            @isset($event->cover)
                <img src="{{ Storage::url('' . $event->cover) }}" alt="event_cover">
            @else
                <img src="{{ asset('images/default_template/event-cover.svg') }}" alt="event_cover">
            @endisset
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

            <div class="bottom-event-flex-data">
                <div class="event-datetime-wrapper">
                    <img src="{{ asset('images/icons/calendar-tertiary.svg') }}" class="icon-20" alt="icon">
                    <p class="text-small slide-event-card-date">{{ $event->start_date->translatedFormat('j F Y', 'ru') }}</p>
                    <p class="text-small">·</p>
                    <p class="text-small slide-event-card-date">{{ $event->start_date->translatedFormat('H:i', 'ru') }}</p>
                </div>
                <div class="event-location-wrapper">
                    <img src="{{ asset('images/icons/location-tertiary.svg') }}" class="icon-20" alt="icon">
                    <p class="text-small">{{ $event->location ?? 'ул. Маршала Бирюзова, 9' }}</p>
                </div>
            </div>

        </div>
    </a>
@endforeach
