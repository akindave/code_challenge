<?php

namespace App\Http\Requests\Api\Request;

use App\Models\DepartmentApprover;
use Illuminate\Foundation\Http\FormRequest;

class UpdateApproverRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $approver = $this->route('approver');
        
        return [
            'approval_level_id' => [
                'sometimes',
                'exists:approval_levels,id',
                'unique:department_approvers,approval_level_id,'.$approver->id.',id,department_id,'.$approver->department_id
            ],
        ];
    }
}