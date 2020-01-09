<?php

namespace App\Http\Requests;

use App\Rules\ClientCompanyCode;
use Illuminate\Foundation\Http\FormRequest;

class ClientRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'company_id' => 'required|exists:companies,id',
            'code' => ['required', new ClientCompanyCode($this->client, request())],
            'name' => 'required'
        ];
    }
}
