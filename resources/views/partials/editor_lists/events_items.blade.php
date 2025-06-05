@foreach ($events as $index => $event)
    <div class="event-item">
        <div class="event-item-left">
            <div class="item-cell num-cell">{{ $loop->iteration + ($page ?? 0) * 10 }}</div>
            <div class="item-cell event-preview-cell">
                <div class="event-cover-wrapper">
                    <img src="{{ $event->cover ? Storage::url($event->cover) : asset('images/default_template/event-cover.svg') }}" class="event-cover" alt="icon">
                </div>
                <div class="event-preview-text-wrapper">
                    <p class="text-big">{{ $event->name }}</p>
                    <p class="text-hint event-text">{{ implode(' · ', $event->guests->map(function($guest) {
                            return $guest->name . ' ' . $guest->surname;
                        })->toArray()) }}</p>
                    <div class="event-datetime-wrapper">
                        <img src="{{ asset('images/icons/calendar-tertiary.svg') }}" class="icon-20" alt="icon">
                        <p class="slide-event-card-date">{{ $event->start_date->translatedFormat('j F Y', 'ru') }}</p>
                        <p>·</p>
                        <p class="slide-event-card-date">{{ $event->start_date->translatedFormat('H:i', 'ru') }}</p>
                    </div>
                </div>
            </div>
            <div class="item-cell">
                <a href="{{ route('events.get_event', $event->id) }}" target="_blank" class="tertiary-btn">
                    Подробнее
                    <img src="{{ asset('images/icons/blue-arrow-link.svg') }}" class="icon-24" alt="icon">
                </a>
            </div>
        </div>
        <div class="event-actions">
            <a href="#" class="list-action-btn edit-event-btn"
               data-event-id="{{ $event->id }}"
               data-event-name="{{ $event->name }}"
               data-event-description="{{ $event->description }}"
               data-event-start_date="{{ $event->start_date ? $event->start_date->format('Y-m-d') : '' }}"
               data-event-time="{{ $event->start_date ? $event->start_date->format('H:i') : '' }}"
               data-event-guests="{{ $event->guests->pluck('name')->implode(', ') }}"
               data-event-tags="{{ $event->tags->pluck('name')->implode(', ') }}"
               data-event-cover="{{ $event->cover ? Storage::url($event->cover) : '' }}">
                <img src="{{ asset('images/icons/edit-primary.svg') }}" class="icon-24" alt="edit-icon">
            </a>
            <a href="#" class="list-action-btn delete-event-btn"
               data-event-id="{{ $event->id }}"
               data-event-name="{{ $event->name }}">
                <img src="{{ asset('images/icons/trash-primary-red.svg') }}" class="icon-24" alt="delete-icon">
            </a>
        </div>
    </div>
@endforeach
