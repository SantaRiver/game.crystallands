<?php

namespace App\Models\Eos\Plugins;

use GuzzleHttp\Client;

interface Plugin
{
    public function __construct(Client $client);
}
