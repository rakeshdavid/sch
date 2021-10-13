<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ChallengesParticipant extends Model
{
	protected $table = 'challenge_participant';
	const STATUS_REVIEWED = 1;
   const PARTICIPATION_TYPE = 'C';  // Challenge

    public function challenge(){    // created because it is singular
		return $this->belongsTo(Challenges::class,'challenge_id','id');
	}
    public function challenges(){   // not removed because it is used at many places

		return $this->belongsTo(Challenges::class,'challenge_id','id');

	}
	public function user(){
		return $this->belongsTo(User::class,'user_id','id');
	}

	public function review(){
		return $this->hasOne(ChallengeReviewNew::class,'challenge_participant_id','id');
	}
	public function challenge_temporary_reviews(){
		return $this->hasOne(ChallengeTempReview::class,'participant_id','id');
	}
	public static function newSubmission($param,$show){
		if($show == 'myreviews'){
			$result = ChallengesParticipant::with(['challenges','user','review'])->where('coach_id', $param['user_id'])->where( 'payment_status', '=', 1)->where('status','=',1)->get();
		}else{
			$result = ChallengesParticipant::with(['challenges','user'])->where('coach_id', $param['user_id'])->where( 'payment_status', '=', 1)->where('status','=',0)->get();
		}
		
		return $result;
	}
	public static function changeStatus( $id, $status )
	{
      DB::table( 'challenge_participant' )
         ->where( 'id', $id )
         ->update( [ 'status' => $status ] );
	}
}
