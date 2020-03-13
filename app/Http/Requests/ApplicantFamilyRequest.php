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
            'relationship' => 'required|max:50',
            'name' => 'required|max:100',
            'address' => 'max:100',
            'occupation' => 'max:100',
            'birth_date' => 'date|nullable',
            'living' => 'sometimes|boolean',
            'contact_no' => 'sometimes|regex:/^\d{11}$/',
            'emergency_contact' => 'sometimes|boolean',
        ];
    }
}
