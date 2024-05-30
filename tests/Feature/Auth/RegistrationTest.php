<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Illuminate\Support\Facades\Event;

class RegistrationTest extends TestCase
{
    public function test_new_users_can_register(): void
    {
        Event::fake();

        $response = $this->postJson('/register', [
            'name' => 'Test',
            'surname' => 'User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'date_of_birth' => '2000-01-01',
            'is_manager' => true,
        ]);

        $response->assertStatus(201);
        $response->assertJsonStructure(['token', 'user' => ['id', 'name', 'surname', 'email', 'date_of_birth']]);

        $user = User::where('email', 'test@example.com')->first();
        $this->assertNotNull($user);
        $this->assertTrue(Hash::check('password', $user->password));
        $this->assertDatabaseHas('companies', ['email' => 'test@example.com']);
        $this->assertDatabaseHas('company_employees', ['user_id' => $user->id, 'role_id' =>
            \App\Enums\Role::MANAGER->value]);

        Event::assertDispatched(Registered::class);
    }

    public function test_registration_requires_all_fields(): void
    {
        $response = $this->postJson('/register', []);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name', 'surname', 'email', 'password', 'date_of_birth', 'is_manager']);
    }

    public function test_registration_requires_valid_email(): void
    {
        $response = $this->postJson('/register', [
            'name' => 'Test',
            'surname' => 'User',
            'email' => 'invalid-email',
            'password' => 'password',
            'password_confirmation' => 'password',
            'date_of_birth' => '2000-01-01',
            'is_manager' => true,
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email']);
    }

    public function test_registration_requires_password_confirmation(): void
    {
        $response = $this->postJson('/register', [
            'name' => 'Test',
            'surname' => 'User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'different-password',
            'date_of_birth' => '2000-01-01',
            'is_manager' => true,
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['password']);
    }

    public function test_registration_requires_date_of_birth(): void
    {
        $response = $this->postJson('/register', [
            'name' => 'Test',
            'surname' => 'User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'is_manager' => true,
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['date_of_birth']);
    }

    public function test_registration_requires_is_manager(): void
    {
        $response = $this->postJson('/register', [
            'name' => 'Test',
            'surname' => 'User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'date_of_birth' => '2000-01-01',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['is_manager']);
    }
}
