<?php

declare(strict_types=1);

namespace App\Http\Requests\Proposal;

use App\Enums\PricingUnit;
use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class CreateProposalRequest extends FormRequest
{
    public function authorize(): bool
    {
        /** @var User $user */
        $user = $this->user();

        return $user->role === UserRole::AGENT;
    }

    public function rules(): array
    {
        return [
            'agent_message' => ['nullable', 'string'],
            'price' => ['nullable', 'integer', 'min:1'],
            'pricing_unit' => ['required_with:price', new Enum(PricingUnit::class)],
        ];
    }
}
