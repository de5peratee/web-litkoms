<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Event;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventControllerTest extends TestCase
{
    use RefreshDatabase;

    // Тесты для основного отображения
    public function test_index_displays_events_view()
    {
        $response = $this->get(route('events.index'));

        $response->assertStatus(200);
        $response->assertViewIs('events.index');
    }

    public function test_index_returns_paginated_events()
    {
        Event::factory()->count(15)->create();

        $response = $this->get(route('events.index'));

        $response->assertStatus(200);
        $response->assertViewHas('events');
    }

    public function test_index_shows_upcoming_events()
    {
        $upcoming = Event::factory()->create(['start_date' => now()->addDays(3)]);
        $past = Event::factory()->create(['start_date' => now()->subDays(3)]);

        $response = $this->get(route('events.index'));

        $response->assertStatus(200);
        $response->assertViewHas('upcomingEvents', function($events) use ($upcoming) {
            return $events->contains($upcoming);
        });
    }

    // Тесты фильтрации
    public function test_index_filters_by_search()
    {
        $matched = Event::factory()->create(['name' => 'Unique Event Name']);
        $unmatched = Event::factory()->create(['name' => 'Other Event']);

        $response = $this->get(route('events.index', ['search' => 'Unique']));

        $response->assertStatus(200);
        $response->assertViewHas('events', function($events) use ($matched, $unmatched) {
            return $events->contains($matched) && !$events->contains($unmatched);
        });
    }

    public function test_index_filters_by_categories()
    {
        $tag = Tag::factory()->create(['name' => 'Music']);
        $eventWithTag = Event::factory()->create();
        $eventWithTag->tags()->attach($tag);

        $eventWithoutTag = Event::factory()->create();

        $response = $this->get(route('events.index', ['categories' => ['Music']]));

        $response->assertStatus(200);
        $response->assertViewHas('events', function($events) use ($eventWithTag, $eventWithoutTag) {
            return $events->contains($eventWithTag) && !$events->contains($eventWithoutTag);
        });
    }

    // Тесты сортировки
    public function test_index_sorts_events_ascending()
    {
        $older = Event::factory()->create(['start_date' => now()->addDays(1)]);
        $newer = Event::factory()->create(['start_date' => now()->addDays(3)]);

        $response = $this->get(route('events.index', ['sort' => 'asc']));

        $response->assertStatus(200);
        $events = $response->viewData('events')->items();
        $this->assertTrue($events[0]->start_date->lt($events[1]->start_date));
    }

    public function test_index_sorts_events_descending()
    {
        $older = Event::factory()->create(['start_date' => now()->addDays(1)]);
        $newer = Event::factory()->create(['start_date' => now()->addDays(3)]);

        $response = $this->get(route('events.index', ['sort' => 'desc']));

        $response->assertStatus(200);
        $events = $response->viewData('events')->items();
        $this->assertTrue($events[0]->start_date->gt($events[1]->start_date));
    }

    // Тесты AJAX
    public function test_index_ajax_returns_json_response()
    {
        Event::factory()->count(3)->create();

        $response = $this->get(route('events.index', ['ajax' => 1]), [
            'X-Requested-With' => 'XMLHttpRequest'
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'html',
            'has_more',
        ]);
    }

    // Тесты для просмотра отдельного события
    public function test_get_event_displays_event_view()
    {
        $event = Event::factory()->create();

        $response = $this->get(route('events.get_event', $event));

        $response->assertStatus(200);
        $response->assertViewIs('events.event');
        $response->assertViewHas('event', $event);
    }

    public function test_get_event_returns_404_for_nonexistent_event()
    {
        $response = $this->get(route('events.get_event', 999));

        $response->assertNotFound();
    }

    // Тесты для проверки данных в представлении
    public function test_view_contains_required_data()
    {
        $event = Event::factory()->create();
        $tag = Tag::factory()->create();
        $event->tags()->attach($tag);

        $response = $this->get(route('events.index'));

        $response->assertSee($event->name);
        $response->assertSee($tag->name);
    }
}