<?php

namespace App\Http\Requests\Api\Department;

use App\Models\Department;
use Illuminate\Foundation\Http\FormRequest;

class UpdateDepartmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'sometimes',
                'string',
                'max:255',
                'unique:departments,name,'.$this->route('department')->id
            ],
            'description' => ['nullable', 'string', 'max:500'],
        ];
    }
}