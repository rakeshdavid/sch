<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Auditions extends Model
{
    public function auditionlist()
	{
		return $this->hasMany(AuditionList::class);
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
		$result = DB::table('auditions')->where('deadline','>=',date('Y-m-d'))->orderBy('deadline', 'asc')->first();
		return $result;
	}

	public function auditionLevel($audition_id){
		$levels = array('Beginner','Intermediate','Advanced');

		$result = DB::table('auditions')->where( 'id', '=', $audition_id )->first();
		

		return $result;
	}

}
