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

        // Share pending data with all admin views
        \Illuminate\Support\Facades\View::composer('admin.*', function ($view) {
            $pendingJobAppsCount = \App\Models\JobApplication::where('status', 'pending')->count();
            $pendingPaymentsCount = \App\Models\Payment::whereNotIn('status', ['Approved', 'Rejected'])->count();
            $pendingRegistrationsCount = \App\Models\Child::where('status', 'pending')->count();
            $unreadMessagesCount = \App\Models\Message::where('receiver_id', auth()->id())->where('is_read', false)->count();
            $totalPendingCount = $pendingJobAppsCount + $pendingPaymentsCount + $pendingRegistrationsCount;
            
            $view->with([
                'pendingJobAppsCount' => $pendingJobAppsCount,
                'pendingPaymentsCount' => $pendingPaymentsCount,
                'pendingRegistrationsCount' => $pendingRegistrationsCount,
                'unreadMessagesCount' => $unreadMessagesCount,
                'totalPendingCount' => $totalPendingCount,
            ]);
        });
    }
}
