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
            ['name' => 'Complete the sentences', 'key' => 'complete_sentences'],
            ['name' => 'Write the sentences about yourself', 'key' => 'write_sentences_about_yourself'],
            ['name' => 'Write the sentences for pictures', 'key' => 'write_sentences_for_pictures'],
            ['name' => 'Write the true sentences, positive or negative', 'key' => 'write_the_true_sentences_positive'],
            ['name' => 'Make questions with these words', 'key' => 'make_questions_with_these_words'],
        ];

        DB::table('homework_types')->insert($types);
    }
}
