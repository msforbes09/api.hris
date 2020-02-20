<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SmsTemplateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $templateId = $this->template ? $this->template->id : '';

        return [
            'name' => 'required|unique:sms_templates,name,'. $templateId . ',id',
            'message' => 'required'
        ];
    }
}
