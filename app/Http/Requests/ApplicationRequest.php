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
            'applicant_id' => 'sometimes|required',
            'client_id' => 'required',
            'client_branch_id' => 'required',
            'client_position_id' => 'required'
        ];
    }
}
