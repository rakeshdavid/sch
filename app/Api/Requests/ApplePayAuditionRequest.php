<?php

namespace App\Api\Requests;

use Dingo\Api\Http\FormRequest;

class ApplePayAuditionRequest extends FormRequest
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
           
            'participant_id' => 'required|exists:audition_participant,id',
            'amount'=>'required',
            'transaction_id'=>'required',
            'status'=>'required',
        ];
    }
}
