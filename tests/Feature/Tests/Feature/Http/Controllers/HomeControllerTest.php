<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Catalog;
use App\Models\Event;
use App\Models\MultimediaPost;
use App\Services\NewsFormatterService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_displays_home_view()
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertViewIs('home');
    }

    public function test_index_shows_upcoming_events()
    {
        $upcomingEvent = Event::factory()->create(['start_date' => now()->addDays(1)]);
        $pastEvent = Event::factory()->create(['start_date' => now()->subDays(1)]);

        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertViewHas('events', function($events) use ($upcomingEvent, $pastEvent) {
            return $events->contains($upcomingEvent) && !$events->contains($pastEvent);
        });
    }

    public function test_index_shows_latest_comics()
    {
        $comic1 = Catalog::factory()->create(['created_at' => now()->subDay()]);
        $comic2 = Catalog::factory()->create(['created_at' => now()]);

        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertViewHas('comics', function($comics) use ($comic1, $comic2) {
            return $comics->contains($comic1) && $comics->contains($comic2);
        });
    }

    public function test_index_shows_combined_news()
    {
        $event = Event::factory()->create(['start_date' => now()->addDays(1)]);
        $mediaPost = MultimediaPost::factory()->create();

        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertViewHas('news', function ($news) use ($event, $mediaPost) {
            $eventNews = NewsFormatterService::formatEvent($event);
            $mediaNews = NewsFormatterService::formatMultimedia($mediaPost);

            return collect($news)->contains(function ($item) use ($eventNews) {
                    return $item->type === $eventNews->type &&
                        $item->id === $eventNews->id &&
                        $item->title === $eventNews->title;
                }) && collect($news)->contains(function ($item) use ($mediaNews) {
                    return $item->type === $mediaNews->type &&
                        $item->id === $mediaNews->id &&
                        $item->title === $mediaNews->title;
                });
        });
    }

    public function test_index_limits_news_to_3_items()
    {
        Event::factory()->count(5)->create(['start_date' => now()->addDays(1)]);
        MultimediaPost::factory()->count(5)->create();

        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertViewHas('news', function($news) {
            return count($news) <= 3;
        });
    }

    public function test_index_limits_events_to_4_items()
    {
        Event::factory()->count(5)->create(['start_date' => now()->addDays(1)]);

        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertViewHas('events', function($events) {
            return $events->count() <= 4;
        });
    }

    public function test_index_limits_comics_to_10_items()
    {
        Catalog::factory()->count(11)->create();

        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertViewHas('comics', function($comics) {
            return $comics->count() <= 10;
        });
    }

    public function test_index_orders_events_by_start_date_asc()
    {
        $event1 = Event::factory()->create(['start_date' => now()->addDays(3)]);
        $event2 = Event::factory()->create(['start_date' => now()->addDays(1)]);

        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $events = $response->viewData('events');
        $this->assertTrue($events[0]->start_date->lt($events[1]->start_date));
    }

    public function test_index_orders_comics_by_created_at_desc()
    {
        $comic1 = Catalog::factory()->create(['created_at' => now()->subDay()]);
        $comic2 = Catalog::factory()->create(['created_at' => now()]);

        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $comics = $response->viewData('comics');
        $this->assertTrue($comics[0]->created_at->gt($comics[1]->created_at));
    }
}