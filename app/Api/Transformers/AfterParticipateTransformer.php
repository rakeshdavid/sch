<?php

namespace App\Api\Transformers;

use App\Models\Auditions;
use App\Models\AuditionList;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class AfterParticipateTransformer extends  TransformerAbstract
{
	public function transform(AuditionList $participant)
    {
        //$auditions->load('review');
        return [
            'participant_id' => (int) $participant->id,
            'audition_id' => $participant->audition->id,
            'payment_status' => ($participant->payment_status == 1) ? 'paid' : 'pending',
            'audition_name' => $participant->audition->audition_name,
            'audition_title' => $participant->audition->title,
            'audition_fee' => $participant->audition->audition_fee,
            'package_detail' => $participant->audition->audition_detail,
            'agency_logo' => ($participant->audition->logo) ? url('/') . config('video.audition_video_path').$participant->audition->logo : '',
            'video_thumb'=>($participant->thumbnail_url) ? url('/').'/user_videos/thumbnails/'.$participant->thumbnail_url : '',
            'status_code'=>200,
            'audition_price'=>$participant->aud_price,
            'audition_price_id'=>$participant->aud_product_id,
        ];
    }
}