<?php

namespace Database\Seeders;

use App\Models\Group;
use Illuminate\Database\Seeder;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Group::insert([
            [
                'name' => 'Frontend 101',
                'teacher_id' => 1,
                'monthly_fee' => 200000,
                'start_date' => '2024-07-01',
                'date' => 'Dushanba, Payshanba',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Backend 202',
                'teacher_id' => 2,
                'monthly_fee' => 250000,
                'start_date' => '2024-07-05',
                'date' => 'Seshanba, Juma',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Python for Beginners',
                'teacher_id' => 3,
                'monthly_fee' => 180000,
                'start_date' => '2024-08-01',
                'date' => 'Chorshanba, Shanba',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
