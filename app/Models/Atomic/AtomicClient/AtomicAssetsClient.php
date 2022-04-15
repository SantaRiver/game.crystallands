<?php

namespace App\Models\Atomic\AtomicClient;

use App\Interfaces\AtomicClientInterface;
use Exception;
use Illuminate\Config\Repository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;

class AtomicAssetsClient extends Model implements AtomicClientInterface
{
    const PREFIX = '/atomicassets';
    const VERSION = '/v1';

    /**
     * @var Repository|Application|mixed
     */
    private mixed $url;

    const METHODS = [
        'assets' => ['method' => 'GET', 'uri' => '/assets'],
        'assetsId' => ['method' => 'GET', 'uri' => '/assets/{id}'],

        'collections' => ['method' => 'GET', 'uri' => '/collections'],
        'collectionsName' => ['method' => 'GET', 'uri' => '/collections/{collection_name}'],

        'schemas' => ['method' => 'GET', 'uri' => '/schemas'],
        'schemasName' => ['method' => 'GET', 'uri' => '/schemas/{collection_name}/{schema_name}'],

        'templates' => ['method' => 'GET', 'uri' => '/templates/'],
        'templatesId' => ['method' => 'GET', 'uri' => '/templates/{collection_name}/{template_id}'],
    ];

    public function __construct()
    {
        parent::__construct();
        $this->url = config('atomic.url');
    }

    /**
     * Call the Atomic API.
     *
     * @param string $method
     * @param array|null $params
     * @return array
     * @throws Exception
     */
    public function call($method, $params = null): array
    {
        if (!isset(self::METHODS[$method])) {
            throw new Exception('Method not found');
        }
        $url = $this->url . self::PREFIX . self::VERSION . (new AtomicAssetsClient)->requestFormatter($method, $params);
        //TODO: make stable connection
        $response = Http::{mb_strtolower(self::METHODS[$method]['method'])}($url);
        if ($response->failed()) {
            throw new Exception('Atomic Server Error : ' . $response->json()['message'] ?? 'Unknown error');
        }
        return $response->json()['data'];
    }

    /**
     * @throws Exception
     */
    public static function requestFormatter($method, $params): string
    {
        $method = self::METHODS[$method];
        preg_match_all('/{(\w*)}/', $method['uri'], $matches);
        if ($matches[1]) {
            $diff = array_diff($matches[1], array_keys($params));
            if ($diff) {
                throw new Exception('Missing parameters: ' . implode(', ', $diff));
            }
            foreach ($matches[1] as $index => $match) {
                $method['uri'] = str_replace('{' . $match . '}', $params[$match], $method['uri']);
            }
        } else {
            $method['uri'] = $method['uri'] . '?' . http_build_query($params ?? []);
        }

        return $method['uri'];
    }
}
