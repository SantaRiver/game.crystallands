<?php

namespace App\Models\Atomic\AtomicClient;

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
     * @throws Exception
     */
    public function assets(): array
    {
        return $this->client->call('assets');
    }

    /**
     * @throws Exception
     */
    public function assetsId($id): array
    {
        return $this->client->call('assetsId', ['id' => $id]);
    }

    /**
     * @throws Exception
     */
    public function collections(): array
    {
        return $this->client->call('collections');
    }

    /**
     * @throws Exception
     */
    public function collectionsName($collection_name): array
    {
        return $this->client->call('collectionsName', ['collection_name' => $collection_name]);
    }

    /**
     * @throws Exception
     */
    public function schemas(): array
    {
        return $this->client->call('schemas');
    }

    public function schemasName($collection_name, $schema_name): array
    {
        return $this->client->call('schemasName', ['collection_name' => $collection_name, 'schema_name' => $schema_name]);
    }

    /**
     * @throws Exception
     */
    public function templates($collection_name): array
    {
        return $this->client->call('templates', ['collection_name' => $collection_name]);
    }

    /**
     * @throws Exception
     */
    public function templatesId($collection_name, $template_id): array
    {
        return $this->client->call('templatesId', ['collection_name' => $collection_name, 'template_id' => $template_id]);
    }
}
