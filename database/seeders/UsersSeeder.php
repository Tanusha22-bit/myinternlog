<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UsersSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'admin'
        ]);

        User::create([
            'name' => 'Student One',
            'email' => 'student1@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'student'
        ]);

        User::create([
            'name' => 'Industry SV',
            'email' => 'industry@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'industry_sv'
        ]);

        User::create([
            'name' => 'Uni Supervisor',
            'email' => 'uni@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'university_sv'
        ]);
    }
}
