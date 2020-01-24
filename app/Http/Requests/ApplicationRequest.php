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
            'date' => 'required|date',
            'branch_id' => 'required|exists:branches,id',
            'client_id' => 'required|exists:clients,id',
            'client_branch_id' => 'required|exists:client_branches,id',
            'client_position_id' => 'required|exists:client_positions,id',
            'interview_date' => 'nullable|date',
            'interview_stat' => 'required|exists:keywords,value',
            'interview_remarks' => 'max:191',
            'exam_date' => 'nullable|date',
            'exam_stat' => 'required|exists:keywords,value',
            'exam_remarks' => 'max:191',
            'medical_stat' => 'required|exists:keywords,value',
            'medical_remarks' => 'max:191',
            'requirement_stat' => 'required|exists:keywords,value',
            'requirement_remarks' => 'max:191',
            'status' => 'required|exists:keywords,value',
        ];
    }
}
