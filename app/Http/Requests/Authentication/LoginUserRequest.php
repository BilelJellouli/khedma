<?php

namespace App\Http\Requests\Authentication;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LoginUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', Rule::exists(User::class, 'email')->whereNull('banned_at')],
            'password' => ['required', 'string', 'min:8']
        ];
    }
}
