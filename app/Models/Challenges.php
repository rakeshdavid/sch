<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class Challenges extends Model
{
	protected $table = 'challenges';

	public function participants()
	{
		return $this->hasMany(ChallengesParticipant::class,'challenge_id','id');
	}
	
	public function user(){
		return $this->belongsTo(User::class,'coach_id','id');
	}
 
    public function auditionreview(){
        return $this->belongsTo(AuditionReview::class);
    }

    public static function challengeById( $id )
	{
		$result = DB::table('challenges')->where( 'id', '=', $id )->first();
		
		return $result;
	}

	public static function firstChallenge(){
		$result = Challenges::where('deadline','>=',date('Y-m-d'))
      ->orWhereHas('participants', function($q){
         $q->where('payment_status',1)->where('user_id','=',auth()->user()->id);
      })
      ->orderBy('deadline', 'asc')->first();
		return $result;
	}
 	
 	public static function getChallengeCoachId($challenge_id){
 		$result = DB::table('challenges')->where( 'id', '=', $challenge_id )->first();
		
		return $result->coach_id;
 	}
}
