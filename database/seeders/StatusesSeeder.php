<?php

namespace Database\Seeders;

use App\Enums\Status;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('statuses')->insert([
            [
                'id' => Status::PENDING->value,
                'name' => Status::PENDING->name,
            ],
            [
                'id' => Status::DECLINED->value,
                'name' => Status::DECLINED->name,
            ],
            [
                'id' => Status::APPROVED->value,
                'name' => Status::APPROVED->name,
            ],
        ]);
    }
}
