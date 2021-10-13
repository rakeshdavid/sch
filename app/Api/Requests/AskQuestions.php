<?php

namespace App\Api\Requests;

use Dingo\Api\Http\FormRequest;

class AskQuestions extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function messages()
    {
        return [
            'video_id' => 'Video Id is required.',
            'question' => 'Coach Id for video is ',
            'package_type' => 'Package type should be like price_detailed or price_summary.',
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
            
            'video_id' => 'required|max:255',
            'question'      => 'required',
           
        ];
    }
}
