<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ConsoleController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $res = Http::post('http://127.0.0.1:8888/v1/chain/get_account', [
            "account_name" => "eosio",
        ])->json();
        return $res;
    }
}
