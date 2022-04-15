<?php

namespace App\Listeners;

use App\Events\NewUser;
use App\Models\Atomic\AtomicClient\AtomicClient;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ScanInventory
{
    private AtomicClient $client;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        $this->client = new AtomicClient();
    }

    /**
     * Handle the event.
     *
     * @param NewUser $event
     * @return void
     * @throws Exception
     */
    public function handle(NewUser $event)
    {
        $user = $event->user;
        $this->client->inventory($user);
    }
}
