<?php

declare(strict_types=1);

namespace App\Http\Requests\Mission;

use App\Enums\MissionStatus;
use App\Enums\MissionType;
use App\Models\Service;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class CreateMissionRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'location' => ['required', 'string', 'max:255'],
            'published' => ['required', 'boolean'],
            'type' => ['required', new Enum(MissionType::class)],
            'budget' => ['required', 'string'],
            'service_id' => ['required', 'uuid', Rule::exists(Service::class, 'id')],
        ];
    }

    public function status(): MissionStatus
    {
        if ($this->boolean('published')) {
            return MissionStatus::LIVE;
        }

        return MissionStatus::PENDING;
    }
}
