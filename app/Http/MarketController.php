<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class MarketController extends Controller
{
    public function show(): Factory|View|Application
    {
        $user = Auth::user();

        return view(
            'components.market', []
        );
    }
}
