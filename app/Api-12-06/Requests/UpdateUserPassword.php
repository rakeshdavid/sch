<?php

namespace App\Api\Requests;

use Dingo\Api\Http\FormRequest;

class UpdateUserPassword extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function messages()
    {
        return [
            //
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'password'   => 'required|min:6|confirmed',
            'password_old'   => 'required',
        ];
    }
}
