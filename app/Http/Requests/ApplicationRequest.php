<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApplicationRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'applicant_id' => 'sometimes|required|exists:applicants,id',
            'branch_id' => 'required|exists:branches,id',
            'client_id' => 'required|exists:clients,id',
            'client_branch_id' => 'required|exists:client_branches,id',
            'client_position_id' => 'required|exists:client_positions,id'
        ];
    }
}
