<?php

namespace App\Models\Atomic\AtomicClient;

use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Model;

class AtomicClient extends Model
{
    private AtomicAssetsClient $client;

    public function __construct()
    {
        parent::__construct();
        $this->client = new AtomicAssetsClient();
    }

    /**
     * @param array $params
     * @return array
     * @throws Exception
     */
    public function assets(array $params): array
    {
        return $this->client->call('assets', $params);
    }

    /**
     * @param string $id
     * @return array
     * @throws Exception
     */
    public function assetsId(string $id): array
    {
        return $this->client->call('assetsId', ['id' => $id]);
    }

    /**
     * @param array $params
     * @return array
     * @throws Exception
     */
    public function collections(array $params): array
    {
        return $this->client->call('collections', $params);
    }

    /**
     * @param string $collection_name
     * @return array
     * @throws Exception
     */
    public function collectionsName(string $collection_name): array
    {
        return $this->client->call('collectionsName', ['collection_name' => $collection_name]);
    }

    /**
     * @param array $params
     * @return array
     * @throws Exception
     */
    public function schemas(array $params): array
    {
        return $this->client->call('schemas');
    }

    /**
     * @param string $collection_name
     * @param string $schema_name
     * @return array
     * @throws Exception
     */
    public function schemasName(string $collection_name, string $schema_name): array
    {
        return $this->client->call('schemasName', ['collection_name' => $collection_name, 'schema_name' => $schema_name]);
    }

    /**
     * @param array $params
     * @return array
     * @throws Exception
     */
    public function templates(array $params): array
    {
        return $this->client->call('templates', $params);
    }

    /**
     * @param string $collection_name
     * @param string $template_id
     * @return array
     * @throws Exception
     */
    public function templatesId(string $collection_name, string $template_id): array
    {
        return $this->client->call('templatesId', ['collection_name' => $collection_name, 'template_id' => $template_id]);
    }

    /**
     * @param User $user
     * @return array
     * @throws Exception
     */
    public function inventory(User $user): array
    {
        return $this->assets([
            'collection_name' => config('atomic.collection_name'),
            'owner' => $user->name,
        ]);
    }
}
