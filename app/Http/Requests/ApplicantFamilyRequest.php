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
            'birth_date' => 'date|nullable',
            'living' => 'required|boolean',
        ];
    }
}
