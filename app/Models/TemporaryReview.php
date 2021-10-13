<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TemporaryReview extends Model
{
    protected $table = 'temporary_reviews';

    protected $fillable = ['url','review_url','user_id','video_id','play_time','created_at','updated_at'];

    public function video()
    {
        return $this->belongsTo(Video::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function moveTemporaryReviewData($temp_review_id)
    {
        $temp_review = self::whereId($temp_review_id)->with('video')->first();
        $temp_review_path = public_path() . config('video.temp_review_path');
        $completed_review_path = public_path() . config('video.completed_review_path');

        if(file_exists($temp_review_path . $temp_review->review_url)){
            rename(
                $temp_review_path . $temp_review->review_url,
                $completed_review_path . $temp_review->review_url
            );
        }

        return self::whereId($temp_review_id)->delete();
    }

    public static function saveTempReview($data)
    {
        $review = Review::where('video_id', $data['video_id'] )->first();
        if( $review ){
           // $id = $review->id;
            TemporaryReview::where('video_id', $data['video_id'])->delete();
           // TemporaryReview::create($data);
            $id = DB::table('temporary_reviews')->insertGetId($data);
        }else{
            $id = DB::table('temporary_reviews')->insertGetId($data);
        }

        return $id;
    }

}
