<?php

namespace App\Http\Requests;

use App\Keyword;
use App\Rules\UniqueColumns;
use Illuminate\Foundation\Http\FormRequest;

class KeywordRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'key' => 'sometimes|exists:keys,id',
            'value' => [
                'required',
                new UniqueColumns(
                    $this->keyword ?? new Keyword, [
                        ['name' => 'value', 'value' => request('value')],
                        ['name' => 'key_id', 'value' => request('key') ?? $this->keyword->key_id]
                    ]
                )
            ]

        ];
    }
}
