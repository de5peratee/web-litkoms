<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UserSettingsControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_show_returns_settings_view_for_authenticated_user(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('settings.show'));

        $response->assertStatus(200);
        $response->assertViewIs('user.settings');
        $response->assertViewHas('user', $user);
    }

    public function test_update_user_settings_with_valid_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->put(route('settings.update'), [
            'nickname' => 'new_nickname',
            'email' => 'new@example.com',
            'birth_date' => '2000-01-01',
            'name' => 'John',
            'last_name' => 'Doe',
        ]);

        $response->assertRedirect(route('settings.show'));

        $user->refresh();

        $this->assertEquals('new_nickname', $user->nickname);
        $this->assertEquals('new@example.com', $user->email);
        $this->assertEquals('2000-01-01', $user->birth_date);
        $this->assertEquals('John', $user->name);
        $this->assertEquals('Doe', $user->last_name);
    }

    public function test_update_user_settings_with_invalid_data_should_fail(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->from(route('settings.show'))->put(route('settings.update'), [
            'email' => 'not-an-email',
        ]);

        $response->assertRedirect(route('settings.show'));
        $response->assertSessionHasErrors(['email']);
    }

    public function test_update_does_not_change_password_if_not_provided(): void
    {
        $user = User::factory()->create([
            'nickname' => 'valid_nickname',
            'password' => bcrypt('originalPassword123'),
        ]);

        $this->actingAs($user);

        $response = $this->put(route('settings.update'), [
            'nickname' => $user->nickname,
            'name' => $user->name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'birth_date' => $user->birth_date,
        ]);

        $response->assertRedirect(route('settings.show'));

        $user->refresh();

        $this->assertTrue(\Hash::check('originalPassword123', $user->password));
    }
}
