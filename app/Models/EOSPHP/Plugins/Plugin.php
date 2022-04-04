<?php

namespace App\Models\EOSPHP\Plugins;

use GuzzleHttp\Client;

interface Plugin
{
    public function __construct(Client $client);
}
