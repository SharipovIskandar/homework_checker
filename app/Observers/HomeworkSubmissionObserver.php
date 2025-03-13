<?php

namespace App\Observers;

use App\Models\HomeworkQuestion;
use App\Models\HomeworkSubmission;
use App\Models\StudentHomeworkResult;
use Illuminate\Support\Facades\Log;

class HomeworkSubmissionObserver
{
    /**
     * Handle the HomeworkSubmission "created" event.
     */
    public function created(HomeworkSubmission $homeworkSubmission): void
    {
        //
    }

    /**
     * Handle the HomeworkSubmission "updated" event.
     */
    public function updated(HomeworkSubmission $submission)
    {
        if ($submission->is_accepted) {
            $answers = is_array($submission->answers) ? $submission->answers : json_decode($submission->answers, true);

            if (!is_array($answers)) {
                throw new \Exception("Answers JSON formatida emas yoki noto‘g‘ri.");
            }

            $homeworkQuestion = HomeworkQuestion::where('homework_id', $submission->homework_id)->first();

            if (!$homeworkQuestion) {
                throw new \Exception("Homework question topilmadi.");
            }

            $correctAnswers = is_array($homeworkQuestion->correct_answers)
                ? $homeworkQuestion->correct_answers
                : json_decode($homeworkQuestion->correct_answers, true);

            if (!is_array($correctAnswers)) {
                throw new \Exception("Homework question correct_answers JSON formatida noto‘g‘ri.");
            }

            $totalQuestions = count($correctAnswers);
            $correctCount = 0;
            $incorrectAnswers = [];

            foreach ($answers as $key => $userAnswer) {
                if (!isset($correctAnswers[$key])) {
                    continue;
                }

                if ($this->compareAnswers($userAnswer, $correctAnswers[$key])) {
                    $correctCount++;
                } else {
                    $incorrectAnswers[] = [
                        'question' => $key,
                        'user_answer' => $userAnswer,
                        'correct_answer' => $correctAnswers[$key] ?? ''
                    ];
                }
            }

            $score = ($correctCount / max($totalQuestions, 1)) * 100;

            StudentHomeworkResult::create([
                'student_id' => $submission->student_id,
                'homework_id' => $submission->homework_id,
                'total_questions' => $totalQuestions,
                'correct_answers' => $correctCount,
                'score' => round($score),
                'incorrect_answers' => $incorrectAnswers
            ]);
        }
    }

    private function compareAnswers($userAnswer, $correctAnswer)
    {
        try {
            $userAnswer = $this->normalizeText($userAnswer);
            $correctAnswer = $this->normalizeText($correctAnswer);

            if (empty($correctAnswer)) {
                Log::warning("Bo'sh correctAnswer taqqoslanmoqda.");
                return false;
            }

            $correctVariants = explode(" or ", $correctAnswer);

            foreach ($correctVariants as $variant) {
                if ($this->isSimilar($userAnswer, trim($variant))) {
                    return true;
                }
            }

            return false;
        } catch (\Exception $e) {
            Log::error("compareAnswers metodida xatolik: " . $e->getMessage());
            return false;
        }
    }

    /**
     * ✅ Bu funksiya javoblarni normalizatsiya qiladi:
     * - "is not" -> "isn't"
     * - "are not" -> "aren't"
     * - "do not" -> "don't"
     */
    private function normalizeText($text)
    {
        try {
            $replacements = [
                "is not" => "isn't",
                "are not" => "aren't",
                "do not" => "don't",
                "does not" => "doesn't",
                "cannot" => "can't",
                "will not" => "won't",
                "would not" => "wouldn't",
                "should not" => "shouldn't",
                "he will" => "he'll",
                "they will" => "they'll",
                "I am" => "I'm",
                "you are" => "you're",
                "they are" => "they're"
            ];

            $text = strtolower(trim($text));
            $text = str_replace(array_keys($replacements), array_values($replacements), $text);
            $text = preg_replace('/[^a-z0-9\s\']/i', '', $text);
            $text = preg_replace('/\s+/', ' ', $text);

            return $text;
        } catch (\Exception $e) {
            Log::error("normalizeText metodida xatolik: " . $e->getMessage());
            return '';
        }
    }

    private function isSimilar($userAnswer, $correctAnswer)
    {
        try {
            if ($userAnswer === $correctAnswer) {
                return true;
            }

            $distance = levenshtein($userAnswer, $correctAnswer);
            $maxLen = max(strlen($userAnswer), strlen($correctAnswer));

            if ($maxLen === 0) {
                Log::warning("Bo'sh satrlarni taqqoslash urinishlari.");
                return false;
            }

            $similarity = (1 - ($distance / $maxLen)) * 100;

                return $similarity >= 85;
        } catch (\Exception $e) {
            Log::error("isSimilar metodida xatolik: " . $e->getMessage());
            return false;
        }
    }


    /**
     * Handle the HomeworkSubmission "deleted" event.
     */
    public function deleted(HomeworkSubmission $homeworkSubmission): void
    {
        //
    }

    /**
     * Handle the HomeworkSubmission "restored" event.
     */
    public function restored(HomeworkSubmission $homeworkSubmission): void
    {
        //
    }

    /**
     * Handle the HomeworkSubmission "force deleted" event.
     */
    public function forceDeleted(HomeworkSubmission $homeworkSubmission): void
    {
        //
    }
}
