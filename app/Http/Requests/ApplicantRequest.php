<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApplicantRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'last_name' => 'required|max:50',
            'first_name' => 'required|max:50',
            'middle_name' => 'max:50',
            'nick_name' => 'max:50',
            'current_address' => 'max:191',
            'permanent_address' => 'max:191',
            'birth_date' => 'required|date',
            'birth_place' => 'max:191',
            'gender' => 'required|max:10',
            'height' => 'max:20',
            'weight' => 'max:20',
            'civil_status' => 'max:20',
            'tax_code' => 'max:20',
            'citizenship' => 'max:20',
            'religion' => 'max:30',
            'contact_no' => 'regex:/^[\d]*$/|min:7|max:20|nullable',
            'email' => 'email|nullable',
            'crn' => 'regex:/^\d{4}-\d{7}-\d$/|nullable',
            'sss' => 'regex:/^\d{2}-\d{7}-\d$/|nullable',
            'tin' => ['regex:/(^\d{3}-\d{3}-\d{3}$)|(^\d{3}-\d{3}-\d{3}-\d{3}$)/', 'nullable'],
            'philhealth' => 'regex:/^\d{2}-\d{9}-\d$/|nullable',
            'pagibig' => 'regex:/^\d{4}-\d{4}-\d{4}$/|nullable',
            'pagibig_tracking' => 'regex:/^\d{12}$/|nullable',
        ];
    }
}
