<?php

namespace Database\Seeders;

use App\Models\Teacher;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Teacher::insert([
            ['name' => 'Alisher Xojayev', 'phone' => '998901112233', 'password' => bcrypt('password')],
            ['name' => 'Dildora Karimova', 'phone' => '998902223344', 'password' => bcrypt('password')],
            ['name' => 'Mirjalol Usmonov', 'phone' => '998903334455', 'password' => bcrypt('password')],
        ]);
    }
}
