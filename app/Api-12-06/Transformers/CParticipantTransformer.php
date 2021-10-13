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
            'challenge_name' => $challengelist->challenges->challenges_name,
            'video_link' => ($challengelist->video_type == 'file') ? url('/') . config('video.challenge_video_path').$challengelist->video_link : $challengelist->video_link,
            'resume'=>($challengelist->resume) ? url('/') . config('video.challenge_resume_path').$challengelist->resume : '',
            'scores' => [
                'timing' => ['rating' => $challengelist->review->timing, 'comment' => $challengelist->review->timing_comment],
                'footwork' => ['rating' => $challengelist->review->footwork, 'comment' => $challengelist->review->footwork_comment],
                'alignment' => ['rating' => $challengelist->review->alingment, 'comment' => $challengelist->review->alingment_comment],
                'balance' => ['rating' => $challengelist->review->balance, 'comment' => $challengelist->review->balance_comment],
                'focus' => ['rating' => $challengelist->review->focus, 'comment' => $challengelist->review->focus_comment],
                'precision' => ['rating' => $challengelist->review->precision, 'comment' => $challengelist->review->precision_comment],
                'energy' => ['rating' => $challengelist->review->energy, 'comment' => $challengelist->review->energy_comment],
                'style' => ['rating' => $challengelist->review->style, 'comment' => $challengelist->review->style_comment],
                'creativity' => ['rating' => $challengelist->review->creativity, 'comment' => $challengelist->review->creativity_comment],
                'interpretation' => ['rating' => $challengelist->review->interpretation, 'comment' => $challengelist->review->interpretation_comment],
                'formation' => ['rating' => $challengelist->review->formation, 'comment' => $challengelist->review->formation_comment],
                'artistry' => ['rating' => $challengelist->review->artisty, 'comment' => $challengelist->review->artisty_comment],
                'feedback_summary'=>['rating'=>0,'comment'=>$challengelist->review->feedback_summary],
                'additional_tips'=>['rating'=>0,'comment'=>$challengelist->review->additional_tips],
            ],
            'overall_rating'  => round((
                    $challengelist->review->artisty +
                    $challengelist->review->formation +
                    $challengelist->review->interpretation +
                    $challengelist->review->creativity +
                    $challengelist->review->style +
                    $challengelist->review->energy +
                    $challengelist->review->precision +
                    $challengelist->review->timing +
                    $challengelist->review->footwork +
                    $challengelist->review->alingment +
                    $challengelist->review->balance +
                    $challengelist->review->focus
                ) / 12, 1),
        ];
    }
}