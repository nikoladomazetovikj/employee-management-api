<?php

namespace Tests\Unit;

use App\Enums\Role;
use App\Models\Company;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class CompaniesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
        $this->user = User::factory()->create();
        $token = JWTAuth::attempt(['email' => $this->user->email, 'password' => 'password']);
        $this->withHeader('Authorization', 'Bearer ' . $token);
    }

    public function test_can_list_companies(): void
    {
        $company = Company::factory()->create();
        $this->user->company()->attach($company->id, ['role_id' => Role::MANAGER->value]);

        $response = $this->getJson('/api/company');

        $response->assertStatus(200)
            ->assertJsonStructure(['data' => ['id', 'name', 'email']])
            ->assertJson(['data' => ['id' => $company->id]]);
    }

    public function test_can_create_company(): void
    {
        $data = [
            'name' => 'Test Company',
            'email' => 'company@example.com',
            'address' => [
                'street' => '123 Main St',
                'city' => 'Test City',
                'state' => 'TS',
                'zip' => '12345',
                'country' => 'Test Country',
            ],
            'phone' => ['number' => '123-456-7890'],
        ];


        $response = $this->postJson('/api/company', $data);


        $response->assertStatus(201)
            ->assertJsonStructure(['data' => ['id', 'name', 'email']])
            ->assertJson(['data' => ['name' => 'Test Company', 'email' => 'company@example.com']]);


        $this->assertDatabaseHas('companies', ['email' => 'company@example.com']);


        $this->assertDatabaseHas('addresses', [
            'street' => '123 Main St',
            'city' => 'Test City',
            'state' => 'TS',
            'zip' => '12345',
            'country' => 'Test Country',
        ]);

        $this->assertDatabaseHas('phones', ['number' => '123-456-7890']);
    }

    public function test_can_show_company(): void
    {
        $company = Company::factory()
            ->create();

        $response = $this->getJson("/api/company/{$company->id}");

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $company->id,
                    'name' => $company->name,
                    'email' => $company->email,
                ],
            ]);
    }

    public function test_can_update_company(): void
    {
        $company = Company::factory()->create();
        $this->user->company()->attach($company->id, ['role_id' => Role::MANAGER->value]);

        $data = [
            'name' => 'Updated Company',
            'address' => ['street' => '456 New St', 'city' => 'New City', 'state' => 'NS', 'zip' => '67890'],
            'phone' => ['number' => '987-654-3210'],
        ];

        $response = $this->putJson("/api/company/{$company->id}", $data);

        $response->assertStatus(200)
            ->assertJsonStructure(['data' => ['id', 'name', 'email']])
            ->assertJson(['data' => ['name' => 'Updated Company']]);

        $this->assertDatabaseHas('companies', ['name' => 'Updated Company']);
        $this->assertDatabaseHas('addresses', ['street' => '456 New St']);
        $this->assertDatabaseHas('phones', ['number' => '987-654-3210']);
    }

    public function test_can_delete_company(): void
    {
        $company = Company::factory()->create();
        $this->user->company()->attach($company->id, ['role_id' => Role::MANAGER->value]);

        $response = $this->deleteJson("/api/company/{$company->id}");

        $response->assertStatus(204);
    }
}
