<?php

namespace Tests\Unit;

use App\Enums\InquireType;
use App\Enums\Role;
use App\Enums\Status;
use App\Models\Company;
use App\Models\Inquire;
use App\Models\User;
use App\Notifications\NotifyInquireStatus;
use Database\Seeders\InquireTypeSeeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\StatusesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\Uid\Ulid;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class InquireTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
        $this->seed(InquireTypeSeeder::class);
        $this->seed(StatusesSeeder::class);
    }

    public function test_employee_can_create_inquire(): void
    {
        $employee = User::factory()->create();
        $company = Company::factory()->create();
        $employee->company()->attach($company->id, ['role_id' => Role::EMPLOYEE->value]);
        $token = JWTAuth::fromUser($employee);
        $this->withHeader('Authorization', 'Bearer ' . $token);

        $data = [
            'type' => InquireType::VACATION->value,
            'start' => '2024-05-30 08:00:00',
            'end' => '2024-05-30 17:00:00',
        ];

        $response = $this->postJson('/api/inquire', $data);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'inquire_id',
                    'user_id',
                    'status_id',
                    'type',
                    'start',
                    'end',
                ]
            ]);

        $this->assertDatabaseHas('inquires', [
            'user_id' => $employee->id,
            'status_id' => Status::PENDING->value,
            'type' => $data['type'],
            'start' => $data['start'],
            'end' => $data['end'],
        ]);
    }

    public function test_employee_cannot_update_inquire_status(): void
    {
        $employee = User::factory()->create();
        $employee->company()->attach(Company::factory()->create()->id, ['role_id' => Role::EMPLOYEE->value]);
        $token = JWTAuth::fromUser($employee);
        $this->withHeader('Authorization', 'Bearer ' . $token);

        $userId = (new Ulid)->toBase32();
        $inquire = Inquire::create([
            'inquire_id' => (new Ulid)->toBase32(),
            'user_id' => $userId,
            'status_id' => \App\Enums\Status::PENDING->value,
            'type' => InquireType::VACATION->value,
            'start' => '2024-05-31 09:00:00',
            'end' => '2024-05-31 18:00:00',
        ]);

        $data = [
            'status_id' => Status::APPROVED->value,
        ];

        $response = $this->json('PATCH', route('inquires.update', $inquire->id), $data);

        $response->assertStatus(403);
    }

    public function test_manager_can_approve_inquire(): void
    {
        $manager = User::factory()->create();
        $manager->company()->attach(Company::factory()->create()->id, ['role_id' => Role::MANAGER->value]);
        $token = JWTAuth::fromUser($manager);
        $this->withHeader('Authorization', 'Bearer ' . $token);

        $userId = (new Ulid)->toBase32();
        $inquire = Inquire::create([
            'inquire_id' => (new Ulid)->toBase32(),
            'user_id' => $userId,
            'status_id' => \App\Enums\Status::PENDING->value,
            'type' => InquireType::VACATION->value,
            'start' => '2024-05-31 09:00:00',
            'end' => '2024-05-31 18:00:00',
        ]);

        $data = [
            'status_id' => Status::APPROVED->value,
        ];

        $response = $this->json('PATCH', route('inquires.update', $inquire->id), $data);

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $inquire->id,
                ],
            ]);

        $inquire->refresh();

        $this->assertEquals($data['status_id'], $inquire->status_id);
        $this->assertDatabaseHas('user_notifications', [
            'notifiable_id' => $userId,
            'type' => NotifyInquireStatus::class,
        ]);
    }

}
