<?php

namespace App\Http\Controllers;

use App\Models\CraftRecipe;
use App\Models\EOSCollection;
use App\Models\Items;
use App\Models\Log\CraftLog;
use App\Models\Log\EosUserTransaction;
use App\Models\User\UserResources;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CraftController extends Controller
{
    static array $resources = [
        'wood',
        'stone',
        'iron',
        'iron_ingot',
    ];

    public function show(): Factory|View|Application
    {
        $craftRecipes = CraftRecipe::query()
            ->join('items', 'craft_recipes.item_id', '=', 'items.id')
            ->join('cards', 'items.card_id', '=', 'cards.id')
            ->get();
        return view(
            'buildings_interface.craft',
            [
                'location' => 'craft',
                'craftRecipes' => $craftRecipes,
            ]
        );
    }

    public function craft(Request $request): JsonResponse
    {
        $request->validate(['craft_id' => 'required']);
        $user = Auth::user();
        $userResources = UserResources::query()->firstWhere('user_id', $user['id']);

        $craftRecipe = CraftRecipe::query()->find($request->get('craft_id'));
        $item = Items::query()->join('cards', 'card_id', '=', 'cards.id')
            ->find($craftRecipe['item_id']);

        $lackOfResources = [];
        foreach (self::$resources as $resource) {
            if ($craftRecipe[$resource] > $userResources[$resource]) {
                $lackOfResources[$resource] = $craftRecipe[$resource] - $userResources[$resource];
            }
        }
        if ($lackOfResources) {
            $lackOfResourcesMessage = "Lack of the following resources: ";
            foreach ($lackOfResources as $resource => $lackOfResource) {
                $lackOfResourcesMessage .= $resource."($lackOfResource); ";
            }
            return response()->json(['status' => 'error', 'message' => $lackOfResourcesMessage]);
        }

        $transaction = EOSCollection::mintAsset($item['schema'], $item['template_id'], $user['name']);
        if ($transaction->transaction_id) {
            foreach (self::$resources as $resource) {
                $userResources[$resource] = $userResources[$resource] - $craftRecipe[$resource];
            }
            $userResources->save();

            (new CraftLog(
                [
                    'user_id' => $user['id'],
                    'craft_recipe_id' => $craftRecipe['id'],
                ]
            ))->save();
            (new EosUserTransaction(
                [
                    'user_id' => $user['id'],
                    'action' => 'mintAsset',
                    'transaction_id' => $transaction->transaction_id,
                    'status' => 'executed',
                ]
            ))->save();
            return response()->json(['status' => 'success', 'transaction_id' => $transaction->transaction_id]);
        }

        return response()->json(['status' => 'error', 'Impossible to mint new asset']);
    }
}
