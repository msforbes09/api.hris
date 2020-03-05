<?php

namespace App\Http\Requests;

use App\Client;
use App\Rules\UniqueColumns;
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
            'code' => ['required', 'max:10', new UniqueColumns(
                $this->client ?? new Client, [
                    ['name' => 'code', 'value' => request('code')],
                    ['name' => 'company_id', 'value' => request('company_id')]
                ],
                true
            )],
            'name' => 'required|max:100'
        ];
    }
}
