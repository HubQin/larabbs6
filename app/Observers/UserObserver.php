<?php

namespace App\Observers;

use App\Models\User;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class UserObserver
{
    public function saving(User $user)
    {
        if (empty($user->avatar)) {
            $user->avatar = config('app.url') . '/uploads/images/avatar/201911/24/1_1574607831_Er5MITXiPB.png';
        }
    }
}
