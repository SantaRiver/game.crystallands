<?php

namespace App\Console\Commands;

use App\Models\Atomic\AtomicClient\AtomicClient;
use App\Models\Cards;
use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;

class UpdateCardsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cards:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update cards table from atomic';
    private AtomicClient $client;

    public function __construct()
    {
        parent::__construct();
        $this->client = new AtomicClient();
    }

    /**
     * Execute the console command.
     *
     * @return bool
     * @throws Exception
     */
    public function handle(): bool
    {
        $templates = $this->client->templates([
            'collection_name' => config('atomic.collection_name'),
        ]);
        foreach ($templates as $template) {
            Cards::query()->updateOrCreate(
                [
                    "template_id" => $template['template_id']
                ],
                [
                    "name" => $template['name'],
                    "schema" => $template['schema']['schema_name'],
                    "description" => $template['immutable_data->description'] ?? '',
                    "is_transferable" => $template['is_transferable'],
                    "is_burnable" => $template['is_burnable'],
                    "issued_supply" => intval($template['issued_supply']),
                    "max_supply" => intval($template['max_supply']),
                    "immutable_data" => $template['immutable_data'],
                    "created_at_time" => new Carbon($template['created_at_time'] / 1000),
                    "image" => $template['immutable_data']['img'],
                ]);
        }
        return 0;
    }
}
