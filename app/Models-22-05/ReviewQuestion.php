<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReviewQuestion extends Model
{
    /**
     * Indicates if the model should be timestamped.
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = ['video_id', 'question', 'question_number', 'answer'];

    public static function saveAnswers($video_id, $answers)
    {
        if(!is_array($answers)) {
            return false;
        }
        $review_qestions = self::whereIn('id', array_keys($answers))->where('video_id', $video_id)->get();
        if(count($review_qestions) == 0) {
            return false;
        }
        foreach($review_qestions as $review_qestion) {
            $review_qestion->answer = $answers[$review_qestion->id];
            $review_qestion->update();
        }
        return true;
    }
}
