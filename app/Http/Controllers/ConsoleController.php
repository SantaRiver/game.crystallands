<?php

namespace App\Http\Controllers;

use App\Jobs\UpdateUserAssets;
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
        /*$client = new AtomicClient();
        for ($i = 0; $i < 1000; $i++) {
            $client->assets();
        }*/
        UpdateUserAssets::dispatch($user);
        //$inventory = $client->assets();
        //dd($inventory);
    }
}
