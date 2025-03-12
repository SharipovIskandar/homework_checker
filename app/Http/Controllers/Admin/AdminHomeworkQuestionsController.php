<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Homework;
use App\Models\HomeworkQuestion;
use App\Models\User;
use App\Traits\Crud;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdminHomeworkQuestionsController extends Controller
{
    use Crud;

    protected string $modelClass = HomeworkQuestion::class;

    public function index()
    {

        $datas = $this->modelClass::query()->with('homework')->paginate();
        return view('admin.pages.homework-questions.index', [
            'datas' => $datas,
        ]);
    }

    public function create()
    {
        $users = User::all();
        $homeworks = Homework::all();
        $model = new $this->modelClass();

        return view('admin.pages.homework-questions.create', [
            'model' => $model,
            'homeworks' => $homeworks,
            'users' => $users,
        ]);
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'homework_id' => 'required|exists:homeworks,id',
                'questions' => 'required|string',
                'correct_answers' => 'required|string',
            ]);

            $questionsArray = explode("\n", trim($request->questions));
            $answersArray = explode("\n", trim($request->correct_answers));

            $formattedQuestions = [];
            $formattedAnswers = [];

            foreach ($questionsArray as $index => $question) {
                $taskKey = "Task " . ($index + 1) . ":";
                $formattedQuestions[$taskKey] = trim($question);
            }

            foreach ($answersArray as $index => $answer) {
                $taskKey = "Task " . ($index + 1) . ":";
                $formattedAnswers[$taskKey] = trim($answer);
            }

            HomeworkQuestion::create([
                'homework_id' => $request->homework_id,
                'questions' => $formattedQuestions,
                'correct_answers' => $formattedAnswers,
            ]);

            return redirect()->back()->with('success', 'Homework questions saved successfully!');
        } catch (\Exception $e) {
            Log::error('HomeworkQuestion saqlashda xatolik: ' . $e->getMessage(), [
                'request_data' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->with('error', 'Homework questions saqlashda xatolik yuz berdi!');
        }
    }

    public function edit(string $id)
    {

        $model = $this->modelClass::findOrFail($id);
        $users = User::all();
        return view('admin.pages.project-overview.edit', [
            'model' => $model,
            'users' => $users,
            'languages' => allLanguage(),
        ]);
    }

    public function update( $request, $id)
    {
        $this->customUpdate($id, $request);

        return redirect()->route('admin.homework.index')
            ->with(['message' => 'Успешно обновлено']);
    }

    public function destroy($id)
    {
        $model = $this->modelClass::findOrFail($id);
        $this->customDelete($id);
        return response()->json(['success' => true, 'tr' => 'tr_' . $id]);
    }
}
