<?php

namespace App\Api\Transformers;

use App\Models\Auditions;
use App\Models\AuditionList;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class ParticipantTransformer extends  TransformerAbstract
{
	public function transform(AuditionList $auditionlist)
    {
        //$auditions->load('review');
        return [
            'id' => (int) $auditionlist->id,
            'payment_status' => ($auditionlist->payment_status == 1) ? 'paid' : 'pending',
            'audition_name' => $auditionlist->audition->audition_name,
            'video_link' => ($auditionlist->video_type == 'file') ? url('/') . config('video.audition_video_path').$auditionlist->video_link : $auditionlist->video_link,
            'resume'=>($auditionlist->resume) ? url('/') . config('video.audition_resume_path').$auditionlist->resume : '',
            'audition_review' => $auditionlist->auditionreviewnew,
            'overall_rating'  => ($auditionlist->auditionreviewnew) ? round((
                    $auditionlist->auditionreviewnew->performance_quality_rating +
                    $auditionlist->auditionreviewnew->technical_ability_rating +
                    $auditionlist->auditionreviewnew->energy_style_rating +
                    $auditionlist->auditionreviewnew->storytelling_rating +
                    $auditionlist->auditionreviewnew->look_appearance_rating 
                ) / 5, 1) : 0,
        ];
    }
}