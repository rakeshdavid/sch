<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class StoreUserRequest extends Request
{
    /**
     * Get the validation attributes names
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'first_name'     => 'First name',
            'last_name'      => 'Last name',
            'title'          => 'Title',
            'email'          => 'Email',
            'contact_email'  => 'Contact email',
            'location'       => 'City',
            'location_state' => 'State',
            'files.*'        => 'Upload files'
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
            'first_name'       => 'required|string|min:2|max:255',
            'last_name'        => 'required|string|min:2|max:255',
            'email'            => 'required|email|unique:users,email|max:255',
            'activity_type'    => /*array|*/'exists:activity_types,id',
            'genres'           => /*required_with:activity_type|*/'array|exists:activity_genres,id',
            'location'         => 'max:255',
            'location_state'   => 'max:255',
            'overview'         => 'max:255',
            'contact_email'    => 'email|max:255',
            'phone'            => 'max:255',
            'wevsites'         => 'max:255',
            'social_links'     => 'max:255'
        ];
    }

    public function messages()
    {
        return [
            'video.regex'                   => 'Wrong video url',
            'documents.*.max'               => 'Upload files cannot be greater than 3 MB',
            'documents.*.mimetypes'         => 'Upload files type must only be of type: pdf',
            'gallery_photos.*.max'          => 'Photos cannot be greater than 2 MB',
            'gallery_photos.*.mimetypes'    => 'Photos type must only be of types: png, jpg, gif',
            'profile_photo.max'             => 'Avatar cannot be greater than 2 MB',
            'profile_photo.mimetypes'       => 'Avatar type must only be of types: png, jpg, gif, jpeg',
            'gallery_video.max'             => 'Video cannot be greater than 250 MB',
            'gallery_video.mimetypes'       => 'Video type must only be of types: avi, mpeg4, wmv, mp4',
        ];
    }
}
