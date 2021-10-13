<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class StoreCoachRequest extends Request
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
            'files.*'         => 'Upload files'
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
        $rules =  [
            'first_name'            => 'required|string|min:2|max:255',
            'last_name'             => 'required|string|min:2|max:255',
            'title'                 => 'max:255',
            'email'                 => 'required|email|unique:users,email|max:255',
            'activity_type'         => /*array|*/'exists:activity_types,id',
            'genres'                => /*required_with:activity_type|*/'array|exists:activity_genres,id',
            'location'              => 'max:255',
            'location_state'        => 'max:255',
            //'overview'              => 'max:255',
            'certifications'        => 'max:255',
            'teaching_positions'    => 'max:255',
            'performance_credits'   => 'max:255',
            'performance_levels'    => 'array|exists:performance_levels,id',
            'contact_email'         => 'email|max:255',
            'phone'                 => 'max:255',
            'wevsites'              => 'max:255',
            'social_links'          => 'max:255',
            'other_site_spec'       => 'max:255',
            'coachs_site'           => 'max:255',
            'vacation_start'        => 'date_format:"Y-m-d"',
            'vacation_end'          => 'date_format:"Y-m-d"|after:vacation_start',
            'price_summary'         => 'max:255',
            'price_detailed'        => 'max:255',
            'profile_photo'         => 'mimetypes:image/gif,image/png,image/jpg,image/jpeg,|max:2048',
            'video'                 => ['max:255', 'regex:/^(http(s)??\:\/\/)?(www\.)?((youtube\.com\/watch\?v=)|(youtu.be\/))([a-zA-Z0-9\-_])+/'],
            'gallery_video'         => 'mimetypes:video/avi,video/mpeg4,video/wmv,video/mp4|max:524288'
        ];
        //if($this->file('documents'))
            $nbr = count($this->file('documents')) - 1;
            foreach(range(0, $nbr) as $index) {
                $rules['documents.' . $index] = 'mimetypes:application/pdf|max:3072';
            }
        //endif;
        //if($this->file('gallery_photos')):
            $gallery_photos_nbr = count($this->file('gallery_photos')) - 1;
            foreach(range(0, $gallery_photos_nbr) as $gph) {
                $rules['gallery_photos.' . $gph] = 'mimetypes:image/gif,image/png,image/jpg,image/jpeg|max:2048';
            }
        //endif;
        if($this->file('gallery_videos')):
            $gallery_videos_nbr = count($this->file('gallery_videos')) - 1;
            foreach(range(0, $gallery_videos_nbr) as $gvid) {
                $rules['gallery_video.' . $gvid] = 'mimetypes:video/avi,video/mpeg4,video/wmv,video/mp4|max:256000';
            }
        endif;
        return $rules;
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
