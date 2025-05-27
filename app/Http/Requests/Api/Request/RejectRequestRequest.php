<?php

namespace App\Http\Requests\Api\Request;

use Illuminate\Foundation\Http\FormRequest;

class RejectRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('approve', $this->route('request'));
    }

    public function rules(): array
    {
        return [
            'comments' => ['required', 'string', 'max:500'],
        ];
    }
}