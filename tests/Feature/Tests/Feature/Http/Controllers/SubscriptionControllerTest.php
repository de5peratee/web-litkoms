<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubscriptionControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_subscribe_adds_subscription_successfully()
    {
        $user = User::factory()->create(['nickname' => 'testuser']);
        $loggedInUser = User::factory()->create();

        $this->actingAs($loggedInUser);
        $response = $this->postJson(route('subscribe', ['nickname' => 'testuser']));

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'subscribed',
            'isSub' => true,
            'subscribersCount' => 1,
        ]);
        $this->assertDatabaseHas('subscribes', [
            'subscriber_id' => $loggedInUser->id,
            'subscribed_to_id' => $user->id,
        ]);
    }

    public function test_subscribe_fails_for_self_subscription()
    {
        $user = User::factory()->create(['nickname' => 'testuser']);
        $this->actingAs($user);
        $response = $this->postJson(route('subscribe', ['nickname' => 'testuser']));

        $response->assertStatus(422);
        $response->assertJson(['error' => 'Нельзя подписаться на себя']);
        $this->assertDatabaseMissing('subscribes', [
            'subscriber_id' => $user->id,
            'subscribed_to_id' => $user->id,
        ]);
    }

    public function test_subscribe_fails_for_nonexistent_user()
    {
        $loggedInUser = User::factory()->create();
        $this->actingAs($loggedInUser);
        $response = $this->postJson(route('subscribe', ['nickname' => 'nonexistent']));

        $response->assertNotFound();
    }

    public function test_unsubscribe_removes_subscription_successfully()
    {
        $user = User::factory()->create(['nickname' => 'testuser']);
        $loggedInUser = User::factory()->create();
        $loggedInUser->subscriptions()->attach($user);

        $this->actingAs($loggedInUser);
        $response = $this->postJson(route('unsubscribe', ['nickname' => 'testuser']));

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'unsubscribed',
            'isSub' => false,
            'subscribersCount' => 0,
        ]);
        $this->assertDatabaseMissing('subscribes', [
            'subscriber_id' => $loggedInUser->id,
            'subscribed_to_id' => $user->id,
        ]);
    }

    public function test_unsubscribe_fails_for_nonexistent_user()
    {
        $loggedInUser = User::factory()->create();
        $this->actingAs($loggedInUser);
        $response = $this->postJson(route('unsubscribe', ['nickname' => 'nonexistent']));

        $response->assertNotFound();
    }

    public function test_subscribe_requires_authentication()
    {
        $user = User::factory()->create(['nickname' => 'testuser']);
        $response = $this->postJson(route('subscribe', ['nickname' => 'testuser']));

        $response->assertStatus(401);
    }

    public function test_unsubscribe_requires_authentication()
    {
        $user = User::factory()->create(['nickname' => 'testuser']);
        $response = $this->postJson(route('unsubscribe', ['nickname' => 'testuser']));

        $response->assertStatus(401);
    }
}