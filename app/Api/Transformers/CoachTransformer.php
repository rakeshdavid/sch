<?php

namespace App\Api\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\User;

class CoachTransformer extends  TransformerAbstract
{
    /**
     * Turn User object into a generic array.
     *
     * @param User $coach
     * @return array
     */
    public function transform(User $coach)
    {
        $coach->load('activity_genres', 'performance_levels', 'gallery');
        return [
            'id' => (int) $coach->id,
            'first_name' => $coach->first_name,
            'last_name' => $coach->last_name,
            'avatar' => preg_match('/^(http:\/\/|https:\/\/)/', $coach->avatar) ?  $coach->avatar : url($coach->avatar),
            'overview' => strip_tags($coach->about),
            'facebook_link'=>$coach->facebook_link,
            'instagram_link'=>$coach->instagram_link,
            'prices' => [
                'summary' => $coach->price_summary ? $coach->price_summary : intval(env('DEFAULT_SUMMARY_PRICE')),
                'detailed' => $coach->price_detailed ? $coach->price_detailed : intval(env('DEFAULT_SUMMARY_DETAILED')),
                'summary_price_tax'=> $coach->price_summary_tax,
                'detailed_price_tax'=> $coach->price_detailed_tax,
                'price_summary_total'=>$coach->price_summary_total,
                'price_detailed_total'=>$coach->price_detailed_total,
                'audition_product_id' => $coach->audition_product_id,
                'audition_product_price' =>  $coach->audition_product_price,
                'comp_product_id' => $coach->comp_product_id,
                'comp_product_price' => $coach->comp_product_price,
            ],
            'genres' => $coach->activity_genres->reduce(function ($carry, $genre) {
                $carry[] = [
                    'id' => $genre->id,
                    'name' => $genre->name,
                ];
                return $carry;
            }, []),
            'gives_feedback_to' => $coach->performance_levels->reduce(function ($carry, $level) {
                $carry[] = [
                    'id' => $level->id,
                    'name' => $level->name,
                ];
                return $carry;
            }, []),
            'certifications' => collect(explode(';', $coach->certifications))->reduce(function ($carry, $crt) {
                if (!empty($crt)) {
                    $carry[] = $crt;
                }
                return $carry;
            }, []),
            'teaching_positions' => collect(explode(';', $coach->teaching_positions))->reduce(function ($carry, $position) {
                if (!empty($position)) {
                    $carry[] = $position;
                }
                return $carry;
            }, []),
            'performance_credits' => collect(explode(';', $coach->performance_credits))->reduce(function ($carry, $cred) {
                if (!empty($cred)) {
                    $carry[] = $cred;
                }
                return $carry;
            }, []),
            'gallery' => $coach->gallery->reduce(function ($carry, $item) {
                if($item->visible) {
                    $carry[] = [
                        'id' => $item->id,
                        'path' => $item->type === 'image' ? url('/gallery/' . $item->path) : $item->path,
                        'type' => $item->type,
                        'video_thumbnail' => ($item->type === 'video') ? 'https://img.youtube.com/vi/' . $item->path . '/0.jpg' : ''
                    ];
                }
                return $carry;
            }, []),
        ];
    }
}
