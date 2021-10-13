<?php

namespace App\Api\Transformers;

use App\Models\Challenges;
use App\Models\ChallengesParticipant;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class CParticipantTransformer extends  TransformerAbstract
{
	public function transform(ChallengesParticipant $challengelist)
    {
        //$auditions->load('review');
        return [
            'participant_id' => (int) $challengelist->id,
            'payment_status' => ($challengelist->payment_status == 1) ? 'paid' : 'pending',
            'challenge_name' => ($challengelist->challenges ? $challengelist->challenges->challenges_name : ''),
            'video_link' => ($challengelist->review ? (($challengelist->review->review_url != '') ? url('/') . config('video.completed_review_path').$challengelist->review->review_url : url('/') . config('video.challenge_video_path').$challengelist->video_link) : '') ,
            'resume'=>'',
            'reviewed_video_url'=>($challengelist->review ? (($challengelist->review->review_url != '') ? url('/') . config('video.completed_review_path').$challengelist->review->review_url:'') : ''),
            'video_thumb'=>($challengelist->thumbnail_url) ? url('/').'/user_videos/thumbnails/'.$challengelist->thumbnail_url : '',
            'performance_level'=>$challengelist->performance_levels,
            'feedback_summary'=> ($challengelist->review ? strip_tags($challengelist->review->feedback) : ''),
            'additional_tips'=> ($challengelist->review ? strip_tags($challengelist->review->additional_tips) : ''),
            'scores' => [
                'performance_quality' => ['rating' => ($challengelist->review ? $challengelist->review->performance_quality_rating : ''), 'comment' => ($challengelist->review ? strip_tags($challengelist->review->performance_quality) : '')],
                'technical_ability' => ['rating' => ($challengelist->review ? $challengelist->review->technical_ability_rating : ''), 'comment' => ($challengelist->review ? strip_tags($challengelist->review->technical_ability) : '') ],
                'energy_style' => ['rating' => ($challengelist->review ? $challengelist->review->energy_style_rating : ''), 'comment' => ($challengelist->review ? strip_tags($challengelist->review->energy_style) : '') ],
                'storytelling' => ['rating' => ($challengelist->review ? $challengelist->review->storytelling_rating : ''), 'comment' => ($challengelist->review ? strip_tags($challengelist->review->storytelling) : '') ],
                'look_appearance' => ['rating' => ($challengelist->review ? $challengelist->review->look_appearance_rating : ''), 'comment' => ($challengelist->review ? strip_tags($challengelist->review->look_appearance) : '')],
                
                
            ],
            'overall_rating'  => round(($challengelist->review ? (
                    $challengelist->review->performance_quality_rating +
                    $challengelist->review->technical_ability_rating +
                    $challengelist->review->energy_style_rating +
                    $challengelist->review->storytelling_rating +
                    $challengelist->review->look_appearance_rating
                    
                ) : 0) / 5, 1),
        ];
    }
}