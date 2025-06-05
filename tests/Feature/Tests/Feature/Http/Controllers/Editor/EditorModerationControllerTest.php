<?php

namespace Tests\Feature\Http\Controllers\Editor;

use App\Http\Controllers\Editor\EditorModerationController;
use App\Models\AuthorComics;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class EditorModerationControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $editor;

    protected function setUp(): void
    {
        parent::setUp();

        $this->editor = User::factory()->create(['role' => 'editor']);
    }

    #[Test]
    public function index_returns_comics_with_search_and_status()
    {
        $user = User::factory()->create();
        $comic = AuthorComics::factory()->create([
            'name' => 'Test Comic',
            'slug' => 'test-comic',
            'is_moderated' => 'under review',
            'created_by' => $user->id,
        ]);

        $response = $this->actingAs($this->editor)->get(route('editor.comics_submissions_index', ['search' => 'Test', 'status' => 'under review']));

        $response->assertStatus(200)
            ->assertViewIs('editor.comics.submissions')
            ->assertViewHas('comics', fn ($comics) => $comics->contains('name', 'Test Comic'))
            ->assertViewHas('comics_count', 1)
            ->assertViewHas('status', 'under review');
    }

    #[Test]
    public function index_returns_comics_via_ajax()
    {
        $user = User::factory()->create();
        $comic = AuthorComics::factory()->create([
            'name' => 'Test Comic',
            'slug' => 'test-comic',
            'is_moderated' => 'under review',
            'created_by' => $user->id,
        ]);

        $response = $this->actingAs($this->editor)
            ->get(route('editor.comics_submissions_index', ['search' => 'Test', 'status' => 'under review']), ['X-Requested-With' => 'XMLHttpRequest']);

        $response->assertStatus(200)
            ->assertViewIs('editor.comics.partials.submissions_list')
            ->assertViewHas('comics', fn ($comics) => $comics->contains('name', 'Test Comic'))
            ->assertViewHas('comics_count', 1)
            ->assertViewHas('status', 'under review');
    }

    #[Test]
    public function show_returns_comic_for_moderation()
    {
        $user = User::factory()->create();
        $comic = AuthorComics::factory()->create([
            'slug' => 'test-comic',
            'is_moderated' => 'under review',
            'created_by' => $user->id,
        ]);

        $response = $this->actingAs($this->editor)->get(route('editor.comic_moderation', $comic->slug));

        $response->assertStatus(200)
            ->assertViewIs('editor.comics.moderation')
            ->assertViewHas('comic', fn ($viewComic) => $viewComic->slug === 'test-comic');
    }

    #[Test]
    public function show_fails_for_non_moderated_comic()
    {
        $user = User::factory()->create();
        $comic = AuthorComics::factory()->create([
            'slug' => 'test-comic',
            'is_moderated' => 'successful',
            'created_by' => $user->id,
        ]);

        $response = $this->actingAs($this->editor)->get(route('editor.comic_moderation', $comic->slug));

        $response->assertRedirect()
            ->assertSessionHas('error', 'Комикс не находится на модерации.');
    }

    #[Test]
    public function update_accepts_comic_with_valid_status()
    {
        $user = User::factory()->create();
        $comic = AuthorComics::factory()->create([
            'slug' => 'test-comic',
            'is_moderated' => 'under review',
            'created_by' => $user->id,
        ]);
        $data = [
            'age_restriction' => 12,
            'moderation_status' => 'successful',
        ];

        $response = $this->actingAs($this->editor)->put(route('editor.comic_moderation', $comic->slug), $data);

        $response->assertRedirect(route('editor.comics_submissions_index'))
            ->assertSessionHas('success', 'Комикс успешно принят.');

        $this->assertDatabaseHas('author_comics', [
            'slug' => 'test-comic',
            'is_moderated' => 'successful',
            'age_restriction' => 12,
            'feedback' => null,
        ]);
    }

    #[Test]
    public function update_rejects_comic_with_feedback()
    {
        $user = User::factory()->create();
        $comic = AuthorComics::factory()->create([
            'slug' => 'test-comic',
            'is_moderated' => 'under review',
            'created_by' => $user->id,
        ]);
        $data = [
            'age_restriction' => 16,
            'moderation_status' => 'unsuccessful',
            'feedback' => 'Needs more details.',
        ];

        $response = $this->actingAs($this->editor)->put(route('editor.comic_moderation', $comic->slug), $data);

        $response->assertRedirect(route('editor.comics_submissions_index'))
            ->assertSessionHas('success', 'Комикс отклонен, фидбек сохранен.');

        $this->assertDatabaseHas('author_comics', [
            'slug' => 'test-comic',
            'is_moderated' => 'unsuccessful',
            'age_restriction' => 16,
            'feedback' => 'Needs more details.',
        ]);
    }

    #[Test]
    public function update_accepts_comic_via_ajax()
    {
        $user = User::factory()->create();
        $comic = AuthorComics::factory()->create([
            'slug' => 'test-comic',
            'is_moderated' => 'under review',
            'created_by' => $user->id,
        ]);
        $data = [
            'age_restriction' => 12,
            'moderation_status' => 'successful',
        ];

        $response = $this->actingAs($this->editor)
            ->put(route('editor.comic_moderation', $comic->slug), $data, ['X-Requested-With' => 'XMLHttpRequest']);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Комикс успешно принят.',
                'status' => 'success',
            ]);

        $this->assertDatabaseHas('author_comics', [
            'slug' => 'test-comic',
            'is_moderated' => 'successful',
            'age_restriction' => 12,
            'feedback' => null,
        ]);
    }
}
