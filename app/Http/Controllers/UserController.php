<?php

namespace App\Http\Controllers;

use App\Models\User;
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
        $user = User::query()->find(Auth::id())->with(['resources', 'stats', 'items' => function($items){
            $items->join('item_assets', 'user_items.item_asset_id', '=', 'item_assets.id')
                ->join('items', 'item_assets.item_id', '=', 'items.id')
                ->join('cards', 'items.card_id', '=', 'cards.id');
        }])->first();
        dd($user);

        /*return view(
            'components.user',
            [
                'user' => $user
            ]
        );*/
    }

}
