<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ChallengeReview extends Model
{
    protected $table = 'challenge_reviews';

    public function participant(){
		return $this->belongsTo(ChallengesParticipant::class);
	}
	public static function checkReview($participant_id){
        $audition_reviews = DB::table( 'challenge_reviews' )
        //->where('user_id','=',$user_id)
        ->where('challenge_participant_id','=',$participant_id)
        ->get();
        
        return count($audition_reviews);
    }
}
