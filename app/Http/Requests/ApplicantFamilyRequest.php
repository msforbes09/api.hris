<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApplicantFamilyRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'relationship' => 'required|max:20',
            'name' => 'required|max:191',
            'address' => 'max:191',
            'occupation' => 'max:50',
            'birth_date' => 'date|nullable',
            'living' => 'sometimes|boolean',
            'contact_no' => 'sometimes|regex:/^\d{11}$/',
            'emergency_contact' => 'sometimes|boolean',
        ];
    }
}
