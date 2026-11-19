<?php

namespace App\Providers;

use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
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
        View::composer('*', function ($view) {
            if (Auth::check()) {
                $user = Auth::user();
                if ($user->role === 'admin') {
                    $notifications = Notification::where('user_id', $user->id)
                        ->orWhereNull('user_id')
                        ->latest()
                        ->take(15)
                        ->get();
                    $unreadCount = Notification::where('user_id', $user->id)
                        ->orWhereNull('user_id')
                        ->where('is_read', false)
                        ->count();
                } else {
                    $notifications = Notification::where('user_id', $user->id)
                        ->latest()
                        ->take(15)
                        ->get();
                    $unreadCount = Notification::where('user_id', $user->id)
                        ->where('is_read', false)
                        ->count();
                }
                $view->with('notifications', $notifications)->with('unreadCount', $unreadCount);
            }
        });
    }
}
