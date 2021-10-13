<?php

namespace App\Api\Transformers;

use App\Models\Challenges;
use App\Models\ChallengesParticipant;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class AfterCParticipateTransformer extends  TransformerAbstract
{
	public function transform(ChallengesParticipant $participant)
    {
        //$auditions->load('review');
        return [
            'participant_id' => (int) $participant->id,
            'challenge_id' => ($participant->challenges ? $participant->challenges->id : ''),
            'payment_status' => ($participant->payment_status == 1) ? 'paid' : 'pending',
            'challenge_name' => ($participant->challenges ? $participant->challenges->challenges_name : ''),
            'challenge_title' => ($participant->challenges ? $participant->challenges->title : ''),
            'challenge_fee' => ($participant->challenges ? $participant->challenges->challenges_fee : ''),
            'package_detail' => ($participant->challenges ? strip_tags($participant->challenges->challenges_detail) : ''),
            'logo' => ($participant->challenges ? (($participant->challenges->logo) ? env('COACH_PLATFORM_LINK').'/uploads/challenges/'.$participant->challenges->logo : '') : ''),
            'video_thumb'=>($participant->thumbnail_url) ? url('/').'/user_videos/thumbnails/'.$participant->thumbnail_url : '',
            'status_code'=>200,
            'challenge_price'=>$participant->comp_price,
            'challenge_price_id'=>$participant->comp_product_id,
        ];
    }
}