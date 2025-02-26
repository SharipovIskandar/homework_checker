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
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::group(['prefix' => 'admin', 'as' => 'admin.','middleware' => ['auth']], function () {
    Route::controller(DashboardController::class)->group(function () {
        Route::get('/dashboard', 'index')->name('dashboard');
    });

    Route::get('homework', [AdminHomeworkController::class, 'index'])->name('homework.index');
    Route::get('homework/create', [AdminHomeworkController::class, 'create'])->name('homework.create');
    Route::post('homework', [AdminHomeworkController::class, 'store'])->name('homework.store');
    Route::get('homework/{id}/edit', [AdminHomeworkController::class, 'edit'])->name('homework.edit');
    Route::put('homework/{id}/edit', [AdminHomeworkController::class, 'update'])->name('homework.update');
    Route::get('homework/{id}/delete', [AdminHomeworkController::class, 'destroy'])->name('homework.delete');

    Route::group(['prefix' => 'homework-questions', 'as' => 'homework-questions.'], function () {
        Route::controller(AdminHomeworkController::class)->group(function () {
           Route::get('/', 'index')->name('index');
           Route::get('create', 'create')->name('create');
           Route::post('store', 'store')->name('store');
        });
    });

    Route::get('students', [AdminStudentController::class, 'index'])->name('students.index');

    Route::get('homework-types', [AdminHomeworkTypeController::class, 'index'])->name('homework-types.index');

    Route::get('homework-correct-answers', [AdminHomeworkTypeController::class, 'index'])->name('homework-correct-answers.index');


    Route::get('clear-cash', function () {
        Artisan::call('cache:clear');
        return redirect()->back()->with(['message' => 'Кэши очищены!']);
    })->name('clear_cash');
});

Route::group(['prefix' => 'students', 'as' => 'students.',], function () {
    Route::get('homework', [StudentHomeworkController::class, 'index'])->name('students.homework.index');
});

require __DIR__ . '/auth.php';
