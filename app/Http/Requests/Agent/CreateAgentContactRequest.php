<?php

namespace App\Http\Requests\Agent;

use App\Enums\AgentContactType;
use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class CreateAgentContactRequest extends FormRequest
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
            'type' => ['required', new Enum(AgentContactType::class)],
            'value' => ['required', 'string'],
            'is_primary' => ['boolean'],
        ];
    }
}
