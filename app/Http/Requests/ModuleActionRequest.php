<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ModuleActionRequest extends FormRequest
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
        $moduleActionId = $this->module_action ?? '';

        return [
            'code' => 'required|unique:module_actions,code,' . $moduleActionId . ',id',
            'name' => 'required'
        ];
    }
}
