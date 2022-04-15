<?php

namespace App\Models;

use App\Models\Eos\EOSClient;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;

class EOSCollection extends Model
{
    use HasFactory;

    static string $collection = 'crystallands';
    static string $apiUrl = 'https://test.wax.api.atomicassets.io/atomicassets/v1';

    /**
     * @return array
     */
    static public function getCollection(): array
    {
        $url = self::$apiUrl.'/collections/'.self::$collection;
        $response['success'] = false;
        while (!$response['success']) {
            $response = Http::get($url);
        }
        return $response['data'];
    }

    /**
     * @param  string  $template_id
     * @return array
     */
    static public function getTemplate(string $template_id): array
    {
        $url = self::$apiUrl.'/templates/'.self::$collection.'/'.$template_id;
        $response['success'] = false;
        while (!$response['success']) {
            $response = Http::get($url);
        }
        return $response['data'];
    }

    /**
     * @param  string  $asset_id
     * @return array
     */
    static public function getAsset(string $asset_id): array
    {
        $url = self::$apiUrl.'/assets/'.$asset_id;
        $response['success'] = false;
        while (!$response['success']) {
            $response = Http::get($url);
        }
        return $response['data'];
    }

    /**
     * @param  string  $schema_name
     * @param  array  $ids
     * @param  int  $page
     * @param  int  $limit
     * @param  string  $order
     * @param  string  $sort
     * @return array
     */
    static public function getTemplates(
        string $schema_name = '',
        array $ids = [],
        int $page = 1,
        int $limit = 100,
        string $order = 'desc',
        string $sort = 'created',
    ): array {
        $param = [
            'collection_name' => self::$collection,
            'schema_name' => $schema_name,
            'ids' => implode(',', $ids),
            'page' => $page,
            'limit' => $limit,
            'order' => $order,
            'sort' => $sort,
        ];
        $url = self::$apiUrl.'/templates?'.http_build_query($param);
        $response['success'] = false;
        while (!$response['success']) {
            $response = Http::get($url);
        }
        return $response['data'];
    }

    /**
     * @param  string  $owner
     * @param  string  $schema_name
     * @param  array  $template_whitelist
     * @param  int  $page
     * @param  int  $limit
     * @param  bool  $burned
     * @param  string  $order
     * @param  string  $sort
     * @return array
     */
    static public function getAssets(
        string $owner = '',
        string $schema_name = '',
        array $template_whitelist = [],
        int $page = 1,
        int $limit = 100,
        bool $burned = false,
        string $order = 'desc',
        string $sort = 'created',
    ): array {
        $param = [
            'collection_name' => self::$collection,
            'schema_name' => $schema_name,
            'template_whitelist' => implode(',', $template_whitelist),
            'burned' => $burned,
            'owner' => $owner,
            'page' => $page,
            'limit' => $limit,
            'order' => $order,
            'sort' => $sort,
        ];
        $url = self::$apiUrl.'/assets?'.http_build_query($param);
        $response['success'] = false;
        while (!$response['success']) {
            $response = Http::get($url);
        }
        return $response['data'];
    }

    /**
     * @param  string  $transaction_id
     * @param  string  $asset_id
     * @param  string  $executor
     * @return bool
     * @throws GuzzleException
     */
    static public function checkBurnAsset(string $transaction_id, string $asset_id, string $executor): bool
    {
        $client = new EOSClient();
        $transaction = $client->history()->getTransaction($transaction_id);
        $data = $transaction->traces[0]->act->data;
        if ($data->asset_owner != $executor || $data->asset_id != $asset_id) {
            return false;
        }
        return true;
    }

    /**
     * @param  string  $transaction_id
     * @param  string  $from
     * @param  string  $to
     * @return bool
     * @throws GuzzleException
     */
    static public function checkTransferToken(
        string $transaction_id,
        string $from,
        string $to = 'crystallands'
    ): bool|float|int {
        $client = new EOSClient();
        $transaction = $client->history()->getTransaction($transaction_id);
        $data = $transaction->traces[0]->act->data;
        if (empty($data) || $data->from != $from || $data->to != $to) {
            return false;
        }
        return $data->amount;
    }

    /**
     * @param  string  $schema_name
     * @param  int  $template_id
     * @param  string  $new_asset_owner
     * @return mixed
     */
    static public function mintAsset(string $schema_name, int $template_id, string $new_asset_owner): mixed
    {
        return Cleos::mintAsset($schema_name, $template_id, $new_asset_owner);
    }

    /**
     * @param  string  $recipient
     * @param  int  $quantity
     * @param  string  $memo
     * @return mixed
     */
    static public function transferToken(string $recipient, int $quantity, string $memo = ''): mixed
    {
        return Cleos::transfer($recipient, $quantity, $memo);
    }
}
