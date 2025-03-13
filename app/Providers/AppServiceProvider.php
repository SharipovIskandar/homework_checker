<?php

namespace App\Providers;

use App\Models\HomeworkSubmission;
use App\Observers\HomeworkSubmissionObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        HomeworkSubmission::observe(HomeworkSubmissionObserver::class);
    }
}
