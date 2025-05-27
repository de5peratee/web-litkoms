<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function auth_page_is_accessible_by_guest()
    {
        $response = $this->get(route('auth'));
        $response->assertStatus(200);
        $response->assertViewIs('auth');
    }

    #[Test]
    public function auth_page_redirects_authenticated_user()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('auth'));
        $response->assertRedirect(route('home'));
    }

    #[Test]
    public function user_can_register_successfully()
    {
        $userData = $this->validRegistrationData();

        $response = $this->post(route('auth.register'), $userData);

        $response->assertRedirect(route('home'));
        $this->assertAuthenticated();

        $this->assertDatabaseHas('users', [
            'email' => $userData['email'],
            'nickname' => $userData['nickname'],
        ]);

        $user = User::where('email', $userData['email'])->first();
        $this->assertTrue(Hash::check('password123', $user->password));
    }

    #[Test]
    public function registration_fails_with_missing_required_fields()
    {
        $response = $this->post(route('auth.register'), []);

        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'nickname', 'email', 'name', 'last_name', 'birth_date', 'password',
        ]);
    }

    #[Test]
    public function registration_fails_with_duplicate_email_or_nickname()
    {
        $existingUser = User::factory()->create([
            'email' => 'test@example.com',
            'nickname' => 'tester',
        ]);

        $userData = $this->validRegistrationData([
            'email' => 'test@example.com',
            'nickname' => 'tester',
        ]);

        $response = $this->post(route('auth.register'), $userData);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['email', 'nickname']);
        $this->assertGuest();
    }

    #[Test]
    public function registration_fails_if_passwords_do_not_match()
    {
        $data = $this->validRegistrationData([
            'password_confirmation' => 'wrongconfirmation',
        ]);

        $response = $this->post(route('auth.register'), $data);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['password']);
    }

    #[Test]
    public function registration_fails_with_invalid_email_format()
    {
        $data = $this->validRegistrationData([
            'email' => 'invalid-email',
        ]);

        $response = $this->post(route('auth.register'), $data);

        $response->assertSessionHasErrors(['email']);
    }

    #[Test]
    public function user_can_login_with_correct_credentials()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('secret123'),
        ]);

        $response = $this->post(route('auth.login'), [
            'email' => 'test@example.com',
            'password' => 'secret123',
        ]);

        $response->assertRedirect(route('home'));
        $this->assertAuthenticatedAs($user);
    }

    #[Test]
    public function login_fails_with_wrong_password()
    {
        User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('secret123'),
        ]);

        $response = $this->post(route('auth.login'), [
            'email' => 'test@example.com',
            'password' => 'wrongpass',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }

    #[Test]
    public function login_fails_with_nonexistent_user()
    {
        $response = $this->post(route('auth.login'), [
            'email' => 'nouser@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }

    #[Test]
    public function authenticated_user_can_logout()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post(route('logout'));

        $response->assertRedirect(route('home'));
        $this->assertGuest();
    }

    #[Test]
    public function guest_cannot_logout()
    {
        $response = $this->post(route('logout'));
        $response->assertRedirect(route('home'));
        $this->assertGuest();
    }

    private function validRegistrationData(array $overrides = []): array
    {
        return array_merge([
            'nickname' => 'testuser',
            'email' => 'test@example.com',
            'name' => 'Test',
            'last_name' => 'User',
            'birth_date' => '1990-01-01',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ], $overrides);
    }
}
