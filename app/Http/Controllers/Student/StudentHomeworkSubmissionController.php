<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Homework;
use App\Models\HomeworkQuestion;
use App\Models\HomeworkSubmission;
use App\Models\User;
use App\Traits\Crud;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            ->whereDoesntHave('homework.homeworkSubmission')
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
        $request->merge(['student_id' => Auth::id()]);

        $homeworkAnswers = $request->input('answers', []);
        $homeworkId = $request->input('homework_id');

        if (empty($homeworkAnswers)) {
            return redirect()->route('student.homework.submissions.index')->with('error', 'Javoblar kiritilmadi.');
        }

        $homeworkQuestions = HomeworkQuestion::whereIn('homework_id', array_keys($homeworkAnswers))->get()->groupBy('homework_id');

        foreach ($homeworkAnswers as $homeworkId => $answers) {
            if (empty($answers) || !isset($homeworkQuestions[$homeworkId])) {
                continue;
            }

            $submission = HomeworkSubmission::create([
                'student_id' => Auth::id(),
                'homework_id' => $homeworkId,
                'answers' => $answers
            ]);


            $this->updateSubmission($submission, $homeworkQuestions[$homeworkId]);
        }

        return redirect()->route('student.homework.submissions.index')->with('success', 'Javoblar muvaffaqiyatli saqlandi.');
    }


    public function edit(string $id)
    {
        $model = $this->modelClass::findOrFail($id);
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
            'model' => $model,
            'questions' => $questions,
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

    public function updateIsAccepted($id)
    {
        $model = $this->modelClass::findOrFail($id);
        $model->is_accepted = true;
        $model->status = 'accepted';
        $model->save();

        return redirect()->back()->with('success', 'Updated successfully');

    }

    protected function updateSubmission(HomeworkSubmission $submission, $homeworkQuestions)
    {
        $existingAnswers = is_array($submission->answers) ? $submission->answers : json_decode($submission->answers, true) ?? [];

        $newAnswers = request()?->input('answers', []);

        foreach ($homeworkQuestions as $question) {
            $taskName = $question->task_name;

            if (isset($newAnswers[$taskName])) {
                $existingAnswers[$taskName] = $newAnswers[$taskName];
            }
        }

        $submission->update([
            'answers' => $existingAnswers
        ]);
    }


}
