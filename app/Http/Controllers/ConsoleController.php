<?php

namespace App\Http\Controllers;

use App\Models\Atomic\AtomicClient\AtomicClient;
use App\Models\Cards;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use JetBrains\PhpStorm\NoReturn;

class ConsoleController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param Request $request
     * @throws Exception
     */
    public function __invoke(Request $request)//: JsonResponse
    {
        /** @var User $user */
        $user = Auth::user();
        $client = new AtomicClient();
        $inventory = $client->inventory($user);
        foreach ($inventory as $asset){
            $userAsset = new User\UserAsset(['user_id' => $user->id, 'asset_id' => $asset['asset_id']]);
            $userAsset->save();
        }
        dd($inventory);
    }
}
