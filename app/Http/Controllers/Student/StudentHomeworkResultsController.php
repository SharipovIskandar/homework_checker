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
            ->orderBy('id', 'desc')
            ->paginate();
        return view('students.pages.homework-results.index', [
            'datas' => $datas,
        ]);
    }

}
