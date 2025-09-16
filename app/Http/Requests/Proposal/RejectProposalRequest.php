<?php

declare(strict_types=1);

namespace App\Http\Requests\Proposal;

use App\Enums\ProposalRejectionReason;
use App\Models\Mission;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class RejectProposalRequest extends FormRequest
{
    public function authorize(): bool
    {
        /** @var Mission $mission */
        $mission = $this->route('mission');

        /** @var User $user */
        $user = $this->user();

        return $mission->customer_id === $user->id;
    }

    public function rules(): array
    {
        return [
            'rejection_reason' => ['nullable', new Enum(ProposalRejectionReason::class)],
            'rejection_message' => ['nullable', 'string'],
        ];
    }
}
