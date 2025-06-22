<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\AuthorComics;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_displays_user_profile_view()
    {
        $user = User::factory()->create(['nickname' => 'testuser']);

        $response = $this->get(route('profile.index', ['nickname' => 'testuser']));

        $response->assertStatus(200);
        $response->assertViewIs('user.profile');
        $response->assertViewHas('user', $user);
    }

    public function test_index_returns_404_for_nonexistent_user()
    {
        $response = $this->get(route('profile.index', ['nickname' => 'nonexistent']));

        $response->assertNotFound();
    }

    public function test_index_shows_user_subscribers_and_subscriptions_count()
    {
        $user = User::factory()->create(['nickname' => 'testuser']);
        $subscriber = User::factory()->create();
        $subscription = User::factory()->create();
        $user->subscribers()->attach($subscriber);
        $user->subscriptions()->attach($subscription);

        $response = $this->get(route('profile.index', ['nickname' => 'testuser']));

        $response->assertStatus(200);
        $response->assertViewHas('subscribersCount', 1);
        $response->assertViewHas('user', function ($viewUser) {
            return $viewUser->subscribers_count === 1 && $viewUser->subscriptions_count === 1;
        });
    }

    public function test_index_shows_user_comics()
    {
        $user = User::factory()->create(['nickname' => 'testuser']);
        $comic1 = AuthorComics::factory()->create([
            'created_by' => $user->id,
            'is_published' => true,
            'is_moderated' => 'successful',
            'created_at' => now()->subDay(),
        ]);
        $comic2 = AuthorComics::factory()->create([
            'created_by' => $user->id,
            'is_published' => true,
            'is_moderated' => 'successful',
            'created_at' => now(),
        ]);

        $response = $this->get(route('profile.index', ['nickname' => 'testuser']));

        $response->assertStatus(200);
        $response->assertViewHas('comics', function ($comics) use ($comic1, $comic2) {
            return $comics->contains($comic1) && $comics->contains($comic2) && $comics->count() === 2;
        });
    }

    public function test_index_limits_comics_to_10()
    {
        $user = User::factory()->create(['nickname' => 'testuser']);
        AuthorComics::factory()->count(11)->create([
            'created_by' => $user->id,
            'is_published' => true,
            'is_moderated' => 'successful',
        ]);

        $response = $this->get(route('profile.index', ['nickname' => 'testuser']));

        $response->assertStatus(200);
        $response->assertViewHas('comics', function ($comics) {
            return $comics->count() === 10;
        });
    }

    public function test_index_orders_comics_by_created_at_desc()
    {
        $user = User::factory()->create(['nickname' => 'testuser']);
        $comic1 = AuthorComics::factory()->create([
            'created_by' => $user->id,
            'is_published' => true,
            'is_moderated' => 'successful',
            'created_at' => now()->subDay(),
        ]);
        $comic2 = AuthorComics::factory()->create([
            'created_by' => $user->id,
            'is_published' => true,
            'is_moderated' => 'successful',
            'created_at' => now(),
        ]);

        $response = $this->get(route('profile.index', ['nickname' => 'testuser']));

        $response->assertStatus(200);
        $response->assertViewHas('comics', function ($comics) use ($comic1, $comic2) {
            return $comics->first()->id === $comic2->id && $comics->last()->id === $comic1->id;
        });
    }

    public function test_index_shows_is_sub_false_for_guest()
    {
        $user = User::factory()->create(['nickname' => 'testuser']);

        $response = $this->get(route('profile.index', ['nickname' => 'testuser']));

        $response->assertStatus(200);
        $response->assertViewHas('isSub', false);
    }

    public function test_index_shows_is_sub_true_for_subscriber()
    {
        $user = User::factory()->create(['nickname' => 'testuser']);
        $loggedInUser = User::factory()->create();
        $loggedInUser->subscriptions()->attach($user);

        $this->actingAs($loggedInUser);
        $response = $this->get(route('profile.index', ['nickname' => 'testuser']));

        $response->assertStatus(200);
        $response->assertViewHas('isSub', true);
    }

    public function test_index_shows_is_sub_false_for_non_subscriber()
    {
        $user = User::factory()->create(['nickname' => 'testuser']);
        $loggedInUser = User::factory()->create();

        $this->actingAs($loggedInUser);
        $response = $this->get(route('profile.index', ['nickname' => 'testuser']));

        $response->assertStatus(200);
        $response->assertViewHas('isSub', false);
    }

    public function test_index_shows_average_rating_and_comics_count()
    {
        $user = User::factory()->create(['nickname' => 'testuser']);
        AuthorComics::factory()->create([
            'created_by' => $user->id,
            'is_published' => true,
            'is_moderated' => 'successful',
            'average_assessment' => 4,
        ]);
        AuthorComics::factory()->create([
            'created_by' => $user->id,
            'is_published' => true,
            'is_moderated' => 'successful',
            'average_assessment' => 5,
        ]);

        $response = $this->get(route('profile.index', ['nickname' => 'testuser']));

        $response->assertStatus(200);
        $response->assertViewHas('averageRating', 4.5); // Среднее: (4 + 5) / 2 = 4.5
        $response->assertViewHas('comicsCount', 2);
    }

    public function test_index_excludes_unpublished_or_unmoderated_comics()
    {
        $user = User::factory()->create(['nickname' => 'testuser']);
        $publishedComic = AuthorComics::factory()->create([
            'created_by' => $user->id,
            'is_published' => true,
            'is_moderated' => 'successful',
        ]);
        $unpublishedComic = AuthorComics::factory()->create([
            'created_by' => $user->id,
            'is_published' => false,
            'is_moderated' => 'successful',
        ]);
        $unmoderatedComic = AuthorComics::factory()->create([
            'created_by' => $user->id,
            'is_published' => true,
            'is_moderated' => 'under review',
        ]);

        $response = $this->get(route('profile.index', ['nickname' => 'testuser']));

        $response->assertStatus(200);
        $response->assertViewHas('comics', function ($comics) use ($publishedComic, $unpublishedComic, $unmoderatedComic) {
            return $comics->contains($publishedComic) &&
                !$comics->contains($unpublishedComic) &&
                !$comics->contains($unmoderatedComic);
        });
    }
}