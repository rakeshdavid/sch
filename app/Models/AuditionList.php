<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AuditionList extends Model
{
    protected $table = 'audition_participant';
    const STATUS_REVIEWED = 1;
    const PARTICIPATION_TYPE = 'A'; // Audition

	public function audition(){

		return $this->belongsTo(Auditions::class,'audition_id','id');

	}
	public function user(){
		return $this->belongsTo(User::class, 'user_id', 'id');
	}

    public function auditionreview(){
        return $this->belongsTo(AuditionReview::class);
    }
    public function auditionreviewnew(){
        return $this->belongsTo(AuditionReviewNew::class,'id','audition_participant_id');
    }
    public function audition_temporary_reviews(){
        return $this->hasOne(AuditionTempReview::class,'participant_id','id');
    }
    public static function audition_list($user_id){
    	$result = DB::table( 'audition_participant' )
    	->where('user_id','=',$user_id)
    	->where('payment_status','=',1)
    	->get();
		
		return $result;
    }
    public static function participant(){
    	$temp = Auditions::find(1)->audition;
    }
	public static function changeStatus( $id, $status )
	{
      DB::table( 'audition_participant' )
         ->where( 'id', $id )
         ->update( [ 'status' => $status ] );
	}
}
