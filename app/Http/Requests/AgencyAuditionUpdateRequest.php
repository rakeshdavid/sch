<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class AgencyAuditionUpdateRequest extends Request
{
    /**
     * Get the validation attributes names
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'audition-name'     => 'Audition Name',
            'title'      => "Title",
            'audition-fee'          => 'Audition fee',
            'audition-deadline'          => 'Audition deadline',
            'audition-location'  => 'Audition location',
            
            'audition-description' => 'Audition description',
            "audition-requirement" =>"Audition requirement",
            "audition-genres" =>"Audition genres",
            "level" =>"Level"
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
            'audition-name'       => 'required|string|min:2|max:255',
            'title'        => 'required|string|min:2|max:255',
            'audition-fee'            => 'required',
            'audition-deadline'    => 'required',
            'audition-location'           => 'required',
           
            'audition-description'   => 'required',
            'audition-requirement'         => 'required',
            'audition-genres'    => 'required',
            'level'            => 'required',
            'logo'            => 'mimetypes:image/gif,image/png,image/jpg,image/jpeg,|max:2048',
            'header_image'    =>'mimetypes:image/gif,image/png,image/jpg,image/jpeg,|max:2048',
            
        ];
    }

    public function messages()
    {
        return [
            
        ];
    }
}
