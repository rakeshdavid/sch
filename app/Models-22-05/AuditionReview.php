<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class AuditionReview extends Model
{
    protected $table = 'audition_reviews';

    public function auditionlist()
    {
        return $this->belongsTo(AuditionList::class);
    }

    public function auditionReviewById($participant_id){
    	//$audition_reviews = AuditionReview::where('audition_participant_id', $participant_id)->get();
    	$audition_reviews = DB::table( 'audition_reviews' )
    	//->where('user_id','=',$user_id)
    	->where('audition_participant_id','=',$participant_id)
    	->get();
    	
    	return $audition_reviews;
    }
}
