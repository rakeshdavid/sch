<?php

namespace App\Api\Requests;

use Dingo\Api\Http\FormRequest;

class PayAuditionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function messages()
    {
        return [
           'participant_id.exists' => 'invalid video id'
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
            'source' => 'required_without:customer',
            'participant_id' => 'required|exists:audition_participant,id',
        ];
    }
}
