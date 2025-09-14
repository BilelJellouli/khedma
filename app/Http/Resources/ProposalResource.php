<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Http\Resources\Agent\MinifiedAgentResource;
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
            'price' => $this->resource->price,
            'pricingUnit' => $this->resource->pricing_unit,
            'status' => $this->resource->status,
            'seenAtByCustomer' => $this->resource->seen_at_by_customer,
            'rejectedReason' => $this->resource->rejection_reason,
            'rejectedMessage' => $this->resource->rejection_message,
            'createdAt' => $this->resource->created_at,
            'updatedAt' => $this->resource->updated_at,
            'agent' => MinifiedAgentResource::make($this->whenLoaded('agent')),
            'mission' => MissionResource::make($this->whenLoaded('mission')),
        ];
    }
}
