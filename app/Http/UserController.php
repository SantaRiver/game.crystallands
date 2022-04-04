<?php

namespace App\Http\Controllers;

use App\Models\User\UserItems;
use App\Models\User\UserResources;
use App\Models\User\UserStats;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function show(): Factory|View|Application
    {
        $user = Auth::user();

        $userStats = UserStats::query()->firstWhere('user_id', $user['id']);
        $userResources = UserResources::query()->firstWhere('user_id', $user['id']);
        $userItems = UserItems::query()->where('user_id', '=', $user['id'])
            ->join('item_assets', 'user_items.item_asset_id', '=', 'item_assets.id')
            ->join('items', 'item_assets.item_id', '=', 'items.id')
            ->join('cards', 'items.card_id', '=', 'cards.id')
            ->get();

        return view(
            'components.user',
            [
                'user' => [
                    'stats' => $userStats,
                    'resources' => $userResources,
                    'items' => $userItems,
                ]
            ]
        );
    }

}
