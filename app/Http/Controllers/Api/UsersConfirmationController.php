<?php

namespace App\Http\Controllers\Api;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UsersConfirmationController extends Controller
{
    public function index()
    {
        User::where('confirmation_token', request('confirmation_token'))
            ->firstOrFail()
            ->update(['confirmed' => true, 'confirmation_token' => null]);

        return redirect('/threads');
    }
}
