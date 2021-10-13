<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChallengeReviewNew extends Model
{
    protected $table = 'challenge_review_new';

    public function participant(){
		return $this->belongsTo(ChallengesParticipant::class, 'challenge_participant_id');
	}
	public static function checkReview($participant_id){
        $audition_reviews = DB::table( 'challenge_review_new' )
        //->where('user_id','=',$user_id)
        ->where('challenge_participant_id','=',$participant_id)
        ->get();
        
        return count($audition_reviews);
    }
}
