<?php

namespace App\Http\Resources;

use App\Models\Proposal;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property-read Proposal $resource
 */
class ProposalResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'initiator' => $this->resource->initiator,
            'agent' => AgentResource::make($this->whenLoaded('agent')),
        ];
    }
}
