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
            'relationship' => 'required',
            'name' => 'required',
            'occupation' => 'required',
            'address' => 'required',
            'birth_date' => 'required',
        ];
    }
}
