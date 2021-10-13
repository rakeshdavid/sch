<?php

namespace App\Api\Requests;

use Dingo\Api\Http\FormRequest;

class StoreVideo extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function messages()
    {
        return [
            'phone' => 'Phone should contain a valid phone number.',
            'question.*' => 'The question may not be greater than 255 characters',
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
            'coach' => 'required',
            'name' => 'required|max:255',
            // 'about' => 'required|max:255',
            // 'other_site_spec' => 'max:255',
            'url' => 'required',
            'price'=>'required',
            // 'description' => 'required|min:6|max:255',
            // 'genres.*' => 'exists:activity_genres,id',
            // 'level' => 'required',
            'activity_experience' => 'required|integer|min:0|max:100',
            'seeking_auditions' => 'required|in:yes,no,maybe,not_yet'
            // 'question.*' => 'max:255'
        ];
    }
}
