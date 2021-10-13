<?php

namespace App\Api\Requests;

use Dingo\Api\Http\FormRequest;

class UpdateUser extends FormRequest
{
    private $requestFields = [
        "first_name",
        "last_name",
        "gender",
        "avatar",
        "activity_type",
        "genres",
        "location",
        "location_state",
        "about",
        "contact_email",
        "phone",
        "website",
        "social_links",
        "birth_date",
        "levels"
    ];

    public function resolvedData()
    {
        $data = $this->only($this->requestFields);
        $modelFields = [
            'website'       => 'wevsites',
            'birth_date'    => 'birthday',
//            'location'      => 'hometown',
//            'location_state'=> 'location',
        ];
        foreach ($data as $key => $val) {
            if (array_key_exists($key, $modelFields)) {
                $data[$modelFields[$key]] = $val;
                array_forget($data, $key);
            }
        }
        return $data;
    }

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
       // $user = app(\Dingo\Api\Auth\Auth::class)->user();
        return [
            'last_name'         => 'required|max:255',
            'first_name'        => 'required|max:255',
          //  'email'             => 'required|email|max:255|unique:users,email,' . $user->id,
            'gender'            => 'required|in:male,female',
            'activity_type'     => 'required|exists:activity_types,id',
            'genres'            => 'required|array|min:1',
            'genres.*'          => 'required|exists:activity_genres,id',
            'location'          => 'required|max:255',
            'location_state'    => 'max:255',
            'about'             => 'required|string',
            'contact_email'     => 'email|max:255',
            'phone'             => 'required|phone',
            'website'           => 'max:255',
            'social_links'      => 'required|max:255',
            'birth_date'        => 'date_format:m/d/Y',
            'levels'             => 'required|array|min:1',
            'levels.*'          => 'required|exists:performance_levels,id',
            'avatar'            => 'image| mimes:jpeg,jpg,png,gif'
        ];
    }
}
