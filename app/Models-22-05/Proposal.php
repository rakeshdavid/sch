<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * App\Models\Proposal
 *
 * @mixin \Eloquent
 * @property integer $id
 * @property integer $user_id
 * @property integer $video_id
 * @property string $description
 * @property boolean $status
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Proposal whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Proposal whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Proposal whereVideoId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Proposal whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Proposal whereStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Proposal whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Proposal whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Proposal whereDeletedAt($value)
 */
class Proposal extends Model
{
    public static function saveProposal($data){
        DB::table('proposals')->insert($data);
    }
    
    public static function getById($id){
        $result = DB::table('proposals')->where('id', '=', $id)->first();
        return $result;
    }
    
    public static function getByUserId($id){
        $result = DB::table('proposals as p')
                ->join('videos as v', 'p.video_id', '=', 'v.id')
                ->join('users as u', 'v.user_id', '=', 'u.id')
                ->select('p.*', 'u.first_name as user_first_name', 'u.last_name as user_last_name', 'u.id as user_id', 'v.name as video_name', 'v.description as video_description', 'v.created_at as video_created_at', 'v.url as video_url', 'v.id as video_id')
                ->where('p.user_id', '=', $id)->paginate(10);
        
        return $result;
    }
    
    public static function getByVideoId($id){
        $result = DB::table('proposals as p')
                ->join('users as u', 'p.user_id', '=', 'u.id')
                ->select('p.*', 'u.first_name as user_first_name', 'u.last_name as user_last_name', 'u.avatar as user_avatar')
                ->where('p.video_id', '=', $id)->paginate(10);
        return $result;
    }
    
    public static function changeStatus($id, $status){
        
        $res = DB::table('proposals')
            ->where('id', $id)
            ->update(['status' => $status, 'updated_at' => mysql_date()]);
        
        return $res;
        
    }
}
