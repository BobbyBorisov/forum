<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FavoritesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(\App\Reply $reply)
    {
        $reply->favorite();

        return response([],204);
    }

    public function destroy(\App\Reply $reply)
    {
        $reply->unfavorite();

        return response([],204);
    }
}
