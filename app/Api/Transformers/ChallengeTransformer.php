<?php

namespace App\Api\Transformers;

use App\Models\Challenges;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class ChallengeTransformer extends  TransformerAbstract
{
	public function transform(Challenges $challenges)
    {
        //$auditions->load('review');
        return [
            'id' => (int) $challenges->id,
            'name' => $challenges->challenges_name,
            'title' => $challenges->title,
            'deadline' => $challenges->deadline,
            'prize'=>$challenges->gift,
            'additional_prize'=>$challenges->additional_gift,
            'challenge_fee' => $challenges->challenges_fee,
            'challenge_fee_tax' => $challenges->challenges_fee_tax,
            'challenge_fee_total' => $challenges->challenges_fee_total,
            'participated'=>$challenges->participated,
            'short_desc'=>$challenges->short_desc,
            'product_id'=>$challenges->product_id,
            'product_price'=>$challenges->product_price,
        ];
    }
}