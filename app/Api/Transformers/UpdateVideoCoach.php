<?php

namespace App\Api\Transformers;

use App\Models\Video;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class UpdateVideoCoach extends  TransformerAbstract
{
    /**
     * Turn User object into a generic array.
     *
     * @param Video $video
     * @return array
     */
    public function transform(Video $videodata)
    {
        
        return [
            'id' => (int) $videodata->id,
            'name' => $videodata->name,
            'description' => $videodata->description,
            'posted_date' => Carbon::parse($videodata->created_at)->format('m/d/Y'),
            'status' => $videodata->status,
            'url' => url('/') . config('video.user_video_path') . $videodata->url,
            'video_thumbnail' =>  $videodata->thumbnail ?
                url('/') . config('video.thumbnail_path') . $videodata->thumbnail :
                url('/') . '/images/default_thumbnail.jpg',
            'pay_status' => $videodata->pay_status,
            'coach_id'=>$videodata->coach_id,
            'started_review_status' => $videodata->started_review_status, // 1 or 0
            'video_price' => intval($videodata->video_price),
            'coach_name'=>$videodata->coach->first_name.' '.$videodata->coach->last_name,
            'coach_avatar' =>url('/').$videodata->coach->avatar,
            'coach_title' => $videodata->coach->title,
            'coach_level'=>$videodata->level,
            'price_summary_desc' => $videodata->coach->price_summary_desc,
            'price_detailed_desc' => $videodata->coach->price_detailed_desc,
        ];
    }
}
 