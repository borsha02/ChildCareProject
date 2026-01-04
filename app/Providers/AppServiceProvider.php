<?php

namespace App\Providers;

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
        \Illuminate\Support\Facades\View::composer('parent.*', function ($view) {
            $unreadCount = 0;
            if (auth()->check()) {
                $unreadCount = auth()->user()->unreadNotifications->count();
            }
            $view->with('unreadCount', $unreadCount);
        });

        // Share pending job applications count with all admin views
        \Illuminate\Support\Facades\View::composer('admin.*', function ($view) {
            $pendingJobAppsCount = \App\Models\JobApplication::where('status', 'pending')->count();
            $view->with('pendingJobAppsCount', $pendingJobAppsCount);
        });
    }
}
