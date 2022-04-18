<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class UserInventoryController extends Controller
{

    /**
     * @param $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        $user = User::findOrFail($id);
        //$userWallet = $user->resources()->first();
        $plug = [
            'game' => [
                [
                    'id' => 1,
                    'name' => 'Game',
                    'quantity' => 3,
                ],
                [
                    'id' => 2,
                    'name' => 'Not Game',
                    'quantity' => 1,
                ],
            ],
            'atomic' => [
                [
                    'id' => 1,
                    'name' => 'atomic',
                    'quantity' => 2,
                ],
                [
                    'id' => 2,
                    'name' => 'Not atomic',
                    'quantity' => 1,
                ],
            ]
        ];
        return response()->json($plug, ResponseAlias::HTTP_CREATED);
    }
}
