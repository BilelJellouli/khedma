<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Http\Resources\Agent\ReducedAgentResource;
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
            'agent' => ReducedAgentResource::make($this->whenLoaded('agent')),
        ];
    }
}
