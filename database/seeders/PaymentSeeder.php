<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Payment;

class PaymentSeeder extends Seeder
{
    public function run(): void
    {
        Payment::insert([
            [
                'student_id' => 1,
                'amount' => 200000,
                'date' => '2025-07-01',
                'note' => 'Iyul oyi uchun to‘lov',
                'type' => 'debt',
                'debt_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'student_id' => 2,
                'amount' => 150000,
                'date' => '2025-07-02',
                'note' => 'Qisman to‘lov',
                'type' => 'debt',
                'debt_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'student_id' => 3,
                'amount' => 100000,
                'date' => '2025-07-03',
                'note' => 'Avvalgi balans',
                'type' => 'balance',
                'debt_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'student_id' => 2,
                'amount' => 50000,
                'date' => '2025-07-04',
                'note' => 'Qo‘shimcha to‘lov',
                'type' => 'balance',
                'debt_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'student_id' => 3,
                'amount' => 250000,
                'date' => '2025-07-05',
                'note' => 'Iyul va avgust uchun to‘lov',
                'type' => 'debt',
                'debt_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
