<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AuditionList extends Model
{
	public function audition(){

		return $this->belongsTo(Auditions::class);

	}
	public function user(){
		return $this->belongsTo(User::class);
	}

    public function auditionreview(){
        return $this->belongsTo(AuditionReview::class);
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
}
