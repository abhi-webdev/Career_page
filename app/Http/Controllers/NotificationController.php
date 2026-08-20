<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;

class NotificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Mark notification as read
    |--------------------------------------------------------------------------
    */

    public function read(string $notification): RedirectResponse
    {
        $user = auth()->user();

        $user->notifications()
            ->where('id', $notification)
            ->update([
                'read_at' => now(),
            ]);

        return back();
    }


    /*
    |--------------------------------------------------------------------------
    | Mark all notifications as read
    |--------------------------------------------------------------------------
    */

    public function readAll(): RedirectResponse
    {
        auth()->user()
            ->unreadNotifications()
            ->update([
                'read_at' => now(),
            ]);

        return back();
    }
}