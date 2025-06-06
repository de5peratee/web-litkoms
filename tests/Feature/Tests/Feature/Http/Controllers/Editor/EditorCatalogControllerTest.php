<?php

namespace Tests\Feature\Http\Controllers\Editor;

use App\Http\Controllers\Editor\EditorCatalogController;
use App\Models\Catalog;
use App\Models\Genre;
use App\Models\User;
use App\Services\ImageCompressionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\DomCrawler\Crawler;
use Tests\TestCase;

class EditorCatalogControllerTest extends TestCase
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
    public function index_returns_catalogs_with_genres_and_search()
    {
        $catalog = Catalog::factory()->create(['name' => 'Test Catalog']);
        $genre = Genre::factory()->create(['name' => 'Fantasy']);
        $catalog->genres()->attach($genre);
        $response = $this->actingAs($this->editor)->get(route('editor.catalogs_index', ['search' => 'Test']));
        $response->assertStatus(200)
            ->assertViewIs('editor.catalog.list')
            ->assertViewHas('catalogs', fn ($catalogs) => $catalogs->contains('name', 'Test Catalog'))
            ->assertViewHas('total', 1)
            ->assertViewHas('search', 'Test');
    }

    #[Test]
    public function load_more_returns_paginated_catalogs()
    {
        $genre = Genre::factory()->create();
        Catalog::factory()->count(15)->create()->each(function ($catalog) use ($genre) {
            $catalog->genres()->attach($genre);
        });

        $response = $this->actingAs($this->editor)->get(route('editor.catalogs_loadMore', ['page' => 2, 'search' => '']));

        $response->assertStatus(200)
            ->assertJsonStructure(['html', 'hasMore', 'nextPage'])
            ->assertJsonFragment(['hasMore' => false])
            ->assertJsonFragment(['nextPage' => 3]);

        $content = $response->json();
        $crawler = new Crawler($content['html']);
        $this->assertCount(5, $crawler->filter('.catalog-item'));
    }

    #[Test]
    public function create_returns_create_view()
    {
        $response = $this->actingAs($this->editor)->get(route('editor.create_catalog'));
        $response->assertStatus(200)
            ->assertViewIs('editor.catalog.create');
    }

    #[Test]
    public function store_creates_catalog_without_image()
    {
        $data = [
            'name' => 'New Catalog',
            'author' => 'Author Name',
            'description' => 'Description',
            'release_year' => 2023,
            'genres' => 'Fantasy,Adventure',
        ];

        $response = $this->actingAs($this->editor)->post(route('editor.store_catalog'), $data);
        $response->assertRedirect(route('editor.catalogs_index'))
            ->assertSessionHas('success', trans('messages.catalog_created'));

        $this->assertDatabaseHas('catalogs', [
            'name' => 'New Catalog',
            'author' => 'Author Name',
            'description' => 'Description',
            'release_year' => 2023,
            'cover' => null,
        ]);
        $this->assertDatabaseHas('genres', ['name' => 'Fantasy']);
        $this->assertDatabaseHas('genres', ['name' => 'Adventure']);
    }

    #[Test]
    public function update_modifies_catalog_without_image()
    {
        $catalog = Catalog::factory()->create([
            'name' => 'Old Catalog',
            'author' => 'Old Author',
            'description' => 'Old Description',
            'release_year' => 2022,
            'cover' => null,
        ]);
        $data = [
            'name' => 'Updated Catalog',
            'author' => 'Updated Author',
            'description' => 'Updated Description',
            'release_year' => 2024,
            'genres' => 'Sci-Fi',
        ];

        $response = $this->actingAs($this->editor)->patch(route('editor.update_catalog', $catalog), $data);

        $response->assertRedirect(route('editor.catalogs_index'))
            ->assertSessionHas('success', trans('messages.catalog_updated'));

        $this->assertDatabaseHas('catalogs', [
            'id' => $catalog->id,
            'name' => 'Updated Catalog',
            'author' => 'Updated Author',
            'description' => 'Updated Description',
            'release_year' => 2024,
            'cover' => null,
        ]);
        $this->assertDatabaseHas('genres', ['name' => 'Sci-Fi']);
    }

    #[Test]
    public function destroy_deletes_catalog_and_detaches_genres()
    {
        $catalog = Catalog::factory()->create(['cover' => null]);
        $genre = Genre::factory()->create();
        $catalog->genres()->attach($genre);

        $response = $this->actingAs($this->editor)->delete(route('editor.delete_catalog', $catalog));

        $response->assertRedirect(route('editor.catalogs_index'))
            ->assertSessionHas('success', trans('messages.catalog_deleted'));

        $this->assertDatabaseMissing('catalogs', ['id' => $catalog->id]);
        $this->assertDatabaseMissing('catalog_genres', ['catalog_id' => $catalog->id]);
    }
}