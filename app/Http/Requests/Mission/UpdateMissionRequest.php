<?php

declare(strict_types=1);

namespace App\Http\Requests\Mission;

use App\Enums\UserRole;
use App\Models\Mission;
use App\Models\User;

class UpdateMissionRequest extends CreateMissionRequest
{
    public function authorize(): bool
    {
        /** @var User $user */
        $user = $this->user();

        /** @var Mission $mission */
        $mission = $this->route()->parameter('mission'); // @phpstan-ignore-line

        if ($user->role === UserRole::ADMIN) {
            return true;
        }

        return $user->id === $mission->customer_id;
    }
}
