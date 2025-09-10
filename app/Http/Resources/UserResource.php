<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property-read User $resource
 */
class UserResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'name' => $this->resource->name,
            'email' => $this->resource->email,
        ];
    }
}
