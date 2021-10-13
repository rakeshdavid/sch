<?php

namespace App\Api\Requests;

use Dingo\Api\Http\FormRequest;
use Illuminate\Http\Request;

class AuditionUpload extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function messages()
    {
        return [
            'video_file.file' => 'The field must be a successfully uploaded file.',
            'video_file.mimetypes' => 'The file must match one of the given MIME types(video/avi,video/mpeg,video/mp4,video/wmv,video/quicktime)',
            'video_type' =>'Required must be file or youtube.',
            'resume'=> 'Required must be pdf file.',
            'audition_id' =>'Required must be audition id integer.'
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
            // 'title' => 'required|max:255',
            // 'description' => 'required|max:255',
            //'video_file' => ['required', 'file', 'mimetypes:video/avi,video/mpeg,video/quicktime,video/wmv,video/mp4,video/webm', 'max:300000'],
            'video_type'=>['required'],
            'resume'=>['required','file','mimes:pdf,jpeg,jpg,png,doc,docx','max:1000000'],
            'audition_id'=>['required']
        ];
    }
}
