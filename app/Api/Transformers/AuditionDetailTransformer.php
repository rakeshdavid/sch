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
            'audition_fee_tax' =>$audition->audition_fee_tax,
            'audition_fee_total' =>$audition->audition_fee_total,
            'participated'=>$audition->participated,
            'package_detail'=>strip_tags($audition->audition_detail),
            'auditon_description'=>strip_tags($audition->description),
            'audition_requirement'=>strip_tags($audition->requirement),
            'talent' => $audition->talent,
            'level' => $audition->level,
            'header_image' => ($audition->header_image) ? url('/uploads/auditions/'.$audition->header_image) :'',
            'agency_logo' => ($audition->logo) ? url('/uploads/auditions/'.$audition->logo) : '',
            'product_id'=>$audition->product_id,
        ];
    }
}