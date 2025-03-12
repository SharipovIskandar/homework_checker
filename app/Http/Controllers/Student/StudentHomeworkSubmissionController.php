<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Homework;
use App\Models\HomeworkQuestion;
use App\Models\HomeworkSubmission;
use App\Models\User;
use App\Traits\Crud;
use Illuminate\Http\Request;

class StudentHomeworkSubmissionController extends Controller
{
    use Crud;

    protected string $modelClass = HomeworkSubmission::class;

    public function index()
    {

        $datas = $this->modelClass::query()->paginate();
        return view('students.pages.homework-submissions.index', [
            'datas' => $datas,
        ]);
    }

    public function create()
    {
        $questions = HomeworkQuestion::query()
            ->with(['homework.homeworkTypes'])
            ->whereHas('homework', function ($query) {
                $query->where('due_date', '>', now());
            })
            ->get();

        if ($questions->isEmpty()) {
            abort(404, "Homework topilmadi yoki muddati o'tib ketgan.");
        }

        $users = User::all();

        return view('students.pages.homework-submissions.create', [
            'questions' => $questions,
            'users' => $users,
        ]);
    }



    public function store(Request $request)
    {
        $this->customStore($request);

        return redirect()->route('student.homework.submissions.index');
    }

    public function edit(string $id)
    {

        $model = $this->modelClass::findOrFail($id);
        $users = User::all();
        return view('students.pages.homework-submissions.edit', [
            'model' => $model,
            'users' => $users,
        ]);
    }

    public function update(Request $request, $id)
    {
        $this->customUpdate($id, $request);

        return redirect()->route('student.homework.submissions.index')
            ->with(['message' => 'Успешно обновлено']);
    }

    public function destroy($id)
    {
        $model = $this->modelClass::findOrFail($id);
        $this->customDelete($id);
        return response()->json(['success' => true, 'tr' => 'tr_' . $id]);
    }
}
