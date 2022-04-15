<?php

namespace App\Http\Controllers;

use App\Models\Cards;
use App\Models\EOSCollection;
use App\Models\ItemAssets;
use App\Models\Items;
use App\Models\User\UserItems;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InterfaceController extends Controller
{
    public function showSelectTool(): Factory|View|Application
    {
        $user = Auth::user();
        $atomicAssets = EOSCollection::getAssets($user['name']);

        return view('components.select_tool_interface', ['atomicAssets' => $atomicAssets]);
    }

    /**
     * @throws GuzzleException
     */
    public function selectTool(Request $request): JsonResponse
    {
        $request->validate(
            [
                'transaction_id' => 'required|max:64|min:64',
                'asset_id' => 'required',
            ]
        );
        $user = Auth::user();
        $transactionId = $request->get('transaction_id');
        $asset_id = $request->get('asset_id');

        sleep(2);

        if (!EOSCollection::checkBurnAsset($transactionId, $asset_id, $user['name'])) {
            return response()->json(['status' => 'error', 'message' => 'Failed to confirm transaction']);
        }

        $asset = EOSCollection::getAsset($asset_id);
        $card = Cards::query()->firstWhere('template_id', $asset['template']['template_id']);
        $item = Items::query()->firstWhere('card_id', $card['id']);
        $itemAssets = new ItemAssets(
            [
                'item_id' => $item['id'],
                'asset_id' => $asset['asset_id'],
                'current_strength' => $item['max_strength'],
            ]
        );
        $itemAssets->save();
        (new UserItems(
            [
                'user_id' => $user['id'],
                'item_asset_id' => $itemAssets['id'],
            ]
        ))->save();

        return response()->json(['status' => 'success']);
    }
}
