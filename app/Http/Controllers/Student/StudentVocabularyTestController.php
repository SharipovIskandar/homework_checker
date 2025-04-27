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
        $datas = $this->modelClass::query()
            ->whereHas('testResults', function ($query) {
                $query->where('user_id', auth()->user()->id)
                    ->where('is_accepted', false);
            })
            ->orWhereDoesntHave('testResults') // Agar testResults bo'lmasa
            ->paginate(10);

        return view('students.pages.vocabulary-practise.index', [
            'datas' => $datas,
        ]);
    }



    public function startTest(string $id)
    {
        $model = $this->modelClass::with(['testResults'])
            ->whereHas('testResults', function ($query) {
                $query->where('user_id', auth()->user()->id)
                    ->where('is_accepted', false);
            })
            ->orWhereDoesntHave('testResults')
            ->findOrFail($id);

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


        $totalVocabularies = $model->total_vocabulries;

        $testResult = $model->testResults()->updateOrCreate(
            [
                'vocabulary_id' => $model->id,
                'user_id'=> auth()->user()->id,
                'correct_answers' => request('correct_answers') ?? 0,
                'incorrect_answers' => request('incorrect_answers') ?? null,
                'is_accepted' => request('is_accepted') ?? false
            ]
        );

        return redirect()->route('student.vocabularies.index')
            ->with(['message' => 'Test natijasi saqlandi']);
    }

    public function commingSoon()
    {
        return view('students.pages.vocabulary-practise.comming-soon');
    }
}
