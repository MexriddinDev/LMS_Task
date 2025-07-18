<?php

namespace Database\Seeders;

use App\Models\Debt;
use Illuminate\Database\Seeder;

class DebtSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Debt::insert([
            [
                'student_id' => 1,
                'group_id' => 1,
                'amount' => 300000,
                'month' => '2025-07',
                'paid_amount' => 0,
                'is_paid' => false,
                'status' => 'unpaid',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'student_id' => 2,
                'group_id' => 1,
                'amount' => 300000,
                'month' => '2025-08',
                'paid_amount' => 150000,
                'is_paid' => false,
                'status' => 'partial',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'student_id' => 3,
                'group_id' => 2,
                'amount' => 350000,
                'month' => '2025-09',
                'paid_amount' => 350000,
                'is_paid' => true,
                'status' => 'paid',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
