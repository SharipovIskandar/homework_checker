<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HomeworkTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $types = [
            ['name' => 'Essay', 'key' => 'essay'],
            ['name' => 'Multiple Choice', 'key' => 'multiple_choice'],
            ['name' => 'Short Answer', 'key' => 'short_answer'],
            ['name' => 'Re-write the sentences', 'key' => 'rewrite_sentences'],
            ['name' => 'True or False', 'key' => 'true_false'],
        ];

        DB::table('homework_types')->insert($types);
    }
}
