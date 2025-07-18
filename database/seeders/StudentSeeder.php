<?php



namespace Database\Seeders;

use App\Models\Student;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Student::insert([
            [
                'full_name' => 'Ali Karimov',
                'phone' => '+998901112233',
                'birth_date' => '2005-03-15',
                'balance' => 120000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'full_name' => 'Dilnoza Xudoyberdiyeva',
                'phone' => '+998901234567',
                'birth_date' => '2006-07-21',
                'balance' => 50000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'full_name' => 'Javohir Mamatqulov',
                'phone' => '+998909998877',
                'birth_date' => '2004-12-02',
                'balance' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'full_name' => 'Malika Nurmatova',
                'phone' => '+998908877665',
                'birth_date' => '2005-01-30',
                'balance' => 30000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
