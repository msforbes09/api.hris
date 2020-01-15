<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KeyRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $keyId = $this->key ? $this->key->id : '';

        return [
            'name' => 'required|unique:keys,name,' . $keyId . ',id'
        ];
    }
}
