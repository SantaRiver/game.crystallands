<?php

namespace App\Http\Controllers;

use App\Models\EOSPHP\EOSClient;
use App\Models\User\UserStats;
use App\Models\User\UserWallet;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class PanelController extends Controller
{
    public function index(): Application|Factory|View
    {
        $user = Auth::user();
        $userStats = UserStats::query()->firstWhere('user_id', $user['id']);
        $userBalance = UserWallet::query()->firstWhere('user_id', $user['id']);

        return view(
            'components.panel',
            [
                'user' => $user,
                'userStats' => $userStats,
                'userBalance' => $userBalance,
            ]
        );
    }
}
