<?php

namespace App\Api\Requests;

use Dingo\Api\Http\FormRequest;

class RegisterUser extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function messages()
    {
        return [
            'phone' => 'Phone should contain a valid phone number.',
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
            //'last_name'  => 'required|max:255',
            'first_name' => 'required|max:255',
            'email'      => 'required|email|max:255|unique:users',
            //'phone'      => 'required|phone|max:255',
            'password'   => 'required|min:6',
        ];
    }
}
