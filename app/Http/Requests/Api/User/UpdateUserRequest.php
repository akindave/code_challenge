<?php

namespace App\Http\Requests\Api\User;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'email' => [
                'sometimes',
                'string',
                'email',
                'max:255',
                'unique:users,email,'.$this->route('user')->id
            ],
            'password' => ['sometimes', 'string', 'confirmed', Password::default()],
            'role' => ['sometimes', 'in:user,approver,admin'],
            'department_id' => ['nullable', 'exists:departments,id'],
        ];
    }
}