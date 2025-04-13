<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\StudentHomeworkResult;
use App\Models\User;
use Illuminate\Http\Request;

class StudentHomeworkResultsController extends Controller
{
    protected string $modelClass = StudentHomeworkResult::class;

    public function index()
    {

        $datas = $this->modelClass::query()
            ->with('homework')
            ->where('student_id' , auth()->user()->id)
            ->when(request('due_date') === 'future', function ($query) {
                $query->whereHas('homework', function ($homeworkQuery) {
                    $homeworkQuery->where('due_date', '>', now());
                });
            })
            ->whereHasEqual('homework', 'exercise_id')
            ->orderByDesc('id')
            ->paginate();
        return view('students.pages.homework-results.index', [
            'datas' => $datas,
        ]);
    }

}
