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
            'challenge_id' => $participant->challenges->id,
            'payment_status' => ($participant->payment_status == 1) ? 'paid' : 'pending',
            'challenge_name' => $participant->challenges->challenges_name,
            'challenge_title' => $participant->challenges->title,
            'challenge_fee' => $participant->challenges->challenges_fee,
            'package_detail' => $participant->challenges->challenges_detail,
            'logo' => ($participant->challenges->logo) ? url('/') . config('video.challenge_video_path').$participant->challenge->logo : '',
            
        ];
    }
}