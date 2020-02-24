<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class SmsRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => 'required|max:50',
            'message' => 'required|max:160',
            'schedule' => 'date|after:' . Carbon::now(),
            'contacts' => 'required'
        ];
    }
}
