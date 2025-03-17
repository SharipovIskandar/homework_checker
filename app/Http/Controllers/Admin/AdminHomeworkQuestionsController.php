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

    public function update($request, $id)
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

        $lines = explode("\n", $text);
        $formattedText = [];
        $counter = 1;

        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line)) continue;

            if (preg_match('/^\d+\./', $line) || preg_match('/^\d+\)/', $line) || preg_match('/^\d+\-/', $line)) {
                $formattedText[] = ucfirst($line);
                continue;
            }

            if (preg_match('/^[A-D][\)\.\-]/', $line)) {
                $formattedText[] = $line;
                continue;
            }

            if (preg_match('/\d+\.$/', $line)) {
                $line = preg_replace('/\d+\.$/', '', $line);
            }

            $line = "{$counter}. " . ucfirst($line);
            $counter++;

            // Oxirida nuqta yo‘q bo‘lsa, qo‘shamiz
            if (!preg_match('/[.?]$/', $line)) {
                $line .= '.';
            }

            $formattedText[] = $line;
        }

        return response()->json([
            'success' => true,
            'text' => implode("\n", $formattedText)
        ]);
    }


    public function generateCorrectAnswers(Request $request)
    {
        $request->validate([
            'homework_id' => 'required',
            'questions' => 'required',
        ]);

        $homework = Homework::find($request->homework_id);

        if (!$homework) {
            return response()->json(['error' => 'Homework not found.'], 404);
        }

        $taskCondition = $homework->task_condition;

        $apiKey = env('GEMINI_API_KEY');
        $url = "https://generativelanguage.googleapis.com/v1/models/gemini-1.5-pro:generateContent?key=$apiKey";

        // AI ga toza va aniq so‘rov berish
        $prompt = "Answer the following question strictly based on the given homework condition without any additional explanation.
    \nHomework condition: \"$taskCondition\"
    \nQuestion: \"{$request->questions}\"
    \nProvide only the correct answer.";

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

        return response()->json(['correct_answers' => trim($answer)]);
    }

}
