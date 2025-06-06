<?php

namespace Tests\Feature\Http\Controllers\AuthorComics;

use App\Http\Controllers\AuthorComics\AuthorComicsController;
use App\Models\AuthorComics;
use App\Models\Genre;
use App\Models\User;
use App\Services\ImageCompressionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\DomCrawler\Crawler;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use App\Http\Requests\User\AuthorComicUpdateRequest;

class AuthorComicsControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $imageCompressionService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $this->imageCompressionService = $this->createMock(ImageCompressionService::class);
        $this->app->instance(ImageCompressionService::class, $this->imageCompressionService);

        Storage::fake('public');
    }

    #[Test]
    public function load_more_returns_paginated_comics()
    {
        $genre = Genre::factory()->create();
        AuthorComics::factory()->count(15)->create([
            'created_by' => $this->user->id,
            'cover' => 'author_comics_covers/test.jpg',
            'comics_file' => 'comics_files/test.pdf',
        ])->each(function ($comic) use ($genre) {
            $comic->genres()->attach($genre);
        });

        $response = $this->actingAs($this->user)->get(route('user.author_comics_loadMore', ['page' => 2, 'search' => '']));

        $response->assertStatus(200)
            ->assertJsonStructure(['html', 'hasMore', 'nextPage'])
            ->assertJsonFragment(['hasMore' => false]) // Ожидаем hasMore => false
            ->assertJsonFragment(['nextPage' => 3]);

        $content = $response->json();
        $crawler = new Crawler($content['html']);
        $this->assertCount(5, $crawler->filter('.comic-item'));
    }

    #[Test]
    public function show_moderation_confirm_returns_view_for_user_comic()
    {
        $comic = AuthorComics::factory()->create([
            'slug' => 'test-comic',
            'created_by' => $this->user->id,
            'is_moderated' => 'under review',
            'is_published' => false,
            'cover' => 'author_comics_covers/test.jpg',
            'comics_file' => 'comics_files/test.pdf',
        ]);

        $response = $this->actingAs($this->user)->get(route('user.moderation-confirm-comics', $comic));

        $response->assertStatus(200)
            ->assertViewIs('user.author_comics.moderation-confirm-comics')
            ->assertViewHas('comic', fn ($viewComic) => $viewComic->slug === 'test-comic');
    }

    #[Test]
    public function show_moderation_confirm_fails_for_published_comic()
    {
        $comic = AuthorComics::factory()->create([
            'slug' => 'test-comic',
            'created_by' => $this->user->id,
            'is_moderated' => 'successful',
            'is_published' => true,
            'cover' => 'author_comics_covers/test.jpg',
            'comics_file' => 'comics_files/test.pdf',
        ]);

        $response = $this->actingAs($this->user)->get(route('user.moderation-confirm-comics', $comic));

        $response->assertRedirect(route('user.author_comics'))
            ->assertSessionHas('error', 'Комикс уже опубликован.');
    }

    #[Test]
    public function show_moderation_confirm_fails_for_unauthorized_user()
    {
        $otherUser = User::factory()->create();
        $comic = AuthorComics::factory()->create([
            'slug' => 'test-comic',
            'created_by' => $otherUser->id,
            'cover' => 'author_comics_covers/test.jpg',
            'comics_file' => 'comics_files/test.pdf',
        ]);

        $response = $this->actingAs($this->user)->get(route('user.moderation-confirm-comics', $comic));

        $response->assertStatus(403);
    }

    #[Test]
    public function create_returns_create_view()
    {
        $response = $this->actingAs($this->user)->get(route('user.create_author_comics'));

        $response->assertStatus(200)
            ->assertViewIs('user.author_comics.create');
    }

    #[Test]
    public function update_modifies_comic_without_files()
    {
        $comic = AuthorComics::factory()->create([
            'name' => 'Old Comic',
            'slug' => 'old-comic',
            'description' => 'Old Description',
            'age_restriction' => 6,
            'created_by' => $this->user->id,
            'is_moderated' => 'under review',
            'cover' => 'author_comics_covers/old.jpg',
            'comics_file' => 'comics_files/old.pdf',
        ]);
        $data = [
            'name' => 'Updated Comic',
            'description' => 'Updated Description',
            'age_restriction' => 16,
            'genres' => 'Sci-Fi',
        ];

        $this->partialMock(AuthorComicUpdateRequest::class, function ($mock) use ($data) {
            $mock->shouldReceive('validated')->andReturn($data);
        });

        $response = $this->actingAs($this->user)->patch(route('user.update_author_comics', $comic), $data);

        $response->assertRedirect(route('user.author_comics'))
            ->assertSessionHas('success', 'Комикс успешно обновлен!');

        $this->assertDatabaseHas('author_comics', [
            'id' => $comic->id,
            'name' => 'Updated Comic',
            'description' => 'Updated Description',
            'age_restriction' => 16,
            'is_moderated' => 'under review',
            'feedback' => '',
        ]);
        $this->assertDatabaseHas('genres', ['name' => 'Sci-Fi']);
    }

    #[Test]
    public function update_fails_for_unauthorized_user()
    {
        $otherUser = User::factory()->create();
        $comic = AuthorComics::factory()->create([
            'created_by' => $otherUser->id,
            'cover' => 'author_comics_covers/old.jpg',
            'comics_file' => 'comics_files/old.pdf',
        ]);
        $data = [
            'name' => 'Updated Comic',
            'description' => 'Updated Description',
            'age_restriction' => 16,
            'genres' => 'Sci-Fi',
        ];

        $response = $this->actingAs($this->user)->patch(route('user.update_author_comics', $comic), $data);

        $response->assertStatus(403);
    }

    #[Test]
    public function destroy_fails_for_unauthorized_user()
    {
        $otherUser = User::factory()->create();
        $comic = AuthorComics::factory()->create([
            'created_by' => $otherUser->id,
            'cover' => 'author_comics_covers/test.jpg',
            'comics_file' => 'comics_files/test.pdf',
        ]);

        $response = $this->actingAs($this->user)->delete(route('user.delete_author_comics', $comic));

        $response->assertStatus(403);
    }

    #[Test]
    public function publish_publishes_successful_comic()
    {
        $comic = AuthorComics::factory()->create([
            'slug' => 'test-comic',
            'created_by' => $this->user->id,
            'is_moderated' => 'successful',
            'is_published' => false,
            'cover' => 'author_comics_covers/test.jpg',
            'comics_file' => 'comics_files/test.pdf',
        ]);

        $response = $this->actingAs($this->user)->post(route('user.publish_comic', $comic));

        $response->assertRedirect(route('user.moderation-confirm-comics', $comic->slug))
            ->assertSessionHas('success', 'Комикс успешно опубликован!');

        $this->assertDatabaseHas('author_comics', [
            'id' => $comic->id,
            'is_published' => true,
            'published_at' => now()->toDateTimeString(),
        ]);
    }

    #[Test]
    public function publish_fails_for_non_successful_comic()
    {
        $comic = AuthorComics::factory()->create([
            'slug' => 'test-comic',
            'created_by' => $this->user->id,
            'is_moderated' => 'under review',
            'cover' => 'author_comics_covers/test.jpg',
            'comics_file' => 'comics_files/test.pdf',
        ]);

        $response = $this->actingAs($this->user)->post(route('user.publish_comic', $comic));

        $response->assertRedirect()
            ->assertSessionHasErrors(['error' => 'Комикс не прошел модерацию и не может быть опубликован.']);
    }

    #[Test]
    public function publish_fails_for_unauthorized_user()
    {
        $otherUser = User::factory()->create();
        $comic = AuthorComics::factory()->create([
            'slug' => 'test-comic',
            'created_by' => $otherUser->id,
            'is_moderated' => 'successful',
            'cover' => 'author_comics_covers/test.jpg',
            'comics_file' => 'comics_files/test.pdf',
        ]);

        $response = $this->actingAs($this->user)->post(route('user.publish_comic', $comic));

        $response->assertStatus(403);
    }
}
