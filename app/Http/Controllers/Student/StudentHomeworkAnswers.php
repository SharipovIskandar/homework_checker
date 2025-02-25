<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class StudentHomeworkAnswers extends Controller
{
    protected string $modelClass = User::class;

    public function index()
    {

        $datas = $this->modelClass::query()->paginate();
        return view('students.pages.homework.index', [
            'datas' => $datas,
        ]);
    }

    public function create()
    {
        $users = User::all();
        $model = new $this->modelClass();

        return view('students.pages.homework.create', [
            'model' => $model,
            'users' => $users,
        ]);
    }

    public function store(Request $request)
    {
        $result = $projectOverviewService->store($request);

        return redirect()->route('students.homework.index');
    }

    public function edit(string $id)
    {

        $model = $this->modelClass::findOrFail($id);
        $users = User::all();
        return view('students.pages.project-overview.edit', [
            'model' => $model,
            'users' => $users,
            'languages' => allLanguage(),
        ]);
    }

    public function update( $request, $id)
    {
        $this->customUpdate($id, $request);

        return redirect()->route('students.homework.index')
            ->with(['message' => 'Успешно обновлено']);
    }

    public function destroy($id)
    {
        $model = $this->modelClass::findOrFail($id);
        $this->customDelete($id);
        return response()->json(['success' => true, 'tr' => 'tr_' . $id]);
    }
}
