<?php

namespace App\Http\Controllers;

use App\Models\Atomic\AtomicClient\AtomicClient;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use JetBrains\PhpStorm\NoReturn;

class ConsoleController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        return \response()->json($request->all());
    }
}
