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
    }
}
