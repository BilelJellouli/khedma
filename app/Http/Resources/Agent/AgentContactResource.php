<?php

namespace App\Http\Resources\Agent;

use App\Models\AgentContact;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property-read AgentContact $resource
 */
class AgentContactResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'type' => $this->resource->type,
            'value' => $this->resource->value,
            'is_primary' => $this->resource->is_primary,
        ];
    }
}
