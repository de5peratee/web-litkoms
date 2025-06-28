<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Catalog;
use App\Models\Genre;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CatalogControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_displays_catalog_view()
    {
        $response = $this->get(route('library.index'));

        $response->assertStatus(200);
        $response->assertViewIs('library.index');
    }

    public function test_index_returns_paginated_catalogs()
    {
        Catalog::factory()->count(15)->create();

        $response = $this->get(route('library.index'));

        $response->assertStatus(200);
        $response->assertViewHas('library');
    }

    public function test_index_filters_by_genre()
    {
        $genre = Genre::factory()->create(['name' => 'Fantasy']);
        $catalog = Catalog::factory()->create();
        $catalog->genres()->attach($genre);

        $other = Catalog::factory()->create();

        $response = $this->get(route('library.index', ['genres' => ['Fantasy']]));

        $response->assertStatus(200);
        $response->assertViewHas('library', function ($items) use ($catalog) {
            return $items->contains($catalog);
        });
    }

    public function test_index_filters_by_search()
    {
        $catalog = Catalog::factory()->create(['name' => 'My Unique Comic']);
        $other = Catalog::factory()->create(['name' => 'Another Comic']);

        $response = $this->get(route('library.index', ['search' => 'Unique']));

        $response->assertStatus(200);
        $response->assertViewHas('library', function ($items) use ($catalog) {
            return $items->contains($catalog) && !$items->contains('Another Comic');
        });
    }

    public function test_index_sorts_by_release_year()
    {
        $older = Catalog::factory()->create(['release_year' => 1999]);
        $newer = Catalog::factory()->create(['release_year' => 2022]);

        $response = $this->get(route('library.index', ['sort' => 'asc']));

        $response->assertStatus(200);
        $sorted = $response->viewData('library')->items();

        $this->assertTrue($sorted[0]->release_year <= $sorted[1]->release_year);
    }

    public function test_index_ajax_returns_html_fragment()
    {
        Catalog::factory()->count(3)->create();

        $response = $this->get(route('library.index', ['ajax' => 1]), [
            'X-Requested-With' => 'XMLHttpRequest'
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'html',
            'has_more',
        ]);
    }

    public function test_get_book_displays_book_view()
    {
        $catalog = Catalog::factory()->create();
        session(['catalog_url' => route('library.index')]);

        $response = $this->get(route('library.get_book', ['id' => $catalog->id]));

        $response->assertStatus(200);
        $response->assertViewIs('library.book');
        $response->assertViewHas('book', $catalog);
    }

    public function test_get_book_404_if_not_found()
    {
        $response = $this->get(route('library.get_book', ['id' => 999]));

        $response->assertNotFound();
    }
}
