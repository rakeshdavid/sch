<?php

namespace App\Api\Transformers;

use App\Models\Video;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class VideoTransformer extends  TransformerAbstract
{
    /**
     * Turn User object into a generic array.
     *
     * @param Video $video
     * @return array
     */
    public function transform(Video $video)
    {
        $video->load('review');
        return [
            'id' => (int) $video->id,
            'name' => $video->name,
            'description' => $video->description,
            'posted_date' => Carbon::parse($video->created_at)->format('m.d.Y').' at '.Carbon::parse($video->created_at)->format('g:ia'),
            'status' => $video->status,
            'url' => url('/') . config('video.user_video_path') . $video->url,
            'video_thumbnail' =>  $video->thumbnail ?
                url('/') . config('video.thumbnail_path') . $video->thumbnail :
                url('/') . '/images/default_thumbnail.jpg',
            'pay_status' => $video->pay_status,
            'coach_id'=>$video->coach_id,
            'started_review_status' => $video->started_review_status, // 1 or 0
            'video_price' => intval($video->video_price),
            'product_id' =>$video->product_id,
            'new_price'=>$video->new_price,
        ];
    }
}
 