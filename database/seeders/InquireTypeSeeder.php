<?php

namespace Database\Seeders;

use App\Enums\InquireType;
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
                'friendly_name' => 'Vacation',
            ],
            [
                'id' => InquireType::DAY_OFF->value,
                'name' => InquireType::DAY_OFF->name,
                'friendly_name' => 'Day Off',
            ],
            [
                'id' => InquireType::REMOTE->value,
                'name' => InquireType::REMOTE->name,
                'friendly_name' => 'Remote',
            ],
            [
                'id' => InquireType::MEDICAL->value,
                'name' => InquireType::MEDICAL->name,
                'friendly_name' => 'Medical',
            ],
            [
                'id' => InquireType::UNPAID->value,
                'name' => InquireType::UNPAID->name,
                'friendly_name' => 'Unpaid',
            ],
        ]);
    }
}
