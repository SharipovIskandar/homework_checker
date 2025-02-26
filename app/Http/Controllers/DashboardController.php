<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\Scopes;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    use Scopes;
    public function index()
    {
        $request = request();

        $request->validate([
            'date_start' => ['nullable', 'date'],
            'date_end' => ['nullable', 'date', 'after:date_start']
        ]);

        if (!$request->has('date_start')) {
            $request->merge([
                'date_start' => date('Y-m-d', strtotime(date('Y-m-d') . '-1 month')),
            ]);
        }
        if (!$request->has('date_end')) {
            $request->merge([
                'date_end' => date('Y-m-d'),
            ]);
        }

        $users = User::all()->count();

        return view('admin.pages.dashboard.index', compact('users'));
    }

    public function homeworks()
    {
        return view('layout.');
    }

    public function studentHomeworks()
    {
        return view('student.homeworks');
    }
}
