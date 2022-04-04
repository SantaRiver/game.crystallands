<?php

namespace App\Models\Eos\Plugins;

use App\Models\Cleos;
use App\Models\Eos\Types\Block;
use App\Models\Eos\Types\Info;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class Chain implements Plugin
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @return Info
     * @throws GuzzleException
     */
    public function getInfo(): Info
    {
        return new Info($this->client->get('chain/get_info')->getBody());
    }

    /**
     * @param  int  $blockOrNumber
     * @return Block
     * @throws GuzzleException
     */
    public function getBlock(int $blockOrNumber = 1): Block
    {
        return new Block(
            $this->client->post(
                'chain/get_block',
                ['body' => '{"block_num_or_id": "'.$blockOrNumber.'"}']
            )->getBody()
        );
    }

    /**
     * @param  string  $accountName
     * @return mixed
     * @throws GuzzleException
     */
    public function getAccount(string $accountName): mixed
    {
        $res = $this->client->post('chain/get_account', ['body' => '{"account_name": "'.$accountName.'"}']);

        return json_decode($res->getBody());
    }

    /**
     * @param  string  $accountName
     * @return mixed
     * @throws GuzzleException
     */
    public function getCode(string $accountName): mixed
    {
        $res = $this->client->post('chain/get_code', ['body' => '{"account_name": "'.$accountName.'"}']);

        return json_decode($res->getBody());
    }

    /**
     * @param  string  $scope
     * @param  string  $code
     * @param  string  $table
     * @param  bool  $json
     * @param  int  $lowerBound
     * @param  int  $upperBound
     * @param  int  $limit
     * @return mixed
     * @throws GuzzleException
     */
    public function getTableRows(
        string $scope,
        string $code,
        string $table,
        bool $json = true,
        int $lowerBound = 0,
        int $upperBound = -1,
        int $limit = 10
    ): mixed {
        $body = '{"scope":"'.$scope.'", "code":"'.$code.'", "table":"'.$table.'", "json": '.$json.', "lower_bound":'.$lowerBound.', "upper_bound":'.$upperBound.', "limit":'.$limit.'}';
        $res = $this->client->post('chain/get_table_rows', ['body' => $body]);

        return json_decode($res->getBody());
    }

    /**
     * @param  string  $code
     * @param  string  $action
     * @param  string  $args
     * @return mixed
     * @throws GuzzleException
     */
    public function abiJsonToBin(string $code, string $action, string $args): mixed
    {
        $body = '{"code":"'.$code.'", "action":"'.$action.'", "args":'.$args.'}';
        $res = $this->client->post('chain/abi_json_to_bin', ['body' => $body]);

        return json_decode($res->getBody());
    }

    /**
     * @param  string  $code
     * @param  string  $action
     * @param  string  $binArgs
     * @return mixed
     * @throws GuzzleException
     */
    public function abiBinToJson(string $code, string $action, string $binArgs): mixed
    {
        $body = '{"code":"'.$code.'", "action":"'.$action.'", "binargs":'.$binArgs.'}';
        $res = $this->client->post('chain/abi_json_to_bin', ['body' => $body]);

        return json_decode($res->getBody());
    }

    /**
     * @param  int  $lowerBound
     * @param  int  $limit
     * @param  bool  $json
     * @return mixed
     * @throws GuzzleException
     */
    public function getProducers(int $lowerBound, int $limit, bool $json = true): mixed
    {
        $body = '{"json":'.$json.', "lower_bound":"'.$lowerBound.'", "limit":'.$limit.'}';
        $res = $this->client->post('chain/get_producers', ['body' => $body]);

        return json_decode($res->getBody());
    }

    /**
     * @param  string  $code
     * @param  string  $account
     * @param  string|null  $symbol
     * @return mixed
     * @throws GuzzleException
     */
    public function getCurrencyBalance(string $code, string $account, ?string $symbol): mixed
    {
        $body = '{"code":"'.$code.'", "account":"'.$account.'", "symbol":"'.$symbol.'"}';
        $res = $this->client->post('chain/get_currency_balance', ['body' => $body]);
        $res = json_decode($res->getBody());
        if (empty($res)){
            return 0;
        }
        return floatval(explode(' ', $res[0])[0]);
    }

    /**
     * @param  string  $code
     * @param  string  $symbol
     * @return mixed
     * @throws GuzzleException
     */
    public function getCurrencyStats(string $code, string $symbol): mixed
    {
        $body = '{"code":"'.$code.'", "symbol":"'.$symbol.'"}';
        $res = $this->client->post('chain/get_currency_stats', ['body' => $body]);

        return json_decode($res->getBody());
    }

    public function getRequiredKeys()
    {
        // TODO
    }

    public function pushBlock()
    {
        // TODO
    }

    /**
     * @param  array  $extra
     * @return mixed
     * @throws GuzzleException
     */
    public function pushTransaction(
        /*string $expiration,
        string $ref_block_num,
        string $ref_block_prefix,*/
        array $extra = []
    ): mixed {
        $body = [
            'compression' => 'none',
            'transaction' => [
                /*'expiration' => $expiration,
                'ref_block_num' => $ref_block_num,
                'ref_block_prefix' => $ref_block_prefix,*/
                'context_free_actions' => [],
                'actions' => $extra['actions'],
                'transaction_extensions' => [],
            ],
            'signatures' => $extra['signatures']
        ];
        $body = json_encode($body);
        $res = $this->client->post('chain/get_currency_stats', ['body' => $body]);

        return json_decode($res->getBody());
    }

    static public function transferToken(string $recipient, float $quantity, string $memo = '')
    {
        $quantity = number_format($quantity, 8);
        return Cleos::run(
            'cleos -u '.Cleos::URL.' push transaction -j \'{
              "delay_sec": 0,
              "max_cpu_usage_ms": 0,
              "actions": [
                {
                  "account": "crystallands",
                  "name": "transfer",
                  "data": {
                    "from": "'.Cleos::WALLET.'",
                    "to": "'.$recipient.'",
                    "quantity": "'.$quantity.' CRSTL",
                    "memo": "'.$memo.'"
                  },
                  "authorization": [
                    {
                      "actor": "crystallands",
                      "permission": "active"
                    }
                  ]
                }
              ]
            }\''
        );
    }

    public function pushTransactions()
    {
        // TODO
    }
}
