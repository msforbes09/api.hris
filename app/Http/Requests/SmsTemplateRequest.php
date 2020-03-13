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
        $templateId = $this->sms_template ? $this->sms_template->id : '';

        return [
            'name' => 'required|max:100|unique:sms_templates,name,'. $templateId . ',id',
            'message' => 'required|max:160'
        ];
    }
}
