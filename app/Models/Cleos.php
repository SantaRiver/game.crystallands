<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cleos extends Model
{
    use HasFactory;

    const URL = 'https://testnet.wax.eosdetroit.io';
    const PRECISION = 8;

    const WALLET = 'crystallands';
    const PASSWORD = 'PW5JZANwZ3oVuaZj6gDZxP24pNeUFyDcDTn96DeZ73wcyrfENAYGm';

    static public function transfer(string $recipient, float $quantity, string $memo = '')
    {
        $quantity = number_format($quantity, self::PRECISION);
        $response = self::run(
            'cleos -u '.self::URL.' push transaction -j \'{
              "actions": [
                {
                  "account": "crystallands",
                  "name": "transfer",
                  "data": {
                    "from": "'.self::WALLET.'",
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

        $strResponse = '';
        foreach ($response as $line) {
            $strResponse .= $line;
        }

        return json_decode($strResponse);
    }

    static public function run($command)
    {
        $status = null;
        $response = null;
        /*
         * Unlock wallet
         * */
        exec('cleos wallet open -n crystallands');
        exec('cleos wallet unlock -n crystallands --password PW5J1ntGSfrhBHS479NrzczqPMgdddR1ERXLe3aU1bBb1FUPVotXg');

        exec($command.' 2>&1', $response, $status);
        return $response;
    }

    static public function mintAsset(string $schema_name, int $template_id, string $new_asset_owner)
    {
        $response = self::run(
            'cleos -u '.self::URL.' push transaction -j \'{
              "actions": [
                  {
                    "account": "atomicassets",
                    "name": "mintasset",
                    "authorization": [
                      {
                        "actor": "crystallands",
                        "permission": "active"
                      }
                    ],
                    "data": {
                      "authorized_minter": "crystallands",
                      "collection_name": "crystallands",
                      "schema_name": "'.$schema_name.'",
                      "template_id": '.$template_id.',
                      "new_asset_owner": "'.$new_asset_owner.'",
                      "immutable_data": [],
                      "mutable_data": [],
                      "tokens_to_back": []
                    }
                  }
                ]
            }\''
        );
        $strResponse = '';
        foreach ($response as $line) {
            $strResponse .= $line;
        }

        return json_decode($strResponse);
    }

    /*PW5J1ntGSfrhBHS479NrzczqPMgdddR1ERXLe3aU1bBb1FUPVotXg*/

    static public function createWallet()
    {
        return self::run(
            'cleos wallet import -n crystallands --private-key 5JbwAoQQW95WiCJS7sVXVGN9JVwUhCTTc4xNJBCVzqPPPeZrdMb'
        );
    }
}
