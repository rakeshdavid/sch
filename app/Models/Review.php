<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * App\Models\Review
 *
 * @mixin \Eloquent
 * @property integer $id
 * @property integer $user_id
 * @property integer $video_id
 * @property string $url
 * @property boolean $status
 * @property boolean $artisty
 * @property boolean $formation
 * @property boolean $interpretation
 * @property boolean $creativity
 * @property boolean $style
 * @property boolean $energy
 * @property boolean $precision
 * @property boolean $timing
 * @property boolean $footwork
 * @property boolean $alingment
 * @property boolean $balance
 * @property boolean $focus
 * @property string $message
 * @property string $play_time
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Review whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Review whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Review whereVideoId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Review whereUrl($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Review whereStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Review whereArtisty($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Review whereFormation($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Review whereInterpretation($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Review whereCreativity($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Review whereStyle($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Review whereEnergy($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Review wherePrecision($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Review whereTiming($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Review whereFootwork($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Review whereAlingment($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Review whereBalance($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Review whereFocus($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Review whereMessage($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Review wherePlayTime($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Review whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Review whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Review whereDeletedAt($value)
 */
class Review extends Model
{
    public static function saveReview($data){

        if( Review::select('url')->where('video_id', $data['video_id'] )->exists() ){
            $url = Review::select('url')->where('video_id', $data['video_id'] )->first()->url;
            if( \File::exists(public_path() . $url) ){;
                \File::delete(public_path() . $url);
            }
        }

        self::whereVideoId($data['video_id'])->delete();
        $id = DB::table('reviews')->insertGetId($data);
        return $id;

    }
    
    public static function getById($id){
        $result = DB::table('reviews')->where('id', '=', $id)->first();
        return $result;
    }
    
    public static function getByIdFull($id){
        $result = DB::table('reviews as r')
                ->join('videos as v', 'v.id', '=', 'r.video_id')
                ->select("r.*", "v.name as video_name", "v.level as video_level", "v.genres as video_genres", "v.description as video_description", "v.url as video_url")
                ->where('r.id', '=', $id)->first();
        return $result;
    }
    
    public static function getByVideoIdFull($id){
        $result = DB::table('reviews as r')
                ->join('videos as v', 'v.id', '=', 'r.video_id')
                ->join('users as u', 'r.user_id', '=', 'u.id')
                ->select("r.*", "v.name as video_name", "v.level as video_level", "v.genres as video_genres", "v.description as video_description","v.thumbnail", "v.url as video_url", "u.id as user_id", "u.first_name as user_first_name", "u.last_name as user_last_name", "u.avatar as user_avatar")
                ->where('r.video_id', '=', $id)
                ->orderBy('r.id', 'desc')
                ->first();
        return $result;
    }
    
    public static function getByUserIdList($id){
        $result = DB::table('reviews as r')
                ->join('videos as v', 'v.id', '=', 'r.video_id')
                ->join('users as u', 'v.user_id', '=', 'u.id')
                ->select("r.*", "v.name as video_name", "v.level as video_level", "v.genres as video_genres", "v.description as video_description", "v.url as video_url","v.thumbnail", "u.id as user_id", "u.first_name as user_first_name", "u.last_name as user_last_name", "u.avatar as user_avatar")
                ->where('r.user_id', '=', $id)
                ->orderBy('r.id', 'desc')
                ->paginate(10);
        return $result;
    }
    public static function getByUserIdListAll($id){
        $result = DB::table('reviews as r')
                ->join('videos as v', 'v.id', '=', 'r.video_id')
                ->join('users as u', 'v.user_id', '=', 'u.id')
                ->select("r.*", "v.name as video_name", "v.level as video_level", "v.genres as video_genres", "v.description as video_description", "v.url as video_url","v.thumbnail", "u.id as user_id", "u.first_name as user_first_name", "u.last_name as user_last_name", "u.avatar as user_avatar")
                ->where('r.user_id', '=', $id)
                ->orderBy('r.id', 'desc')
                ->get();
        return $result;
    }
    
    public static function getByUserIdFull($id, $user_id){
        $result = DB::table('reviews as r')
                ->join('videos as v', 'v.id', '=', 'r.video_id')
                ->join('users as u', 'v.user_id', '=', 'u.id')
                ->select("r.*", "v.name as video_name", "v.level as video_level", "v.genres as video_genres", "v.created_at as video_created_at","v.thumbnail", "v.description as video_description", "v.url as video_url", "u.id as user_id", "u.first_name as user_first_name", "u.last_name as user_last_name", "u.avatar as user_avatar")
                ->where('r.video_id', '=', $id)
                ->where('r.user_id', '=', $user_id)
                ->orderBy('r.id', 'desc')
                ->first();
        return $result;
    }
    
    public static function setRating($id, $rating){
        DB::table('reviews')
            ->where('id', $id)
            ->update($rating);
    }

    public static function levelPlacement($id, $level_id)
    {
        $level = PerformanceLevel::whereId($level_id)->first();
        $review = self::whereId($id)->first();
        if(is_null($level) || is_null($review)) {
            return false;
        }
        $review->performance_level_placement()->associate($level);
        return $review->update();
    }

    public function video()
    {
        return $this->belongsTo(Video::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function performance_level_placement()
    {
        return $this->belongsTo(PerformanceLevel::class, 'performance_level_id');
    }

    public static function removeReviewFile($review_url)
    {
        $completed_review_path = public_path() . config('video.completed_review_path');

        unlink_if_exist($completed_review_path . $review_url);
    }

    public function getAllReviewedVideos($user_id){
        $result = DB::table('reviews as r')
                ->join('videos as v', 'v.id', '=', 'r.video_id')
                ->join('users as u', 'v.user_id', '=', 'u.id')
                ->select("r.*", "v.name as video_name", "v.level as video_level", "v.genres as video_genres", "v.created_at as video_created_at","v.thumbnail", "v.description as video_description", "v.url as video_url", "u.id as user_id", "u.first_name as user_first_name", "u.last_name as user_last_name", "u.avatar as user_avatar")
                ->where('r.user_id', '=', $user_id)
                ->orderBy('r.id', 'desc')
                ->get();
        return $result;
    }

    public static function getAllReviewedByLevel($user_id,$level){
        $result = DB::table('reviews as r')
                ->join('videos as v', 'v.id', '=', 'r.video_id')
                ->join('users as u', 'v.user_id', '=', 'u.id')
                ->select("r.*", "v.name as video_name", "v.level as video_level", "v.genres as video_genres", "v.created_at as video_created_at","v.thumbnail", "v.description as video_description", "v.url as video_url", "u.id as user_id", "u.first_name as user_first_name", "u.last_name as user_last_name", "u.avatar as user_avatar")
                ->where('r.user_id', '=', $user_id)
                ->where('r.performance_level_id', '=', $level)
                ->orderBy('r.id', 'desc')
                ->get();
        return $result;
    }

    public function getReviewByVideoId($video_id){
        $result = DB::table('reviews')->where('video_id', '=', $video_id)->first();
        return $result;
    }

    public function getReviewQuestion($video_id){
        $result = DB::table('review_questions')->where('video_id', '=', $video_id)->orderBy('question_number', 'asc')->limit(3)->get();
        return $result;
    }

    public function getCoachDetail($coach_id){
        $result = DB::table('users')->where('id', '=', $coach_id)->first();
        return $result;
    }

    public function getCoachDetailByVideoId($video_id){
        $video = DB::table('videos')->where('id', '=', $video_id)->first();
        $result = DB::table('users')->where('id', '=', $video->coach_id)->first();
        return $result;
    }
}
