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
        \App\Models\User::factory()->create([
            'username' => 'sakina',
            'email' => 'sakina@gmail.com',
            'password' => Hash::make(1234),
            'role_id' => 1
        ]);
        \App\Models\User::factory()->create([
            'username' => 'abdulqayum',
            'email' => 'abdulqayum@gmail.com',
            'password' => Hash::make(1234),
            'role_id' => 3
        ]);
        \App\Models\User::factory()->create([
            'username' => 'abdulrohman',
            'email' => 'abdulrohman@gmail.com',
            'password' => Hash::make(1234),
            'role_id' => 3
        ]);
        \App\Models\User::factory()->create([
            'username' => 'alijon',
            'email' => 'alijon@gmail.com',
            'password' => Hash::make(1234),
            'role_id' => 3
        ]);
        \App\Models\User::factory()->create([
            'username' => 'mavjuda',
            'email' => 'mavjuda@gmail.com',
            'password' => Hash::make(1234),
            'role_id' => 3
        ]);
        \App\Models\User::factory()->create([
            'username' => 'sevinch',
            'email' => 'sevinch@gmail.com',
            'password' => Hash::make(1234),
            'role_id' => 3
        ]);
        \App\Models\User::factory()->create([
            'username' => 'vazira',
            'email' => 'vazira@gmail.com',
            'password' => Hash::make(1234),
            'role_id' => 3
        ]);
        \App\Models\User::factory()->create([
            'username' => 'umidaxon',
            'email' => 'umidaxon@gmail.com',
            'password' => Hash::make(1234),
            'role_id' => 3
        ]);
        $this->call(SubjectSeeder::class);
    }
}
