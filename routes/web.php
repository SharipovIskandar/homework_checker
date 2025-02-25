<?php

use App\Http\Controllers\Admin\AdminHomeworkController;
use App\Http\Controllers\Admin\AdminHomeworkTypeController;
use App\Http\Controllers\Admin\AdminStudentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Student\StudentHomeworkController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::group(['prefix' => 'admin', 'as' => 'admin.',], function () {
    Route::controller(DashboardController::class)->group(function () {
        Route::get('/dashboard', 'index')->name('dashboard');
    });

    Route::get('homework', [AdminHomeworkController::class, 'index'])->name('homework.index');

    Route::get('students', [AdminStudentController::class, 'index'])->name('students.index');

    Route::get('homework-types', [AdminHomeworkTypeController::class, 'index'])->name('homework-types.index');

    Route::get('homework-correct-answers', [AdminHomeworkTypeController::class, 'index'])->name('homework-correct-answers.index');


    Route::get('clear-cash', function () {
        Artisan::call('cache:clear');
        return redirect()->back()->with(['message' => 'Кэши очищены!']);
    })->name('clear_cash');
});

Route::group(['prefix' => 'student', 'as' => 'student.',], function () {
    Route::get('homework', [StudentHomeworkController::class, 'index'])->name('student.homework.index');
});

require __DIR__ . '/auth.php';
