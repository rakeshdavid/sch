<?php

namespace App\Api\Transformers;

use App\Models\Challenges;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class ChallengeDetailTransformer extends  TransformerAbstract
{
	public function transform(Challenges $challenge)
    {
        //$challenges->load('review');
        return [
            'id' => (int) $challenge->id,
            'coach_id'=>$challenge->coach_id,
            'name' => $challenge->challenges_name,
            'title' => $challenge->title,
            'deadline' => $challenge->deadline,
            'prize'=>$challenge->gift,
            'additional_prize'=>$challenge->additional_gift,
            'challenge_fee' => $challenge->challenges_fee,
            'challenge_fee_tax' => $challenge->challenges_fee_tax,
            'challenge_fee_total' => $challenge->challenges_fee_total,
            'participated'=>$challenge->participated,
            'package_detail'=>strip_tags($challenge->challenges_detail),
            'challenge_description'=>strip_tags($challenge->description),
            'challenge_requirement'=>strip_tags($challenge->requirement),
            'header_image' => ($challenge->header_image) ? url('/uploads/challenge/'.$challenge->header_image) :'',
            'logo' => ($challenge->logo) ? url('/uploads/challenge/'.$challenge->logo) : '',
            'product_id'=>$challenge->product_id,
            'product_price'=>$challenge->product_price,
        ];
    }
}