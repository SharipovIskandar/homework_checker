<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use thiagoalessio\TesseractOCR\TesseractOCR;
use Illuminate\Support\Facades\Http;

class ImageController extends Controller
{
    public function processImage(Request $request)
    {
        try {
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $image = $request->file('image');
            $words = $this->extractTextFromImage($image);
            $translatedWords = $this->translateWords($words);

            // Matnni formatlash
            $formattedText = $this->formatText($translatedWords);
            $totalVocabularies = count($words);

            return response()->json([
                'success' => true,
                'text' => $formattedText,
                'total_vocabularies' => $totalVocabularies,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Rasmni qayta ishlashda xatolik yuz berdi: ' . $e->getMessage()
            ], 500);
        }
    }

    private function extractTextFromImage($image)
    {
        $ocr = new TesseractOCR($image->getRealPath());
        $ocr->lang('eng')           
            ->psm(3)                
            ->oem(3)                
            ->config('tessedit_char_whitelist', 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789.,!?\'"()-_ '); 

        $text = $ocr->run();

        // Matnni tozalash
        $text = preg_replace('/[\/\\\[\]{}<>|]+/', ' ', $text); 
        $text = preg_replace('/\s+/', ' ', $text);  
        $text = trim($text); 

        // Matnni qatorlarga ajratish
        $lines = explode("\n", $text);
        $result = [];
        
        foreach ($lines as $line) {
            $line = trim($line);
            if (!empty($line)) {
                $result[] = $line;
            }
        }

        return implode("\n", $result);
    }

    private function translateWords($words)
{
    $apiKey = env('GEMINI_API_KEY');
    $url = "https://generativelanguage.googleapis.com/v1/models/gemini-1.5-pro:generateContent?key=$apiKey";

    $wordArray = explode(' ', $words); // BU YERDA TUZATILDI
    $prompt = "Translate the following words from English to Uzbek in the format 'word => translation': \n" . implode(", ", $wordArray) . "\n\nPlease also include the total number of only the english words at the end.";

    $response = Http::withOptions([
        'Content-Type' => 'application/json',
        'verify' => false,
    ])->post($url, [
        "contents" => [
            ["parts" => [["text" => $prompt]]]
        ]
    ]);

    $data = json_decode($response->getBody(), true);
    $text = $data['candidates'][0]['content']['parts'][0]['text'];

    // Clean up the translation
    $text = preg_replace('/^\* /m', '', $text);

    return $text;
}


    private function formatText($words)
    {
        $formattedText = '';
        $currentLine = '';
        $maxLineLength = 50; 

        foreach ($words as $word) {
            if (strlen($currentLine . ' ' . $word) <= $maxLineLength) {
                $currentLine .= ($currentLine ? ' ' : '') . $word;
            } else {
                $formattedText .= ($formattedText ? "\n" : '') . $currentLine;
                $currentLine = $word;
            }
        }

        if ($currentLine) {
            $formattedText .= ($formattedText ? "\n" : '') . $currentLine;
        }

        return $formattedText;
    }
}
