<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AuditionTempReview extends Model
{
    protected $table = 'audition_temporary_reviews';

    public function participant(){
		return $this->belongsTo(AuditionList::class);
	}

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function saveTempReview($data)
    {
        $review = AuditionReviewNew::where('audition_participant_id', $data['participant_id'] )->first();
        $review1 = AuditionTempReview::where('participant_id', $data['participant_id'] )->first();
        if($review1){
            AuditionTempReview::where('participant_id', $data['participant_id'])->delete();
        }
        if( $review ){
           // $id = $review->id;
            AuditionTempReview::where('participant_id', $data['participant_id'])->delete();
           // TemporaryReview::create($data);
            $id = DB::table('audition_temporary_reviews')->insertGetId($data);
        }else{
            $id = DB::table('audition_temporary_reviews')->insertGetId($data);
        }

        return $id;
    }

    public static function moveTemporaryReviewData($temp_review_id)
    {
        $temp_review = self::whereId($temp_review_id)->with('participant')->first();
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
}
