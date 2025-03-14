<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Homework;
use App\Models\HomeworkQuestion;
use App\Models\User;
use App\Traits\Crud;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use thiagoalessio\TesseractOCR\TesseractOCR;

class AdminHomeworkQuestionsController extends Controller
{
    use Crud;

    protected string $modelClass = HomeworkQuestion::class;

    public function index()
    {

        $datas = $this->modelClass::query()->with('homework')->orderByDesc('id')->paginate();
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

    public function processImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $image = $request->file('image');

        $tempPath = $image->getRealPath();

        $ocr = new TesseractOCR($tempPath);
        $text = $ocr->run();

        return response()->json([
            'success' => true,
            'text' => $text
        ]);
    }

    public function generateCorrectAnswers(Request $request)
    {
        Log::info('Generate Correct Answers called with data:', [
            'request_data' => $request->all(),
            'headers' => $request->headers->all(),
        ]);
        Log::info('User auth check: ', ['authenticated' => auth()->check()]);
        Log::info('Middlewaredan keyin: ', ['user' => auth()->user()]);
        Log::info('Session data:', session()->all());

        $request->validate([
            'homework_id' => 'required',
            'questions' => 'required',
        ]);

        $apiKey = env('GEMINI_API_KEY');
        $url = "https://generativelanguage.googleapis.com/v1/models/gemini-1.5-pro:generateContent?key=$apiKey";

        $prompt = "Given the following homework condition: \"{$request->homework_id}\" and the question: \"{$request->questions}\", generate the correct answers.";

        $response = Http::withOptions([
            'Content-Type' => 'application/json',
            'verify' => false,
        ])->post($url, [
            "contents" => [
                ["parts" => [["text" => $prompt]]]
            ]
        ]);

        $data = $response->json();
        $answer = $data['candidates'][0]['content']['parts'][0]['text'] ?? 'No answer generated.';

        return response()->json(['correct_answers' => $answer]);
    }
}
