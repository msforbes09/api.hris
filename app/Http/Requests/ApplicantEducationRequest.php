<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApplicantEducationRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'level' => 'required|max:30',
            'school' => 'required|max:30',
            'year_from' => 'required|digits:4',
            'year_to' => 'required|digits:4',
        ];
    }
}
