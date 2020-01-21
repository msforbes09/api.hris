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
            'company' => 'required',
            'address' => 'required',
            'date_from' => 'required|digits:4',
            'date_to' => 'required|digits:4',
            'position' => 'required',
            'salary' => 'required|numeric',
            'leaving_reason' => 'required',
        ];
    }
}
