<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * App\Models\Video
 *
 * @mixin \Eloquent
 * @property integer $id
 * @property integer $user_id
 * @property string $name
 * @property string $description
 * @property string $url
 * @property boolean $status
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Video whereId( $value )
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Video whereUserId( $value )
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Video whereName( $value )
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Video whereDescription( $value )
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Video whereUrl( $value )
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Video whereStatus( $value )
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Video whereCreatedAt( $value )
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Video whereUpdatedAt( $value )
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Video whereDeletedAt( $value )
 * @property string $genres
 * @property boolean $level
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Video whereGenres( $value )
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Video whereLevel( $value )
 * @property string $payed
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Video wherePayed($value)
 */
class Video extends Model
{
	protected $table = 'videos';
	protected $fillable = [
		'name',
		'description',
		'genres',
		'level',
        'activity_experience',
        'coach_id',
        'user_id',
        'seeking_auditions',
	];
	
	public static $rules = [
		'name'        => 'required|max:255',
		'file_name'   => 'required|max:255',
		'description' => 'required|min:6',
		/*'genres'      => 'required',*/
		'genres.*'    => 'exists:activity_genres,id',
		'level'       => 'required',
		'activity_experience' => 'required|integer|min:0|max:100',
		'seeking_auditions' => 'required|in:yes,no,maybe,not_yet',
	];

    public static $messages = [
        'url.required_if'       => 'The Url field is required',
        'url.regex'             => 'The Url field is is not valid',
        'file_name.required_if' => 'Please, upload file or paste video URL',
    ];

    /**
     * The number of models to return for pagination.
     *
     * @var int
     */
    protected $perPage = 10;

	const STATUS_NEW = 1;
	const STATUS_ACCEPT_PROPOSAL = 2;
	const STATUS_REVIEWED = 3;
	const STATUS_REFUNDED = 4;
	const FLAG_PAYED = 'Y';
	const FLAG_NOT_PAYED = 'N';
   const PARTICIPATION_TYPE = 'V';  // Video

	public function user()
	{
		return $this->belongsTo(User::class, 'user_id', 'id');
	}

	public static function setStatus($video_id = 0, $coach_id = 0)
    {
        $video = self::whereId($video_id)->whereCoachId($coach_id)->whereStatus(self::STATUS_ACCEPT_PROPOSAL)->first();
        if(is_null($video))
            return false;
        $video->status = self::STATUS_NEW;
        return $video->update();
    }

    public static function setStatusRefund($video_id = 0, $coach_id = 0)
    {
        $video = self::whereId($video_id)->whereCoachId($coach_id)->whereIn('status', [self::STATUS_NEW, self::STATUS_ACCEPT_PROPOSAL])->first();
        if(is_null($video))
            return false;
        $video->status = self::STATUS_REFUNDED;
        return $video->update();
    }
	
	public static function search($params=[], $status = [self::STATUS_NEW, self::STATUS_ACCEPT_PROPOSAL])
	{
	    $valid_statuses = [self::STATUS_NEW, self::STATUS_ACCEPT_PROPOSAL, self::STATUS_REVIEWED];
        if(!is_null($status)) {
            $status = in_array($status, $valid_statuses) ? $status : ($status == 'myreviews' ?
                self::STATUS_REVIEWED : self::STATUS_NEW);
        } else {
            $status = [self::STATUS_NEW, self::STATUS_ACCEPT_PROPOSAL];
        }
        $result = self::whereCoachId($params['user_id'])
            ->where('user_id', '<>', $params['user_id'])
            ->where('pay_status',1)
            ->where('is_reformatted',1)
            ->orderBy('created_at', 'DESC');
        if(is_array($status)) {
            $result = $result->whereIn('status', $status);
        } else {
            $result = $result->where('status', $status);
        }

        if(!empty($params['search_text'])) {
            $result = $result->where(function($query) use ($params) {
                $query->where('name', 'LIKE', '%'.$params['search_text'].'%')
                    ->orWhere('description', 'LIKE', '%'.$params['search_text'].'%');
            });
        }
        return $result->paginate();
	}
	
	public static function getById( $id )
	{
		$result = DB::table( 'videos as v' )
                        ->join('users as u', 'v.user_id', '=', 'u.id')
                        ->where( 'v.id', '=', $id )
                        ->select("v.*", "u.first_name as user_first_name", "u.last_name as user_last_name", "u.id as user_id")
                        ->first();
		
		return $result;
	}
	
	public static function getByUserId( $id )
	{
		$result = DB::table( 'videos' )->where( 'user_id', '=', $id )->orderBy( 'created_at', 'desc' )->paginate( 10 );
		
		return $result;
	}
	
	public static function saveVideo( $data )
	{
		
		DB::table( 'videos' )->insert( $data );
		
	}
	
	public static function changeStatus( $id, $status )
	{
            DB::table( 'videos' )
                    ->where( 'id', $id )
                    ->update( [ 'status' => $status ] );
	}

	public function coach()
    {
        return $this->belongsTo(User::class, 'coach_id', 'id');
    }

    public function performance_level()
    {
        return $this->belongsTo(PerformanceLevel::class, 'level', 'id');
    }

    public function activity_genres()
    {
        return $this->belongsToMany(ActivityGenre::class, 'video_genres');
    }

    public function questions()
    {
        return $this->hasMany(ReviewQuestion::class, 'video_id', 'id');
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }

    public function temporary_review()
    {
        return $this->hasOne(TemporaryReview::class);
    }
    public static function getVideoPackage($id){
        $result = DB::table( 'videos as v' )
                        ->where( 'v.id', '=', $id )
                        ->select("v.package_id")
                        ->first();
        
        return $result->package_id;
    }
    public static function getVideoThumbnailUrl($video_id){
        $result = DB::table( 'videos as v' )
                        ->where( 'v.id', '=', $video_id )
                        ->select("v.thumbnail")
                        ->first();
        
        return $result->thumbnail;
    }
}
