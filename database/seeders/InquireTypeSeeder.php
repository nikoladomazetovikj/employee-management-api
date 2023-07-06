<?php

namespace Database\Seeders;

use App\Enums\InquireType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InquireTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('inquire_types')->insert([
            [
                'id' => InquireType::VACATION->value,
                'name' => InquireType::VACATION->name,
            ],
            [
                'id' => InquireType::DAY_OFF->value,
                'name' => InquireType::DAY_OFF->name,
            ],
            [
                'id' => InquireType::REMOTE->value,
                'name' => InquireType::REMOTE->name,
            ],
            [
                'id' => InquireType::MEDICAL->value,
                'name' => InquireType::MEDICAL->name,
            ],
            [
                'id' => InquireType::UNPAID->value,
                'name' => InquireType::UNPAID->name,
            ],
        ]);
    }
}
