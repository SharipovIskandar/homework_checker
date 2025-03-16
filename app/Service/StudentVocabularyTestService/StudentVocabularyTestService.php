<?php

namespace App\Service\StudentVocabularyTestService;

use App\Models\Vocabulary;
use App\Models\VocabularyTestResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentVocabularyTestService
{
    public function storeTestResult(Request $request, $model)
    {
        $correctAnswers = $request->input('correct_answers');
        $incorrectAnswers = $request->input('incorrect_answers');

        $testResult = $model->testResults()->create([
            'correct_answers' => $correctAnswers,
            'incorrect_answers' => $incorrectAnswers,
            'user_id' => auth()->id()
        ]);

        return $testResult;
    }

}
