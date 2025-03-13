<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Homework;
use App\Models\User;
use App\Traits\Crud;
use Illuminate\Http\Request;

class AdminStudentController extends Controller
{
    use Crud;

    protected string $modelClass = User::class;

    public function index()
    {

        $datas = $this->modelClass::query()->paginate();
        return view('admin.pages.students.index', [
            'datas' => $datas,
        ]);
    }

    public function create()
    {
        $users = User::all();
        $model = new $this->modelClass();

        return view('admin.pages.students.create', [
            'model' => $model,
            'users' => $users,
        ]);
    }


    public function edit(string $id)
    {

        $model = $this->modelClass::findOrFail($id);
        $users = User::all();
        return view('admin.pages.students.edit', [
            'model' => $model,
            'users' => $users,
        ]);
    }

    public function update(Request $request, $id)
    {
        $this->customUpdate($id, $request);

        return redirect()->route('admin.students.index')
            ->with(['message' => 'Успешно обновлено']);
    }

    public function destroy($id)
    {
        $model = $this->modelClass::findOrFail($id);
        $this->customDelete($id);
        return response()->json(['success' => true, 'tr' => 'tr_' . $id]);
    }
}
