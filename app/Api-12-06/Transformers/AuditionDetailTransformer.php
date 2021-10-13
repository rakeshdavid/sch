<?php

namespace App\Api\Transformers;

use App\Models\Auditions;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class AuditionDetailTransformer extends  TransformerAbstract
{
	public function transform(Auditions $audition)
    {
        //$auditions->load('review');
        return [
            'id' => (int) $audition->id,
            'agency_id'=>$audition->agency_id,
            'name' => $audition->audition_name,
            'title' => $audition->title,
            'deadline' => $audition->deadline,
            'location'=>$audition->location,
            'audition_fee' => $audition->audition_fee,
            'participated'=>$audition->participated,
            'package_detail'=>$audition->audition_detail,
            'auditon_description'=>$audition->description,
            'audition_requirement'=>$audition->requirement,
            'talent' => $audition->talent,
            'level' => $audition->level,
            'header_image' => ($audition->header_image) ? url('/') . config('video.audition_video_path').$audition->header_image :'',
            'agency_logo' => ($audition->logo) ? url('/') . config('video.audition_video_path').$audition->logo : '',
            'product_id'=>$audition->product_id,
            'product_price'=>$audition->product_price,
        ];
    }
}