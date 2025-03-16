<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vocabulary;
use App\Service\VocabularyService\VocabularyService;
use Illuminate\Http\Request;

class AdminVocabularyController extends Controller
{
    protected string $modelClass = Vocabulary::class;
    protected VocabularyService $service;

    public function __construct(VocabularyService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $datas = $this->modelClass::query()->paginate(10);
        return view('admin.pages.vocabularies.index', [
            'datas' => $datas,
        ]);
    }

    public function create()
    {
        $model = new $this->modelClass();
        return view('admin.pages.vocabularies.create', [
            'model' => $model,
        ]);
    }

    public function edit(string $id)
    {
        $model = $this->modelClass::findOrFail($id);

        if (is_string($model->word)) {
            $decodedWord = json_decode($model->word, true);
            $model->word = is_array($decodedWord) ? $decodedWord : [$model->word];
        }

        return view('admin.pages.vocabularies.edit', [
            'model' => $model,
        ]);
    }



    public function store(Request $request)
    {
        $vocabulary = $this->service->store($request);

        return redirect()->route('admin.vocabularies.index')
            ->with(['message' => 'Vocabulary successfully created']);
    }


    public function update(Request $request, string $id)
    {
        $model = $this->modelClass::findOrFail($id);
        $this->service->update($request, $model);

        return redirect()->route('admin.vocabularies.index')
            ->with(['message' => 'Vocabulary successfully updated']);
    }

    public function destroy(string $id)
    {
        $model = $this->modelClass::findOrFail($id);
        $model->delete();

        return response()->json(['success' => true, 'tr' => 'tr_' . $id]);
    }
}
