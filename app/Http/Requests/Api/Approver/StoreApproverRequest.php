<?php

namespace App\Http\Requests\Api\Approver;

use App\Models\Department;
use Illuminate\Foundation\Http\FormRequest;

class StoreApproverRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => [
                'required',
                'exists:users,id',
                'unique:department_approvers,user_id,NULL,id,department_id,'.$this->route('department')->id
            ],
            'approval_level_id' => [
                'required',
                'exists:approval_levels,id',
                'unique:department_approvers,approval_level_id,NULL,id,department_id,'.$this->route('department')->id
            ],
        ];
    }
}