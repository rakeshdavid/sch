<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class CoachChallengeUpdateRequest extends Request
{
    /**
     * Get the validation attributes names
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'challenge-name'     => 'Challenge Name',
            'title'      => "Title",
            'challenge-fee'          => 'Challenge fee',
            'challenge-deadline'          => 'Challenge deadline',
            'short-desc'  => 'Short Description',
            //'challenge-detail'       => 'Challenge detail',
            'challenge-description' => 'Challenge description',
            "challenge-requirement" =>"Challenge requirement",
            "gift" =>"Gift",
            "additional-gift" =>"Additional gift"
        ];
    }

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
        return [
            'challenge-name'       => 'required|string|min:2|max:255',
            'title'        => 'required|string|min:2|max:255',
            'challenge-fee'            => 'required|integer',
            'challenge-deadline'    => 'required',
            'short-desc'           => 'required',
            //'challenge-detail'         => 'required',
            'challenge-description'   => 'required',
            'challenge-requirement'         => 'required',
            'gift'    => 'required',
            'additional-gift'            => 'required',
            //'logo'            => '|mimetypes:image/gif,image/png,image/jpg,image/jpeg,|max:2048',
            //'header_image'    =>'mimetypes:image/gif,image/png,image/jpg,image/jpeg,|max:2048',
            
        ];
    }

    public function messages()
    {
        return [
            
        ];
    }
}
