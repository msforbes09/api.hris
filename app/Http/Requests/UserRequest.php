<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $userId = $this->user ? $this->user->id : '';

        return [
            'name' => 'required|max:30',
            'username' => 'required|max:30|unique:users,username,' . $userId . ',id',
            'email' => 'required|email|max:30|unique:users,email,' . $userId . ',id',
            'user_type_id' => 'required|exists:user_types,id',
            'branch_id' => 'required|exists:branches,id',
            'active' => 'sometimes|boolean'
        ];
    }

    public function messages()
    {
        return [
            'user_type_id.required' => 'User type field is required.'
        ];
    }
}
