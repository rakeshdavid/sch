<?php

namespace App\Api\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\User;

class CoachesTransformer extends  TransformerAbstract
{
    /**
     * Turn User object into a generic array.
     *
     * @param User $coach
     * @return array
     */
    public function transform(User $coach)
    {
        $coach->load('activity_genres');
        $coach->load('performance_levels');
        return [
            'id' => (int) $coach->id,
            'first_name' => $coach->first_name,
            'last_name' => $coach->last_name,
            'title'=>$coach->title,
            'avatar' => preg_match('/^(http:\/\/|https:\/\/)/', $coach->avatar) ?  $coach->avatar : url($coach->avatar),
            'genres' => $coach->activity_genres->reduce(function ($carry, $genre) {
                $carry[] = [
                    'id' => $genre->id,
                    'name' => $genre->name,
                ];
                return $carry;
            }, []),
            'levels' => $coach->performance_levels->reduce(function ($carry, $level) {
                $carry[] = [
                    'id' => $level->id,
                    'name' => $level->name,
                ];
                return $carry;
            }, []),
            'price_detailed'=>$coach->price_detailed,
            'price_summary'=>$coach->price_summary,
            'vacation_start'=>$coach->vacation_start,
            'vacation_end'=>$coach->vacation_end,
            'audition_product_id' => $coach->audition_product_id,
            'audition_product_price' =>  $coach->audition_product_price,
            'comp_product_id' => $coach->comp_product_id,
            'comp_product_price' => $coach->comp_product_price
        ];
    } 
}
