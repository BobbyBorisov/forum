<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Notifications\DatabaseNotification;

class UserNotificationsController extends Controller
{
    public function index()
    {
        return auth()->user()->unreadNotifications;
    }

    public function store($userId,DatabaseNotification $notification)
    {
        $notification->update(['read_at' => Carbon::now()]);

        return response([], 204);
    }
}
