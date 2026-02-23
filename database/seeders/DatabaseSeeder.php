<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::create([
            'name' => 'Aziz Fatkhu Rohman',
            'email' => 'azizfrachman@gmail.com',
            'password' => Hash::make('Karawang050101'),
            'username' => '200525-001',
            'employee_id' => '200525-001'
        ]);
    }
}
