<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Vocabulary;
use App\Service\StudentVocabularyTestService\StudentVocabularyTestService;
use Illuminate\Http\Request;

class StudentVocabularyTestController extends Controller
{
    protected string $modelClass = Vocabulary::class;
    protected StudentVocabularyTestService $service;

    public function __construct(StudentVocabularyTestService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $datas = $this->modelClass::query()->paginate(10);
        return view('students.pages.vocabulary-practise.index', [
            'datas' => $datas,
        ]);
    }

    public function startTest(string $id)
    {
        $model = $this->modelClass::findOrFail($id);

        if (is_string($model->word)) {
            $decodedWord = json_decode($model->word, true);
            $model->word = is_array($decodedWord) ? $decodedWord : [$model->word];
        }

        return view('students.pages.vocabulary-practise.create', [
            'model' => $model,
        ]);
    }

    public function storeTestResult(Request $request, string $id)
    {
        $model = $this->modelClass::findOrFail($id);

        $correctAnswers = $request->input('correct_answers');
        $incorrectAnswers = $request->input('incorrect_answers');

        $totalVocabularies = $model->total_vocabulries;

        $testResult = $model->testResults()->updateOrCreate(
            ['vocabulary_id' => $model->id, 'user_id' => auth()->id()],
            [
                'correct_answers' => $correctAnswers,
                'incorrect_answers' => $incorrectAnswers,
                'is_accepted' => $correctAnswers == $totalVocabularies ? true : false,
            ]
        );

        return redirect()->route('student.vocabularies.index')
            ->with(['message' => 'Test natijasi saqlandi']);
    }



}
