<?php

namespace App\Api\Transformers;

use App\Models\Auditions;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class AuditionTransformer extends  TransformerAbstract
{
	public function transform(Auditions $auditions)
    {
        //$auditions->load('review');
        return [
            'id' => (int) $auditions->id,
            'name' => $auditions->audition_name,
            'title' => $auditions->title,
            'deadline' => $auditions->deadline,
            'location'=>$auditions->location,
            'audition_fee' => $auditions->audition_fee,
            'participated'=>$auditions->participated,
            'product_id'=>$auditions->product_id,
            'product_price'=>$auditions->product_price,
        ];
    }
}