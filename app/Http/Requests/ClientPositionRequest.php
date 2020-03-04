<?php

namespace App\Http\Requests;

use App\ClientPosition;
use App\Rules\UniqueColumns;
use Illuminate\Foundation\Http\FormRequest;

class ClientPositionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'code' => ['required', 'max:10', new UniqueColumns(
                $this->position ?? new ClientPosition, [
                    ['name' => 'code', 'value' => request('code')],
                    ['name' => 'client_id', 'value' => $this->client->id]
                ],
                true
            )],
            'name' => 'required|max:100'
        ];
    }
}
