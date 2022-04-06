<?php

namespace App\Listeners;

use App\Events\NewUser;
use App\Models\User\UserResources;
use App\Models\User\UserStats;
use App\Models\User\UserWallet;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class PostCreatedUser
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param NewUser $event
     * @return void
     */
    public function handle(NewUser $event)
    {
        $user = $event->user;
        (new UserStats(['user_id' => $user['id']]))->save();
        (new UserWallet(['user_id' => $user['id']]))->save();
        (new UserResources(['user_id' => $user['id']]))->save();
    }
}
