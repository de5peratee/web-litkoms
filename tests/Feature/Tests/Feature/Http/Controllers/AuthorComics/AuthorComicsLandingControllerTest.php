<?php

namespace Tests\Feature\Http\Controllers\AuthorComics;

use App\Http\Controllers\AuthorComics\AuthorComicsLandingController;
use App\Models\AuthorComics;
use App\Models\Genre;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AuthorComicsLandingControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    #[Test]
    public function index_returns_landing_page_with_comics_and_authors()
    {
        $author = User::factory()->create();
        $comic = AuthorComics::factory()->create([
            'name' => 'Test Comic',
            'is_moderated' => 'successful',
            'is_published' => true,
            'created_by' => $author->id,
            'cover' => 'author_comics_covers/test.jpg',
            'comics_file' => 'comics_files/test.pdf',
        ]);
        $genre = Genre::factory()->create(['name' => 'Action']);
        $comic->genres()->attach($genre);

        $response = $this->get(route('authors_comics_landing'));

        $response->assertStatus(200)
            ->assertViewIs('authors_comics.landing')
            ->assertViewHas('newComics', fn ($comics) => $comics->contains('name', 'Test Comic'))
            ->assertViewHas('topAuthors', fn ($authors) => $authors->contains('id', $author->id))
            ->assertViewHas('subscribedComics', fn ($subscribed) => $subscribed->isEmpty());
    }

    #[Test]
    public function index_returns_subscribed_comics_for_authenticated_user()
    {
        $author = User::factory()->create();
        $comic = AuthorComics::factory()->create([
            'name' => 'Subscribed Comic',
            'is_moderated' => 'successful',
            'is_published' => true,
            'created_by' => $author->id,
            'cover' => 'author_comics_covers/test.jpg',
            'comics_file' => 'comics_files/test.pdf',
        ]);
        $genre = Genre::factory()->create();
        $comic->genres()->attach($genre);

        $this->user->subscriptions()->attach($author->id);

        $response = $this->actingAs($this->user)->get(route('authors_comics_landing'));

        $response->assertStatus(200)
            ->assertViewIs('authors_comics.landing')
            ->assertViewHas('subscribedComics', fn ($subscribed) => $subscribed->contains('name', 'Subscribed Comic'));
    }
}
