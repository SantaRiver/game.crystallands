<?php

namespace App\Jobs;

use App\Models\Atomic\AtomicClient\AtomicClient;
use App\Models\User;
use App\Models\User\UserAsset;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateUserAssets implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected User $user;
    private AtomicClient $client;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
        $this->client = new AtomicClient();
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws Exception
     */
    public function handle()
    {
        print 'Updating user assets for ' . $this->user->name . PHP_EOL;
        $userAssets = $this->client->assets(['owner' => $this->user->name]);
        foreach ($userAssets as $asset) {
            $userAsset = new UserAsset([
                'user_id' => $this->user['id'],
                'asset_id' => $asset['asset_id']]
            );
            $userAsset->save();
        }
    }
}
