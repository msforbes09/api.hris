<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApplicantEmploymentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'company' => 'required|max:100',
            'address' => 'required|max:100',
            'date_from' => 'required|date',
            'date_to' => 'required|date',
            'position' => 'required|max:100',
            'salary' => 'required|numeric',
            'leaving_reason' => 'required',
        ];
    }
}
