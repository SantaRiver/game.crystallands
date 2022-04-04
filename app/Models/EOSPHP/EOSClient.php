<?php

namespace App\Models\EOSPHP;

use App\Models\EOSPHP\Plugins\Chain;
use App\Models\EOSPHP\Plugins\History;
use App\Models\EOSPHP\Plugins\Wallet;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Config;
use JetBrains\PhpStorm\Pure;

class EOSClient
{
    private Client $client;

    public function __construct()
    {
        $this->client = new Client(['base_uri' => Config::get('constants.token.token_endpoint').'/v1/']);
    }

    #[Pure] public function chain(): Chain
    {
        return new Chain($this->client);
    }

    #[Pure] public function history(): History
    {
        return new History($this->client);
    }

    #[Pure] public function wallet(): Wallet
    {
        return new Wallet($this->client);
    }
}
