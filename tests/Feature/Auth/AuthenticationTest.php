<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_users_can_authenticate_using_the_login_api(): void
    {
        $user = User::factory()->create(['password' => Hash::make('password')]);

        $response = $this->postJson('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);


        $response->assertStatus(200);
        $response->assertJsonStructure(['token', 'user', 'role']);
        $this->assertAuthenticatedAs($user);
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create(['password' => bcrypt('password')]);

        $response = $this->postJson('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(401);
        $response->assertJson(['message' => 'Invalid credentials']);
        $this->assertGuest();
    }
}
