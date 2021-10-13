<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class Challenges extends Model
{
	public function ChallengeParticipant()
	{
		return $this->hasMany(ChallengeParticipant::class);
	}
	
	public function user(){
		return $this->belongsTo(User::class);
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
		$result = DB::table('challenges')->where('deadline','>=',date('Y-m-d'))->orderBy('deadline', 'asc')->first();
		return $result;
	}
 
}
