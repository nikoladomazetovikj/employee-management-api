<?php

namespace Database\Seeders;

use App\Enums\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('roles')->insert([
            [
                'id' => Role::MANAGER->value,
                'name' => Role::MANAGER->name,
            ],
            [
                'id' => Role::EMPLOYEE->value,
                'name' => Role::EMPLOYEE->name,
            ],
        ]);
    }
}
