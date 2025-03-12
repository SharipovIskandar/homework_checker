<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProjectOverviewRequest;
use App\Http\Requests\Admin\ProjectOverviewUpdateRequest;
use App\Models\Homework;
use App\Models\HomeworkType;
use App\Models\ProjectOverviewTracking;
use App\Models\Subject;
use App\Models\User;
use App\Services\ProjectOverview\ProjectOverviewService;
use App\Traits\Crud;
use Illuminate\Http\Request;

class AdminHomeworkController extends Controller
{
    use Crud;

    protected string $modelClass = Homework::class;

    public function index()
    {
        $datas = $this->modelClass::with(['subject', 'type'])
            ->paginate();
        return view('admin.pages.homework.index', [
            'datas' => $datas,
        ]);
    }

    public function create()
    {
        $users = User::all();
        $subjects = Subject::all();
        $homeworkTypes = HomeworkType::all();
        $model = new $this->modelClass();

        return view('admin.pages.homework.create', [
            'model' => $model,
            'subjects' => $subjects,
            'homeworkTypes' => $homeworkTypes,
            'users' => $users,
        ]);
    }

    public function store(Request $request)
    {
        $result = $this->customStore($request);

        return redirect()->route('admin.homework.index');
    }

    public function edit(string $id)
    {
        $model = $this->modelClass::findOrFail($id);
        $users = User::all();
        $subjects = Subject::all();
        $homeworkTypes = HomeworkType::all();
        return view('admin.pages.homework.edit', [
            'model' => $model,
            'subjects' => $subjects,
            'homeworkTypes' => $homeworkTypes,
            'users' => $users,
        ]);
    }

    public function update(Request $request, $id)
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
