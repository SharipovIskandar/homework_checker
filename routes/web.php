<?php

use App\Http\Controllers\Admin\AdminHomeworkController;
use App\Http\Controllers\Admin\AdminHomeworkQuestionsController;
use App\Http\Controllers\Admin\AdminHomeworkTypeController;
use App\Http\Controllers\Admin\AdminStudentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Student\StudentHomeworkController;
use App\Http\Controllers\Student\StudentHomeworkSubmissionController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['auth']], function () {

        Route::group(['prefix' => 'homework', 'as' => 'homework.'], function () {
            Route::get('/', [AdminHomeworkController::class, 'index'])->name('index');
            Route::get('/create', [AdminHomeworkController::class, 'create'])->name('create');
            Route::post('/', [AdminHomeworkController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [AdminHomeworkController::class, 'edit'])->name('edit');
            Route::put('/{id}/edit', [AdminHomeworkController::class, 'update'])->name('update');
            Route::get('/{id}/delete', [AdminHomeworkController::class, 'destroy'])->name('delete');
        });
        Route::group(['prefix' => 'homework-questions', 'as' => 'homework-questions.'], function () {
            Route::controller(AdminHomeworkQuestionsController::class)->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('create', 'create')->name('create');
                Route::post('store', 'store')->name('store');
            });
        });

        Route::group(['prefix' => 'homework-types', 'as' => 'homework-types.'], function () {
            Route::get('/', [AdminHomeworkTypeController::class, 'index'])->name('index');
            Route::get('/create', [AdminHomeworkTypeController::class, 'create'])->name('create');
            Route::post('/', [AdminHomeworkTypeController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [AdminHomeworkTypeController::class, 'edit'])->name('edit');
            Route::put('/{id}/edit', [AdminHomeworkTypeController::class, 'update'])->name('update');
            Route::delete('/{id}/delete', [AdminHomeworkTypeController::class, 'destroy'])->name('delete');
        });

        Route::get('homework-correct-answers', [AdminHomeworkTypeController::class, 'index'])->name('homework-correct-answers.index');


        Route::get('clear-cash', function () {
            Artisan::call('cache:clear');
            return redirect()->back()->with(['message' => 'Кэши очищены!']);
        })->name('clear_cash');
    });

    Route::group(['prefix' => 'student-homeworks', 'as' => 'student.homeworks.',], function () {
        Route::get('/', [StudentHomeworkController::class, 'index'])->name('index');
    });

    Route::group(['prefix' => 'student-homework/submissions', 'as' => 'student.homework.submissions.',], function () {
        Route::controller(StudentHomeworkSubmissionController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('/{id}/edit', 'edit')->name('edit');
            Route::put('/{id}/edit', 'update')->name('update');
            Route::delete('/{id}/delete', 'destroy')->name('delete');
        });
    });
});

require __DIR__ . '/auth.php';
