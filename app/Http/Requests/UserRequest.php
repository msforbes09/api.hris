<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $user_id = $this->user ?? '';

        return [
            'name' => 'required',
            'username' => 'required|unique:users,username,' . $user_id . ',id',
            'email' => 'required|email|unique:users,email,' . $user_id . ',id',
            'password' => 'required|min:8|confirmed',
            'user_type_id' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'user_type_id.required' => 'User type field is required.'
        ];
    }
}
