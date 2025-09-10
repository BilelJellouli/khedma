<?php

namespace App\Events\Users;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserCreated
{
    use Dispatchable;
    use Queueable;
    use SerializesModels;

    public function __construct(public readonly User $user) {}
}
