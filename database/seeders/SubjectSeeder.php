<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('subjects')->insert([
            ['name' => 'Mathematics', 'teacher_id' => 1],
            ['name' => 'Physics', 'teacher_id' => 2],
            ['name' => 'Chemistry', 'teacher_id' => 3]
        ]);
    }
}
