<?php

namespace App\Http\Controllers;

use App\Http\Requests\MineRequest;
use App\Models\ItemAssets;
use App\Models\Items;
use App\Models\Log\MiningLog;
use App\Models\Log\MiningRewardLog;
use App\Models\MiningLocation;
use App\Models\User\UserItems;
use App\Models\User\UserResources;
use App\Models\User\UserStats;
use Carbon\Carbon;
use Carbon\Traits\Creator;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MineBuildingsController extends Controller
{
    public function show($location): Factory|View|Application
    {
        $user = Auth::user();

        $location = MiningLocation::query()->firstWhere('name', $location);

        $items = UserItems::query()->where('user_id', '=', $user['id'])
            ->join('item_assets', 'user_items.item_asset_id', '=', 'item_assets.id')
            ->join('items', 'item_assets.item_id', '=', 'items.id')
            ->join('cards', 'items.card_id', '=', 'cards.id')
            ->where('type', $location['item_type'])
            ->get();
        $userStats = UserStats::query()->firstWhere('user_id', $user['id']);

        $lastMiningLog = MiningLog::query()
            ->where('user_id', $user['id'])
            ->orderBy('created_at', 'desc')
            ->first();
        $lastMiningLocationLog = MiningLog::query()
            ->where('user_id', $user['id'])
            ->orderBy('created_at', 'desc')
            ->where('location', '=', $location['name'])
            ->first();

        $miningStatus = 'completed';
        $anotherProcessInProgress = false;

        $nowDateTime = Carbon::now();

        if ($lastMiningLocationLog) {
            $endMiningLocationDateTime = new Carbon($lastMiningLocationLog['end_time']);
            $differenceSecond = $endMiningLocationDateTime->diffInSeconds($nowDateTime);

            $miningStatus = 'in_progress';
            if ($nowDateTime > $endMiningLocationDateTime) {
                $miningStatus = 'pending';
                if (!empty(MiningRewardLog::query()->firstWhere('mining_log_id', $lastMiningLocationLog['id']))) {
                    $miningStatus = 'completed';
                }
            }
        }

        if ($lastMiningLog) {
            $anotherProcessInProgress = false;
            $endMiningDateTime = new Carbon($lastMiningLog['end_time']);
            if ($nowDateTime < $endMiningDateTime) {
                $anotherProcessInProgress = true;
            }
        }

        $miningLocationLogInfo = [
            'status' => $miningStatus,
            'diffFormat' => gmdate('H:i:s', $differenceSecond ?? 0),
        ];

        return view(
            'buildings_interface.mining',
            [
                'location' => $location['name'],
                'anotherProcessInProgress' => $anotherProcessInProgress,
                'items' => $items,
                'userStats' => $userStats,
                'lastMiningLog' => $lastMiningLog,
                'miningStatus' => $miningLocationLogInfo,
            ]
        );
    }

    public function action($location, MineRequest $request): JsonResponse
    {
        $user = Auth::user();
        $location = MiningLocation::query()->firstWhere('name', $location);

        $itemAsset = ItemAssets::query()->firstWhere('asset_id', $request->get('item_asset_id'));
        $item = Items::query()->find($itemAsset['item_id']);

        $nowDateTime = Carbon::now();
        $endTime = $nowDateTime->addSeconds($item['mining_time']);

        $lastMiningLog = MiningLog::query()
            ->where('user_id', $user['id'])
            ->orderBy('created_at', 'desc')
            ->first();

        if ($lastMiningLog){
            $endMiningDateTime = new Carbon($lastMiningLog['end_time']);
            if (Carbon::now() < $endMiningDateTime) {
                return response()->json(['status' => 'error', 'message' => 'Another mining process in progress']);
            }
        }

        (new MiningLog(
            [
                'user_id' => $user['id'],
                'location' => $location['name'],
                'item_id' => $item['id'],
                'item_asset_id' => $itemAsset['id'],
                'end_time' => $endTime,
            ]
        ))->save();

        $itemAsset['current_strength'] -= $item['wear'];
        $itemAsset->save();

        return response()->json(['status' => 'success']);
    }

    public function claim($location, Request $request): JsonResponse
    {
        $user = Auth::user();

        $location = MiningLocation::query()->firstWhere('name', $location);

        $lastMiningLocationLog = MiningLog::query()
            ->where('user_id', $user['id'])
            ->where('location', $location['name'])
            ->orderBy('created_at', 'desc')
            ->first();
        $nowDateTime = Carbon::now();
        $endMiningLocationDateTime = new Carbon($lastMiningLocationLog['end_time']);
        if ($nowDateTime < $endMiningLocationDateTime) {
            return response()->json(['status' => 'error', 'message' => 'Mining in progress']);
        }

        $item = Items::query()->find($lastMiningLocationLog['item_id']);
        $userResource = UserResources::query()->firstWhere('user_id', $user['id']);
        foreach (json_decode($location['resources']) as $resource) {
            $userResource[$resource] += $item['efficiency'];
            $userResource->save();
            (new MiningRewardLog(
                [
                    'mining_log_id' => $lastMiningLocationLog['id'],
                    'reward' => $item['efficiency'],
                    'resource' => $resource,
                ]
            ))->save();
        }
        return response()->json(['status' => 'success']);
    }
}
