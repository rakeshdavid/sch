<?php

namespace App\Api\Transformers;

use App\Models\Video;
use App\Models\Review;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class ReviewedVideoTransformer extends  TransformerAbstract
{
    /**
     * Turn User object into a generic array.
     *
     * @param Video $video
     * @return array
     */
    public function transform(Review $user_videos)
    {
        //$video->load('review');
        return [
            'id' => (int) $user_videos->id,
            'video_id'=>(int) $user_videos->video->id,
            'name' => $user_videos->video->name,
           // 'description' => $user_videos->description,
            'posted_date' => Carbon::parse($user_videos->video->created_at)->format('m/d/Y'),
            'status' => $user_videos->status,
            'url' => url('/') . config('video.user_video_path') . $user_videos->video->url,
            'video_thumbnail' =>  $user_videos->video->thumbnail ?
                url('/') . config('video.thumbnail_path') . $user_videos->video->thumbnail :
                url('/') . '/images/default_thumbnail.jpg',
            'pay_status' => $user_videos->video->pay_status,
            'coach_id'=>$user_videos->video->coach_id,
            'level'=>$user_videos->video->level,
        ];
    }
}
 