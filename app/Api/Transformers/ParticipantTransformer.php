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
            'video_link' => ($auditionlist->auditionreviewnew->review_url != '') ? url('/') . config('video.completed_review_path').$auditionlist->auditionreviewnew->review_url : url('/') . config('video.audition_video_path').$auditionlist->video_link,
            'resume'=>($auditionlist->resume) ? url('/') . config('video.audition_resume_path').$auditionlist->resume : '',
            'video_thumb'=>($auditionlist->thumbnail_url) ? url('/').'/user_videos/thumbnails/'.$auditionlist->thumbnail_url : '',
            'reviewed_video_url'=>($auditionlist->auditionreviewnew->review_url != '') ? url('/') . config('video.completed_review_path').$auditionlist->auditionreviewnew->review_url:'',
            'performance_level'=>$auditionlist->performance_levels,
            'feeback_comment'=>strip_tags($auditionlist->auditionreviewnew->feedback),
            'audition_review' => [
                "performance"=>["rating"=>$auditionlist->auditionreviewnew->performance_quality_rating,"comment"=>strip_tags($auditionlist->auditionreviewnew->performance_quality)],
                "technical"=>["rating"=>$auditionlist->auditionreviewnew->technical_ability_rating,"comment"=>strip_tags($auditionlist->auditionreviewnew->technical_ability)],
                "energy"=>["rating"=>$auditionlist->auditionreviewnew->energy_style_rating,"comment"=>strip_tags($auditionlist->auditionreviewnew->energy_style)],
                "story"=>["rating"=>$auditionlist->auditionreviewnew->storytelling_rating,"comment"=>strip_tags($auditionlist->auditionreviewnew->storytelling)],
                "look"=>["rating"=>$auditionlist->auditionreviewnew->look_appearance_rating,"comment"=>strip_tags($auditionlist->auditionreviewnew->look_appearance)],
            ],
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