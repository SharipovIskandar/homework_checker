<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard');
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
