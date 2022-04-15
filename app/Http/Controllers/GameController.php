<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GameController extends Controller
{
    /**
     * Show the application game.
     *
     * @return Application|Factory|View
     */
    public function index(): Factory|View|Application
    {
        $user = Auth::user();
        return view('pages.game', [
            'user' => $user ?? [],
        ]);
    }
}
