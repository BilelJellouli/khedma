<?php

declare(strict_types=1);

namespace App\Http\Requests\Agent;

use App\Enums\AgentAvailability;
use App\Enums\UserRole;
use App\Models\Agent;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class CreateAgentRequest extends FormRequest
{
    public function authorize(): bool
    {
        /** @var User $user */
        $user = $this->user();

        if ($user->role !== UserRole::AGENT) {
            return false;
        }

        return Agent::where('user_id', $user->id)->doesntExist();
    }

    public function rules(): array
    {
        return [
            'bio' => ['nullable', 'string'],
            'experience' => ['nullable', 'string'],
            'skills' => ['nullable', 'array'],
            'skills.*' => ['string'],
            'availability' => ['required', new Enum(AgentAvailability::class)],
            'services' => ['required', 'array', 'min:1', 'max:5'],
            'services.*' => ['required', 'uuid', Rule::exists(Service::class, 'id')],
        ];
    }

    public function services(): Collection
    {
        return Service::query()
            ->whereIn('id', $this->input('services'))
            ->get();
    }
}
