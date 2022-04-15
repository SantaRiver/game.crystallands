<?php

namespace App\Models\Eos\Plugins;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class History implements Plugin
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param  string  $accountName
     * @param  int  $pos
     * @param  int  $offset
     * @return mixed
     * @throws GuzzleException
     */
    public function getActions(string $accountName, int $pos = 0, int $offset = 0): mixed
    {
        $body = '{"account_name":"'.$accountName.'", "pos":'.$pos.', "offset": '.$offset.'}';
        $res = $this->client->post('history/get_actions', ['body' => $body]);

        return json_decode($res->getBody());
    }

    /**
     * @param  string  $transactionId
     * @return mixed
     * @throws GuzzleException
     */
    public function getTransaction(string $transactionId): mixed
    {
        $body = '{"id":"'.$transactionId.'"}';
        $res = $this->client->post('history/get_transaction', ['body' => $body]);

        return json_decode($res->getBody());
    }
}
