<?php

namespace Tests\Feature\Http\Controllers\AuthorComics;

use App\Http\Controllers\AuthorComics\AuthorComicsListController;
use App\Models\AuthorComics;
use App\Models\Comments;
use App\Models\Genre;
use App\Models\Rating;
use App\Models\User;
use App\Services\ViewCounterService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AuthorComicsListControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $viewCounterService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->viewCounterService = $this->createMock(ViewCounterService::class);
        $this->app->instance(ViewCounterService::class, $this->viewCounterService);
        Storage::fake('public');
    }

    #[Test]
    public function library_returns_comics_with_filters()
    {
        $comic = AuthorComics::factory()->create([
            'name' => 'Test Comic',
            'is_moderated' => 'successful',
            'is_published' => true,
            'cover' => 'author_comics_covers/test.jpg',
            'comics_file' => 'comics_files/test.pdf',
            'average_assessment' => 4.5,
        ]);
        $genre = Genre::factory()->create(['name' => 'Action']);
        $comic->genres()->attach($genre);

        $response = $this->get(route('authors_comics_library', [
            'search' => 'Test',
            'genres' => ['Action'],
            'sort' => 'rating-desc',
        ]));

        $response->assertStatus(200)
            ->assertViewIs('authors_comics.library')
            ->assertViewHas('comics', fn ($comics) => $comics->contains('name', 'Test Comic'))
            ->assertViewHas('genres', fn ($genres) => $genres->contains('name', 'Action'));
    }

    #[Test]
    public function library_returns_comics_via_ajax()
    {
        $comic = AuthorComics::factory()->create([
            'name' => 'Test Comic',
            'is_moderated' => 'successful',
            'is_published' => true,
            'cover' => 'author_comics_covers/test.jpg',
            'comics_file' => 'comics_files/test.pdf',
        ]);

        $response = $this->get(route('authors_comics_library', ['search' => 'Test']), ['X-Requested-With' => 'XMLHttpRequest']);

        $response->assertStatus(200)
            ->assertJsonStructure(['html', 'has_more'])
            ->assertJsonFragment(['has_more' => false]);
    }

    #[Test]
    public function show_fails_for_unpublished_comic()
    {
        $comic = AuthorComics::factory()->create([
            'slug' => 'test-comic',
            'is_moderated' => 'under review',
            'is_published' => false,
            'cover' => 'author_comics_covers/test.jpg',
            'comics_file' => 'comics_files/test.pdf',
        ]);

        $response = $this->get(route('author_comic', $comic->slug));

        $response->assertStatus(404);
    }

    #[Test]
    public function rate_updates_comic_rating()
    {
        $comic = AuthorComics::factory()->create([
            'slug' => 'test-comic',
            'is_moderated' => 'successful',
            'is_published' => true,
            'cover' => 'author_comics_covers/test.jpg',
            'comics_file' => 'comics_files/test.pdf',
        ]);
        $data = ['rating' => 5];

        $response = $this->actingAs($this->user)->post(route('author_comic.rate', $comic->slug), $data);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'average_rating' => '5.0',
                'user_rating' => 5,
            ]);

        $this->assertDatabaseHas('ratings', [
            'comics_to' => $comic->id,
            'graded_by' => $this->user->id,
            'grade' => 5,
        ]);
        $this->assertDatabaseHas('author_comics', [
            'id' => $comic->id,
            'average_assessment' => 5.0,
        ]);
    }

    #[Test]
    public function rate_removes_rating_when_zero()
    {
        $comic = AuthorComics::factory()->create([
            'slug' => 'test-comic',
            'is_moderated' => 'successful',
            'is_published' => true,
            'cover' => 'author_comics_covers/test.jpg',
            'comics_file' => 'comics_files/test.pdf',
        ]);
        Rating::factory()->create(['comics_to' => $comic->id, 'graded_by' => $this->user->id, 'grade' => 4]);
        $data = ['rating' => 0];

        $response = $this->actingAs($this->user)->post(route('author_comic.rate', $comic->slug), $data);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'average_rating' => '0.0',
                'user_rating' => 0,
            ]);

        $this->assertDatabaseMissing('ratings', [
            'comics_to' => $comic->id,
            'graded_by' => $this->user->id,
        ]);
        $this->assertDatabaseHas('author_comics', [
            'id' => $comic->id,
            'average_assessment' => 0.0,
        ]);
    }

    #[Test]
    public function comment_adds_new_comment()
    {
        $comic = AuthorComics::factory()->create([
            'slug' => 'test-comic',
            'is_moderated' => 'successful',
            'is_published' => true,
            'cover' => 'author_comics_covers/test.jpg',
            'comics_file' => 'comics_files/test.pdf',
        ]);
        $data = ['comment' => 'Great comic!'];

        $response = $this->actingAs($this->user)->post(route('author_comic.comment', $comic->slug), $data);

        $response->assertStatus(200)
            ->assertJsonStructure(['message', 'commentHtml'])
            ->assertJsonFragment(['message' => 'Комментарий добавлен.']);

        $this->assertDatabaseHas('comments', [
            'comics_to' => $comic->id,
            'created_by' => $this->user->id,
            'comment' => 'Great comic!',
        ]);
    }
}
