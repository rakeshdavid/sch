<?php

namespace App\Api\Requests;

use Dingo\Api\Http\FormRequest;

class ApplePayVideoRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function messages()
    {
        return [
           'video_id.exists' => 'invalid video id'
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
           
            'video_id' => 'required|exists:videos,id',
            'amount'=>'required',
            'transaction_id'=>'required',
            'status'=>'required',
            
        ];
    }
}
