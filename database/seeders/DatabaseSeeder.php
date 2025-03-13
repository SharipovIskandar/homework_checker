<?php

namespace Database\Seeders;

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

        $this->call(HomeworkTypeSeeder::class);
        $this->call(RoleSeeder::class);
        \App\Models\User::factory()->create([
            'username' => 'iskandar',
            'email' => 'iskandar@gmail.com',
            'password' => Hash::make(1234),
            'role_id' => 1
        ]);
        $this->call(SubjectSeeder::class);
    }
}
