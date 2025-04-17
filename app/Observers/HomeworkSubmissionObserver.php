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
                throw new \Exception("Answers JSON formatida emas yoki noto'g'ri.");
            }

            $homeworkQuestion = HomeworkQuestion::where('homework_id', $submission->homework_id)->first();

            if (!$homeworkQuestion) {
                throw new \Exception("Homework question topilmadi.");
            }

            $correctAnswers = is_array($homeworkQuestion->correct_answers)
                ? $homeworkQuestion->correct_answers
                : json_decode($homeworkQuestion->correct_answers, true);

            if (!is_array($correctAnswers)) {
                throw new \Exception("Homework question correct_answers JSON formatida noto'g'ri.");
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
            // Bo'sh javoblarni tekshirish
            if (empty($userAnswer) || empty($correctAnswer)) {
                Log::warning("Bo'sh javob taqqoslanmoqda", [
                    'userAnswer' => $userAnswer,
                    'correctAnswer' => $correctAnswer
                ]);
                return false;
            }

            // Javoblarni normalizatsiya qilish
            $userAnswer = $this->normalizeText($userAnswer);
            $correctAnswer = $this->normalizeText($correctAnswer);

            // Variantlarni ajratish
            $correctVariants = array_map('trim', explode(" or ", $correctAnswer));
            
            foreach ($correctVariants as $variant) {
                // Tartib raqamlarini olib tashlash
                $variantWithoutNumbers = $this->removeNumbers($variant);
                
                // Variantni normalizatsiya qilish
                $variant = $this->normalizeText($variant);
                $variantWithoutNumbers = $this->normalizeText($variantWithoutNumbers);

                // Foydalanuvchi javobidan tartib raqamini olib tashlash
                $userAnswerWithoutNumbers = $this->removeNumbers($userAnswer);
                $userAnswerWithoutNumbers = $this->normalizeText($userAnswerWithoutNumbers);

                // To'g'ridan-to'g'ri taqqoslash
                if ($this->isExactMatch($userAnswer, $variant) || 
                    $this->isExactMatch($userAnswer, $variantWithoutNumbers) ||
                    $this->isExactMatch($userAnswerWithoutNumbers, $variant) ||
                    $this->isExactMatch($userAnswerWithoutNumbers, $variantWithoutNumbers)) {
                    return true;
                }

                // Levenshtein masofasi orqali taqqoslash
                if ($this->isSimilar($userAnswer, $variant) || 
                    $this->isSimilar($userAnswer, $variantWithoutNumbers) ||
                    $this->isSimilar($userAnswerWithoutNumbers, $variant) ||
                    $this->isSimilar($userAnswerWithoutNumbers, $variantWithoutNumbers)) {
                    return true;
                }

                // Qisqartmalarni hisobga olish
                if ($this->checkAbbreviations($userAnswer, $variant) || 
                    $this->checkAbbreviations($userAnswer, $variantWithoutNumbers) ||
                    $this->checkAbbreviations($userAnswerWithoutNumbers, $variant) ||
                    $this->checkAbbreviations($userAnswerWithoutNumbers, $variantWithoutNumbers)) {
                    return true;
                }
            }

            return false;
        } catch (\Exception $e) {
            Log::error("compareAnswers metodida xatolik: " . $e->getMessage(), [
                'userAnswer' => $userAnswer ?? null,
                'correctAnswer' => $correctAnswer ?? null,
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }

    private function isExactMatch($str1, $str2)
    {
        return strtolower(trim($str1)) === strtolower(trim($str2));
    }

    private function checkAbbreviations($userAnswer, $correctAnswer)
    {
        $abbreviations = [
            "is not" => ["isn't", "isnt"],
            "are not" => ["aren't", "arent"],
            "do not" => ["don't", "dont"],
            "does not" => ["doesn't", "doesnt"],
            "cannot" => ["can't", "cant"],
            "will not" => ["won't", "wont"],
            "would not" => ["wouldn't", "wouldnt"],
            "should not" => ["shouldn't", "shouldnt"],
            "he will" => ["he'll", "hell"],
            "they will" => ["they'll", "theyll"],
            "I am" => ["I'm", "Im"],
            "you are" => ["you're", "youre"],
            "they are" => ["they're", "theyre"],
            "it is" => ["it's", "its"],
            "that is" => ["that's", "thats"],
            "what is" => ["what's", "whats"],
            "who is" => ["who's", "whos"],
            "where is" => ["where's", "wheres"],
            "when is" => ["when's", "whens"],
            "why is" => ["why's", "whys"],
            "how is" => ["how's", "hows"],
            "there is" => ["there's", "theres"],
            "here is" => ["here's", "heres"],
            "that is" => ["that's", "thats"],
            "this is" => ["this's", "thiss"],
            "what is" => ["what's", "whats"],
            "who is" => ["who's", "whos"],
            "where is" => ["where's", "wheres"],
            "when is" => ["when's", "whens"],
            "why is" => ["why's", "whys"],
            "how is" => ["how's", "hows"]
        ];

        $userAnswer = strtolower(trim($userAnswer));
        $correctAnswer = strtolower(trim($correctAnswer));

        // Qisqartmalarni to'liq shaklga o'tkazish
        foreach ($abbreviations as $full => $short) {
            if (in_array($userAnswer, $short)) {
                $userAnswer = $full;
            }
            if (in_array($correctAnswer, $short)) {
                $correctAnswer = $full;
            }
        }

        // Qisqartmalarni matn ichida ham tekshirish
        foreach ($abbreviations as $full => $short) {
            foreach ($short as $abbr) {
                if (strpos($userAnswer, $abbr) !== false) {
                    $userAnswer = str_replace($abbr, $full, $userAnswer);
                }
                if (strpos($correctAnswer, $abbr) !== false) {
                    $correctAnswer = str_replace($abbr, $full, $correctAnswer);
                }
            }
        }

        return $userAnswer === $correctAnswer;
    }

    private function normalizeText($text)
    {
        try {
            if (empty($text)) {
                return '';
            }

            // Barcha belgilarni tozalash
            $text = preg_replace('/[^\p{L}\p{N}\s\'\.,]/u', '', $text);
            
            // Qo'shimcha bo'shliqlarni olib tashlash
            $text = preg_replace('/\s+/', ' ', $text);
            
            // Katta-kichik harflarni birlashtirish
            $text = strtolower(trim($text));

            // Qisqartmalarni normalizatsiya qilish
            $text = $this->normalizeAbbreviations($text);

            // Nuqta va vergullarni tozalash
            $text = str_replace(['.', ','], '', $text);

            return $text;
        } catch (\Exception $e) {
            Log::error("normalizeText metodida xatolik: " . $e->getMessage(), [
                'text' => $text,
                'trace' => $e->getTraceAsString()
            ]);
            return '';
        }
    }

    private function normalizeAbbreviations($text)
    {
        $abbreviations = [
            "is not" => ["isn't", "isnt"],
            "are not" => ["aren't", "arent"],
            "do not" => ["don't", "dont"],
            "does not" => ["doesn't", "doesnt"],
            "cannot" => ["can't", "cant"],
            "will not" => ["won't", "wont"],
            "would not" => ["wouldn't", "wouldnt"],
            "should not" => ["shouldn't", "shouldnt"],
            "he will" => ["he'll", "hell"],
            "they will" => ["they'll", "theyll"],
            "I am" => ["I'm", "Im"],
            "you are" => ["you're", "youre"],
            "they are" => ["they're", "theyre"],
            "it is" => ["it's", "its"],
            "that is" => ["that's", "thats"],
            "what is" => ["what's", "whats"],
            "who is" => ["who's", "whos"],
            "where is" => ["where's", "wheres"],
            "when is" => ["when's", "whens"],
            "why is" => ["why's", "whys"],
            "how is" => ["how's", "hows"]
        ];

        foreach ($abbreviations as $full => $short) {
            if (in_array($text, $short)) {
                return $full;
            }
        }

        return $text;
    }

    private function isSimilar($userAnswer, $correctAnswer)
    {
        try {
            if (empty($userAnswer) || empty($correctAnswer)) {
                return false;
            }

            // To'g'ridan-to'g'ri taqqoslash
            if ($userAnswer === $correctAnswer) {
                return true;
            }

            // Levenshtein masofasini hisoblash
            $distance = levenshtein($userAnswer, $correctAnswer);
            $maxLen = max(strlen($userAnswer), strlen($correctAnswer));

            if ($maxLen === 0) {
                return false;
            }

            // O'xshashlik foizini hisoblash
            $similarity = (1 - ($distance / $maxLen)) * 100;

            // Qisqa matnlar uchun qattiqroq talab
            if ($maxLen < 5) {
                return $similarity >= 90;
            }

            // O'rta uzunlikdagi matnlar uchun
            if ($maxLen < 10) {
                return $similarity >= 85;
            }

            // Uzun matnlar uchun
            return $similarity >= 80;
        } catch (\Exception $e) {
            Log::error("isSimilar metodida xatolik: " . $e->getMessage(), [
                'userAnswer' => $userAnswer,
                'correctAnswer' => $correctAnswer,
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }

    /**
     * Tartib raqamlarini olib tashlaydigan metod
     */
    private function removeNumbers($text)
    {
        try {
            // Boshidagi raqamlarni olib tashlash (1., 2., 3. kabi)
            $text = preg_replace('/^\d+[\.\)\-]\s*/', '', $text);
            // O'rtadagi raqamlarni olib tashlash
            $text = preg_replace('/\s+\d+[\.\)\-]\s*/', ' ', $text);
            // Qavslar ichidagi raqamlarni olib tashlash
            $text = preg_replace('/\(\d+\)/', '', $text);
            // Qavslar ichidagi harflarni olib tashlash
            $text = preg_replace('/\([a-zA-Z]\)/', '', $text);
            return trim($text);
        } catch (\Exception $e) {
            Log::error("removeNumbers metodida xatolik: " . $e->getMessage(), [
                'text' => $text,
                'trace' => $e->getTraceAsString()
            ]);
            return $text;
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
