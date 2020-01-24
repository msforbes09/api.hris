<?php

namespace App\Http\Requests;

use App\ClientBranch;
use App\Rules\UniqueColumns;
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
            'code' => ['required', 'max:10', new UniqueColumns(
                $this->branch ?? new ClientBranch, [
                    ['name' => 'code', 'value' => request('code')],
                    ['name' => 'client_id', 'value' => $this->client->id]
                ]
            )],
            'name' => 'required|max:100'
        ];
    }
}
