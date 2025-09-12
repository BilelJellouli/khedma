<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property-read \App\Models\Mission $resource
 */
class MissionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'title' => $this->resource->title,
            'description' => $this->resource->title,
            'location' => $this->resource->location,
            'status' => $this->resource->status,
            'type' => $this->resource->type,
            'budget' => $this->resource->budget,
            'customer' => UserResource::make($this->whenLoaded('customer')),
            'service' => ServiceResource::make($this->whenLoaded('service')),
            'proposals' => ProposalResource::collection($this->whenLoaded('proposals')),
        ];
    }
}
