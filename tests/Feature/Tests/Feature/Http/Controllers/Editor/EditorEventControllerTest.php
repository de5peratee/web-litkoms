<?php

namespace Tests\Feature\Http\Controllers\Editor;

use App\Http\Controllers\Editor\EditorEventController;
use App\Models\Event;
use App\Models\Guest;
use App\Models\Tag;
use App\Models\User;
use App\Services\ImageCompressionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\DomCrawler\Crawler;
use Tests\TestCase;

class EditorEventControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $editor;
    protected $imageCompressionService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->editor = User::factory()->create(['role' => 'editor']);

        $this->imageCompressionService = $this->createMock(ImageCompressionService::class);
        $this->app->instance(ImageCompressionService::class, $this->imageCompressionService);

        Storage::fake('public');
    }

    #[Test]

    public function index_returns_events_with_tags_guests_and_search()
    {
        $event = Event::factory()->create(['name' => 'Test Event', 'created_by' => $this->editor->id]);
        $tag = Tag::factory()->create(['name' => 'Conference']);
        $guest = Guest::factory()->create(['name' => 'John Doe']);
        $event->tags()->attach($tag);
        $event->guests()->attach($guest);


        $response = $this->actingAs($this->editor)->get(route('editor.events_index', ['search' => 'Test']));

        $response->assertStatus(200)
            ->assertViewIs('editor.events.list')
            ->assertViewHas('events', fn ($events) => $events->contains('name', 'Test Event'))
            ->assertViewHas('total', 1)
            ->assertViewHas('search', 'Test');
    }

    #[Test]
    public function load_more_returns_paginated_events()
    {
        $tag = Tag::factory()->create();
        $guest = Guest::factory()->create();
        Event::factory()->count(15)->create(['created_by' => $this->editor->id])->each(function ($event) use ($tag, $guest) {
            $event->tags()->attach($tag);
            $event->guests()->attach($guest);
        });

        $response = $this->actingAs($this->editor)->get(route('editor.events_loadMore', ['page' => 2, 'search' => '']));

        $response->assertStatus(200)
            ->assertJsonStructure(['html', 'hasMore', 'nextPage'])
            ->assertJsonFragment(['hasMore' => false])
            ->assertJsonFragment(['nextPage' => 3]);

        $content = $response->json();
        $crawler = new Crawler($content['html']);
        $this->assertCount(5, $crawler->filter('.event-item'));
    }

    #[Test]
    public function create_returns_create_view()
    {
        $response = $this->actingAs($this->editor)->get(route('editor.create_event'));

        $response->assertStatus(200)
            ->assertViewIs('editor.events.create');
    }

    #[Test]
    public function update_modifies_event_without_image()
    {
        $event = Event::factory()->create([
            'name' => 'Old Event',
            'description' => 'Old Description',
            'start_date' => '2025-06-01 10:00:00',
            'created_by' => $this->editor->id,
            'cover' => null,
        ]);
        $data = [
            'name' => 'Updated Event',
            'description' => 'Updated Description',
            'start_date' => '2025-06-15',
            'time' => '15:00',
            'guests' => 'Alice Brown',
            'tags' => 'Seminar',
        ];

        $response = $this->actingAs($this->editor)->patch(route('editor.update_event', $event), $data);

        $response->assertRedirect(route('editor.events_index'))
            ->assertSessionHas('success', trans('messages.event_updated'));

        $this->assertDatabaseHas('events', [
            'id' => $event->id,
            'name' => 'Updated Event',
            'description' => 'Updated Description',
            'start_date' => '2025-06-15 15:00:00',
            'cover' => null,
        ]);
        $this->assertDatabaseHas('guests', ['name' => 'Alice Brown']);
        $this->assertDatabaseHas('tags', ['name' => 'Seminar']);
    }

    #[Test]
    public function destroy_deletes_event_and_detaches_relations()
    {
        $event = Event::factory()->create(['created_by' => $this->editor->id, 'cover' => null]);
        $guest = Guest::factory()->create();
        $tag = Tag::factory()->create();
        $event->guests()->attach($guest);
        $event->tags()->attach($tag);

        $response = $this->actingAs($this->editor)->delete(route('editor.delete_event', $event));

        $response->assertRedirect(route('editor.events_index'))
            ->assertSessionHas('success', trans('messages.event_deleted'));

        $this->assertDatabaseMissing('events', ['id' => $event->id]);
        $this->assertDatabaseMissing('event_guests', ['event_id' => $event->id]);
        $this->assertDatabaseMissing('event_tags', ['event_id' => $event->id]);
        $this->assertDatabaseMissing('guests', ['id' => $guest->id]);
        $this->assertDatabaseMissing('tags', ['id' => $tag->id]);
    }
}