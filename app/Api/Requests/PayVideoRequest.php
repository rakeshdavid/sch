<?php

namespace App\Api\Requests;

use Dingo\Api\Http\FormRequest;

class PayVideoRequest extends FormRequest
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
            'source' => 'required_without:customer',
            'video_id' => 'required|exists:videos,id',
        ];
    }
}
