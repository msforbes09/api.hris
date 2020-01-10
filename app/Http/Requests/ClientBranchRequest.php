<?php

namespace App\Http\Requests;

use App\Rules\ClientBranchCode;
use Illuminate\Foundation\Http\FormRequest;

class ClientBranchRequest extends FormRequest
{
   public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'code' => ['required', new ClientBranchCode($this->branch, request())],
            'name' => 'required'
        ];
    }
}
