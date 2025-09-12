<?php

declare(strict_types=1);

namespace App\Http\Resources\Agent;

use App\Models\Agent;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property-read Agent $resource
 */
class ReducedAgentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'name' => $this->whenLoaded('user', $this->resource->user->name), // @phpstan-ignore-line
        ];
    }
}
