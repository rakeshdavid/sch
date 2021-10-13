<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Auditions extends Model
{
	protected $table = 'auditions';

    public function participants()
	{
		return $this->hasMany(AuditionList::class,'audition_id','id');
	}
	public function user()
	{
		return $this->belongsTo(User::class, 'agency_id', 'id');
	}
	public static function getParticipant(){

		$temp = Auditions::find(1)->audition;
	}

	public static function auditionById( $id )
	{
		$result = DB::table('auditions')->where( 'id', '=', $id )->first();
		
		return $result;
	}

	public static function firstAudition(){
		$result = Auditions::where('deadline','>=',date('Y-m-d'))
         ->orWhereHas('participants',function($q){
            $q->where('user_id',auth()->user()->id)->where('payment_status',1)->where( 'stripe_id', '!=', 'NULL');
         })
         ->orderBy('deadline', 'asc')
         ->first();
		return $result;
	}

	public function auditionLevel($audition_id){
		$levels = array('Beginner','Intermediate','Advanced');

		$result = DB::table('auditions')->where( 'id', '=', $audition_id )->first();
		

		return $result;
	}

}
