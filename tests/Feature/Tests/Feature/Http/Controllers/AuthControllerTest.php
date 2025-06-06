<?php

namespace Tests\Feature\Http\Controllers;

use App\Http\Controllers\AuthController;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\StoreRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function index_returns_auth_view()
    {
        $response = $this->get(route('auth'));

        $response->assertStatus(200)
            ->assertViewIs('auth');
    }

    #[Test]
    public function login_successful_with_valid_credentials()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->post(route('auth.login'), [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticatedAs($user);
    }

    #[Test]
    public function login_fails_with_invalid_credentials()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->post(route('auth.login'), [
            'email' => 'test@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(302)
            ->assertSessionHasErrors(['email' => 'Неверные учётные данные']);
        $this->assertGuest();
    }

    #[Test]
    public function login_validation_fails_with_invalid_data()
    {
        $response = $this->post(route('auth.login'), [
            'email' => 'invalid-email',
            'password' => '',
        ]);

        $response->assertSessionHasErrors([
            'email' => 'Не корректный адрес почты',
            'password' => 'Поле не должно быть пустым',
        ]);
        $this->assertGuest();
    }

    #[Test]
    public function register_creates_user_and_logs_in()
    {
        Event::fake();

        $response = $this->post(route('auth.register'), [
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'nickname' => 'testuser',
            'name' => 'Test',
            'last_name' => 'User',
            'birth_date' => '1990-01-01',
        ]);

        $response->assertRedirect(route('verification.notice'));

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'nickname' => 'testuser',
            'name' => 'Test',
            'last_name' => 'User',
        ]);

        $user = User::where('email', 'test@example.com')->first();
        $this->assertAuthenticatedAs($user);
        Event::assertDispatched(Registered::class);
    }

    #[Test]
    public function register_fails_with_invalid_data()
    {
        $response = $this->post(route('auth.register'), [
            'email' => 'invalid-email',
            'password' => 'short',
            'password_confirmation' => 'different',
            'nickname' => 'ab',
            'name' => '123',
            'last_name' => '',
            'birth_date' => 'invalid-date',
        ]);

        $response->assertSessionHasErrors([
            'email' => 'Поле должно быть корректным email-адресом.',
            'password' => 'Пароль должен содержать минимум 8 символов.',
            'password' => 'Пароль и подтверждение пароля не совпадают.',
            'nickname' => 'Поле должно содержать минимум 5 символов.',
            'name' => 'Поле должно содержать только буквы.',
            'last_name' => 'Поле не должно быть пустым',
            'birth_date' => 'Поле должно быть корректной датой.',
        ]);

        $this->assertGuest();
    }

    #[Test]
    public function register_fails_with_duplicate_email()
    {
        User::factory()->create([
            'email' => 'test@example.com',
            'nickname' => 'existinguser',
        ]);

        $response = $this->post(route('auth.register'), [
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'nickname' => 'testuser',
            'name' => 'Test',
            'last_name' => 'User',
            'birth_date' => '1990-01-01',
        ]);

        $response->assertSessionHasErrors([
            'email' => 'Данный email уже зарегистрирован.',
        ]);
    }

    #[Test]
    public function logout_logs_out_user()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post(route('logout'));

        $response->assertRedirect('/');
        $this->assertGuest();
    }
}