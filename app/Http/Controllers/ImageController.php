<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use thiagoalessio\TesseractOCR\TesseractOCR;
use Illuminate\Support\Facades\Http;

class ImageController extends Controller
{
    public function processImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $image = $request->file('image');
        $words = $this->extractTextFromImage($image);
        $translatedWords = $this->translateWords($words);

        return response()->json([
            'success' => true,
            'text' => $translatedWords,
        ]);
    }

    private function extractTextFromImage($image)
    {
        $ocr = new TesseractOCR($image->getRealPath());
        $text = $ocr->run();

        $text = preg_replace('/\([^)]*\)/', '', $text);
        $text = preg_replace('/[\/\\\[\]{}<>|]+/', ' ', $text);
        $words = preg_split('/\s+/', trim($text));

        return array_filter($words);
    }

    private function translateWords($words)
    {
        $apiKey = env('GEMINI_API_KEY');
        $url = "https://generativelanguage.googleapis.com/v1/models/gemini-1.5-pro:generateContent?key=$apiKey";

        $prompt = "Translate the following words from English to Uzbek in the format 'word => translation': \n" . implode(", ", $words);

        $response = Http::withOptions([
            'Content-Type' => 'application/json',
            'verify' => false,
        ])->post($url, [
            "contents" => [
                ["parts" => [["text" => $prompt]]]
            ]
        ]);

        $data = json_decode($response->getBody(), true);
        $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? 'Tarjima topilmadi';

        $text = preg_replace('/^\* /m', '', $text);

        return $text;
    }

}
