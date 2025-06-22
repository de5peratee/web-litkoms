<?php

namespace Tests\Feature\Http\Controllers\Editor;

use App\Http\Controllers\Editor\EditorPostController;
use App\Models\MultimediaPost;
use App\Models\User;
use App\Services\ImageCompressionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\DomCrawler\Crawler;
use Tests\TestCase;

class EditorPostControllerTest extends TestCase
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
    public function index_returns_media_posts_with_search()
    {
        $mediaPost = MultimediaPost::factory()->create([
            'name' => 'Test Post',
            'created_by' => $this->editor->id,
        ]);

        $response = $this->actingAs($this->editor)->get(route('editor.mediapost_index', ['search' => 'Test']));

        $response->assertStatus(200)
            ->assertViewIs('editor.mediaposts.list')
            ->assertViewHas('mediaPosts', fn ($mediaPosts) => $mediaPosts->contains('name', 'Test Post'))
            ->assertViewHas('total', 1)
            ->assertViewHas('search', 'Test');
    }

    #[Test]
    public function create_returns_create_view()
    {
        $response = $this->actingAs($this->editor)->get(route('editor.create_mediapost'));

        $response->assertStatus(200)
            ->assertViewIs('editor.mediaposts.create');
    }

    #[Test]
    public function store_creates_media_post_without_media()
    {
        $data = [
            'name' => 'New Post',
            'description' => 'Post Description',
        ];

        $response = $this->actingAs($this->editor)->post(route('editor.store_mediapost'), $data);

        $response->assertRedirect(route('editor.mediapost_index'))
            ->assertSessionHas('success', trans('messages.mediapost_created'));

        $this->assertDatabaseHas('multimedia_posts', [
            'name' => 'New Post',
            'description' => 'Post Description',
            'created_by' => $this->editor->id,
        ]);
    }

    #[Test]
    public function update_modifies_media_post_without_media()
    {
        $mediaPost = MultimediaPost::factory()->create([
            'name' => 'Old Post',
            'description' => 'Old Description',
            'created_by' => $this->editor->id,
        ]);
        $data = [
            'name' => 'Updated Post',
            'description' => 'Updated Description',
        ];

        $response = $this->actingAs($this->editor)->patch(route('editor.update_mediapost', $mediaPost), $data);

        $response->assertRedirect(route('editor.mediapost_index'))
            ->assertSessionHas('success', trans('messages.mediapost_updated'));

        $this->assertDatabaseHas('multimedia_posts', [
            'id' => $mediaPost->id,
            'name' => 'Updated Post',
            'description' => 'Updated Description',
        ]);
    }

    #[Test]
    public function destroy_deletes_media_post()
    {
        $mediaPost = MultimediaPost::factory()->create(['created_by' => $this->editor->id]);

        $response = $this->actingAs($this->editor)->delete(route('editor.delete_mediapost', $mediaPost));

        $response->assertRedirect(route('editor.mediapost_index'))
            ->assertSessionHas('success', trans('messages.mediapost_deleted'));

        $this->assertDatabaseMissing('multimedia_posts', ['id' => $mediaPost->id]);
    }
}
