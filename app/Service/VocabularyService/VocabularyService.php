<?php

namespace App\Service\VocabularyService;

use App\Models\Vocabulary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VocabularyService
{
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $data = $request->validate([
                'word' => 'required|array',
                'word.*' => 'string',
                'level' => 'required|string',
                'due_date' => 'nullable|date',
            ]);

            $data['word'] = $data['word'];

            $vocabulary = Vocabulary::create($data);

            DB::commit();
            return $vocabulary;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }


    public function update(Request $request, Vocabulary $vocabulary)
    {
        DB::beginTransaction();

        try {
            $data = $request->validate([
                'word' => 'sometimes|array',
                'word.*' => 'string',
                'level' => 'sometimes|string',
                'due_date' => 'nullable|date',
            ]);

            if (!empty($data['word'])) {
                if (!is_string($data['word'])) {
                    $data['word'] = $data['word'];
                }
            } else {
                $data['word'] = json_encode([]);
            }

            $vocabulary->update($data);

            DB::commit();
            return $vocabulary;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

}
