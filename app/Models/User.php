<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	const USER_ROLE = 1;
	const COACH_ROLE = 2;
	const ADMIN_ROLE = 3;
    const AGENCY_ROLE = 4;
	const USER_ROLE_NAME = 'user';
	const COACH_ROLE_NAME = 'coach';
	const ADMIN_ROLE_NAME = 'admin';
    const AGENCY_ROLE_NAME = 'agency';
	const REAL_USER = 0;
	const TEST_USER = 1;

	protected $fillable = [
		'email',
		'contact_email',
		'facebook_id',
		'avatar',
		'role',
		'gender',
		'birthday',
		'location',
		'first_name',
		'last_name',
		'position',
		'companies',
		'social_links',
		'wevsites',
		'phone',
		'about',
		'hometown',
		'languages',
        'password',
        'is_hidden',
        'is_test'
	];

    protected static $hiddenOptions = [
        '0' => 'Visible',
        '1' => 'Hidden'
    ];

    protected static $testUsers = [
        '0' => 'Real',
        '1' => 'Test'
    ];

    protected static $password_rules = [
        'password_old' => 'required|old_password',
        'password' => 'required|confirmed|min:6',
    ];

    public static $internal_rules = [
        'first_name'        => 'required|max:254',
        'last_name'         => 'required|max:254',
        'contact_email'     => 'required|email|max:254',
        'location'          => 'required|max:254',
        'birthday'          => 'required',
        'about'             => 'string',
        'site_target'       => 'required|in:internet_search,advertisement,friend,other',
        'other_site_spec'   => 'required_if:site_target,other',
    ];

    public static $public_fields = ['first_name', 'last_name', 'contact_email', 'location', 'birthday', 'about',
        'other_site_spec', 'site_target'];

    protected static $password_messages = [
        'password_old.old_password' => 'Old password is incorrect.',
    ];

    /**
     * The number of models to return for pagination.
     *
     * @var int
     */
    protected $perPage = 10; //TODO: change

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [
		'remember_token',
	];
 
	public function videos()
	{
		return $this->hasMany( Video::class );
	}
    public function reviews()
    {
        return $this->hasMany( Video::class );
    }
    public function auditionParticipant(){
        return $this->hasMany(AudtionList::class);
    }
    public function challengesparticipant(){
        return $this->hasMany(ChallengesParticipant::class);
    }
	public function coachVideos()
    {
        return $this->hasMany(Video::class, 'coach_id', 'id');
    }
    public function agencyAudition()
    {
        return $this->hasMany(Auditions::class, 'agency_id', 'id');
    }
    public function coachChallenges(){
        return $this->hasMany(Challenges::class, 'coach_id', 'id');
    }
	public static function getById( $id )
	{
		$result = DB::table('users')->where( 'id', '=', $id )->first();
		
		return $result;
	}

	public static function getUserRole()
    {
        return self::USER_ROLE;
    }

    public static function getUserRoleName()
    {
        return self::USER_ROLE_NAME;
    }
    public static function getAgencyRoleName()
    {
        return self::AGENCY_ROLE_NAME;
    }
    public static function getCoachRole()
    {
        return self::COACH_ROLE;
    }
    public static function getAgencyRole()
    {
        return self::AGENCY_ROLE;
    }

    public static function getCoachRoleName()
    {
        return self::COACH_ROLE_NAME;
    }

    public static function getAdminRole()
    {
        return self::ADMIN_ROLE;
    }

    public static function getAdminRoleName()
    {
        return self::ADMIN_ROLE_NAME;
    }

    public static function getRealUser()
    {
        return self::REAL_USER;
    }

    public static function getTestUser()
    {
        return self::TEST_USER;
    }

    public function isUser()
    {
        return $this->role == self::getUserRole();
    }

    public function isCoach()
    {
        return $this->role == self::getCoachRole();
    }

    public function isAdmin()
    {
        return $this->role == self::getAdminRole();
    }

	public static function setRole( $role, $id )
	{
		
		DB::table( 'users' )
			->where( 'id', $id )
			->update( [ 'role' => $role ] );
	}

    /**
     * @param null|integer $type_id
     * @param null|array $genres_id
     * @param null|array $levels_id
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public static function findCoaches($type_id = null, $genres_id = null, $levels_id = null,$name=null)
    {
        $result = self::whereRole(self::COACH_ROLE)->whereIsHidden(self::getVisibleOption())->orderBy('first_name');
        if($type_id === 0 && is_null($genres_id) && is_null($levels_id)) {
            return $result->paginate();
        }
        if($type_id > 0) {
            $type_related_users_id = array_flatten(UserActivityType::whereActivityTypeId($type_id)->select('user_id')
                ->get()->toArray());
            $result = $result->whereIn('id', $type_related_users_id);
        }
        if(!empty($genres_id) && !is_null($genres_id) && is_array($genres_id)) {
            $genres_related_users_id = array_flatten(UserGenre::whereIn('activity_genre_id', $genres_id)
                ->select('user_id')->get()->toArray());
            if(!empty($genres_related_users_id)){
               $result = $result->whereIn('id', $genres_related_users_id);
            }
        }
        if(!empty($levels_id) && !is_null($levels_id) && is_array($levels_id)) {
            $levels_related_users_id = array_flatten(UserPerformanceLevel::whereIn('performance_level_id', $levels_id)
                ->select('user_id')->get()->toArray());
            if(!empty($levels_related_users_id)){
               $result = $result->whereIn('id', $levels_related_users_id);
            }
        }
        if(!is_null($name) && trim($name) !=''){
            $temp_name = explode(" ",$name);
            if(count($temp_name) > 1){
                $result = $result->where('first_name','like','%'.$temp_name[0].'%')->orWhere('last_name','like','%'.$temp_name[1].'%');
            }else{
                $result = $result->where(function($q) use ($temp_name){
                   $q->where('first_name','like','%'.$temp_name[0].'%')->orWhere('last_name','like','%'.$temp_name[0].'%');
                });
            }
        }
        return $result->paginate();
    }

    public function changePassword($data = [], $user_id)
    {
        $validator = validator($data, self::$password_rules, self::$password_messages);
        if($validator->fails()) {
            return $validator->errors();
        }
        $user = self::find($user_id);
        $user->password = bcrypt($data['password']);
        $user->update();
        return true;
    }

    public function performance_levels()
    {
        return $this->belongsToMany(PerformanceLevel::class, 'user_performance_levels')->orderBy('order', 'ASC');
    }

    public function activity_types()
    {
        return $this->belongsToMany(ActivityType::class, 'user_activity_types');
    }

    public function activity_genres()
    {
        return $this->belongsToMany(ActivityGenre::class, 'user_genres')->orderBy('activity_type_id', 'ASC');
    }

    public function gallery()
    {
        return $this->hasMany(CoachGallery::class, 'user_id')->orderBy('type', 'DESC');
    }

    public function documents()
    {
        return $this->hasMany(CoahcesDocuments::class, 'user_id');
    }

    public function stripe_connection(){
        return $this->hasOne('App\Models\StripeConnection');
    }
    
    public function getCoachAvatar(){
        if( CoachGallery::where('user_id', $this->id)->where('type', 'video')->exists() === "1488" ){
            $path = CoachGallery::where('user_id', $this->id)->where('type', 'video')->first()->path;
            return '<iframe style="width: 100%; max-width: 250px !Important"; src="https://www.youtube.com/embed/'. $path .'"></iframe>';
        } else if ( $this->avatar ){
            return '<img style="width: 100%; max-width: 250px !Important"; src="'. $this->avatar .'">';
        } else {
            $path = CoachGallery::where('user_id', $this->id)->where('type', 'image')
                ->orderBy('created_at', 'DESC')->first();//->path;      =>
            //Temporarly fix due to non-object when no user avatar
            if (isset($path->path)) {
                return '<img style="width: 100%; max-width: 250px !Important"; src="/gallery/' . $path->path . '">';
            } else {
                return '';
            }
        }
        
    }

    public static function getCoachesData($request)
    {
        $length = $request->get('length');
        $draw = (int) $request->get('draw');
        $search = $request->get('search')['value'];

        $users = self::select([
            'first_name',
            'last_name',
            'email',
            'is_test',
            \DB::raw('CONCAT(id, "::", is_hidden)')
        ])
        ->where('role', self::getCoachRole());

        $recordsTotal = $users->count();

        $users = self::prepareSearch($users, $search);

        $recordsFiltered = $users->count();
        $users = $users->limit($length)->offset($request->get('start'))->get();
        if ($users->count() == 0) {
            return ["data" => [], "draw" => $draw, "recordsTotal" => 0, "recordsFiltered" => 0];
        }

        $data = [];
        $i = 0;
        foreach ($users->toArray() as $key => $val) {
            foreach($val as $v) {
                $data[$i][] = $v;
            }
            $i++;
        }

        return ["data" => $data, "draw" => $draw, "recordsTotal" => $recordsTotal, "recordsFiltered" => $recordsFiltered];
    }

    public static function getUsersData($request)
    {
        $length = $request->get('length');
        $draw = (int) $request->get('draw');
        $search = $request->get('search')['value'];

        $users = self::select([
            'first_name',
            'last_name',
            'email',
            'is_test',
            'id'
        ])
        ->where('role', self::getUserRole());

        $recordsTotal = $users->count();

        $users = self::prepareSearch($users, $search);

        $recordsFiltered = $users->count();
        $users = $users->limit($length)->offset($request->get('start'))->get();
        if ($users->count() == 0) {
            return ["data" => [], "draw" => $draw, "recordsTotal" => 0, "recordsFiltered" => 0];
        }

        $data = [];
        $i = 0;
        foreach ($users->toArray() as $key => $val) {
            foreach($val as $v) {
                $data[$i][] = $v;
            }
            $i++;
        }

        return ["data" => $data, "draw" => $draw, "recordsTotal" => $recordsTotal, "recordsFiltered" => $recordsFiltered];
    }

    public static function getCodeRedData($request)
    {
        $length = $request->get('length');
        $draw = (int) $request->get('draw');
        $search = $request->get('search')['value'];

        $videos = self::getCodeRedVideos(5, $search);

        $data = [];
        foreach ($videos as $key => $video){
            if($video->user()->exists() && ($video->user()->get()[0]->is_test == 0)) {
                $data[$key][] = $video->user()->get(['id', 'email', 'first_name', 'last_name'])->toArray();
                $data[$key][] = $video->coach()->get(['id', 'email', 'first_name', 'last_name'])->toArray();
                $data[$key][] = ['video_id' => $video['video_id']];
                $data[$key][] = $video['video_id'];
            }
        }

        if (count($data) == 0) {
            return ["data" => [], "draw" => $draw, "recordsTotal" => 0, "recordsFiltered" => 0];
        }

        $recordsTotal = count($data);
        $recordsFiltered = count($data);

        $data = array_slice($data, $request->get('start'), $length);

        return ["data" => $data, "draw" => $draw, "recordsTotal" => $recordsTotal, "recordsFiltered" => $recordsFiltered];
    }

    protected static function prepareSearch($instance, $search)
    {
        $instance = $instance->where(function($query) use($search){
            $query->orWhere('first_name', 'LIKE', '%' . $search . '%');
            $query->orWhere('last_name', 'LIKE', '%' . $search . '%');
            $query->orWhere('email', 'LIKE', '%' . $search . '%');
        });

        return $instance;
    }

    /**
     * Get users count.
     *
     * @return integer
     */
    public static function getUsersCount()
    {
        return self::whereRole(self::getUserRole())
            ->whereIsHidden(self::getVisibleOption())
            ->whereIsTest(self::getRealUserName())
            ->count();
    }

    /**
     * Get coaches count.
     *
     * @return integer
     */
    public static function getCoachesCount()
    {
        return self::whereRole(self::getCoachRole())
            ->whereIsHidden(self::getVisibleOption())
            ->whereIsTest(self::getRealUserName())
            ->count();
    }

    /**
     * Active users (customers who paid and waiting on review).
     *
     * @return integer
     */
    public static function getActiveUsersCount()
    {
        return self::whereRole(self::getUserRole())
            ->whereIsHidden(self::getVisibleOption())
            ->whereIsTest(self::getRealUserName())
            ->whereHas('videos', function($query) {
                $query->where('videos.status', '=', Video::STATUS_NEW)
                    ->where('videos.pay_status', '=', 1);
            })
            ->count();
    }

    public static function getActiveUsers($request)
    {
        $length = $request->get('length');
        $draw = (int) $request->get('draw');
        $search = $request->get('search')['value'];

        $users = self::select([
                'id',
                'first_name',
                'last_name',
                'email'
            ])
            ->where('role', self::getUserRole())
            ->whereIsHidden(self::getVisibleOption())
            ->whereIsTest(self::getRealUserName())
            ->whereHas('videos', function($query) {
                $query->where('videos.status', '=', Video::STATUS_NEW)
                    ->where('videos.pay_status', '=', 1);
            });

        $recordsTotal = $users->count();

        $users = self::prepareSearch($users, $search);

        $recordsFiltered = $users->count();
        $users = $users->limit($length)->offset($request->get('start'))->get();
        if ($users->count() == 0) {
            return ["data" => [], "draw" => $draw, "recordsTotal" => 0, "recordsFiltered" => 0];
        }

        $data = [];
        $i = 0;
        foreach ($users->toArray() as $key => $val) {
            foreach($val as $v) {
                $data[$i][] = $v;
            }

            $coaches = Video::select([
                'coach_id', 'users.email'
            ])->where('user_id', '=', $val['id'])
              ->where('status', '=', Video::STATUS_NEW)
              ->where('pay_status', '=', 1)
              ->join('users', 'videos.coach_id', '=', 'users.id')
              ->get()->unique();

            $data[$i][] = $coaches->toArray();

            $i++;
        }

        return ["data" => $data, "draw" => $draw, "recordsTotal" => $recordsTotal, "recordsFiltered" => $recordsFiltered];
    }

    public static function getActiveCoachesCount()
    {
        return self::whereRole(self::getCoachRole())
            ->whereIsTest(self::getRealUserName())
            ->whereIsHidden(self::getVisibleOption())
            ->whereHas('coachVideos', function($query) {
                $query->where('videos.status', '<>', Video::STATUS_NEW);
            })
            ->count();
    }

    public static function getActiveCoaches($request)
    {
        $length = $request->get('length');
        $draw = (int) $request->get('draw');
        $search = $request->get('search')['value'];

        $users = self::select([
                'first_name',
                'last_name',
                'email',
                \DB::raw('CONCAT(id, "::", is_hidden)')
            ])
            ->whereRole(self::getCoachRole())
            ->whereIsHidden(self::getVisibleOption())
            ->whereIsTest(self::getRealUserName())
            ->whereHas('coachVideos', function($query) {
                $query->whereNotIn('videos.status', [Video::STATUS_NEW, Video::STATUS_REFUNDED]);
            });

        $recordsTotal = $users->count();

        $users = self::prepareSearch($users, $search);

        $recordsFiltered = $users->count();
        $users = $users->limit($length)->offset($request->get('start'))->get();
        if ($users->count() == 0) {
            return ["data" => [], "draw" => $draw, "recordsTotal" => 0, "recordsFiltered" => 0];
        }

        $data = [];
        $i = 0;
        foreach ($users->toArray() as $key => $val) {
            foreach($val as $v) {
                $data[$i][] = $v;
            }
            $i++;
        }

        return ["data" => $data, "draw" => $draw, "recordsTotal" => $recordsTotal, "recordsFiltered" => $recordsFiltered];
    }

    public static function getNotActiveCoaches()
    {
        $coach_ids = [];
        $all_ids = self::whereRole(self::getCoachRole())
            ->whereIsHidden(self::getVisibleOption())
            /*->whereHas('coachVideos', function($query) {
                $query->where('videos.status', '<>', Video::STATUS_NEW); //=> old wish - only free coaches
                //$query->where('videos.status', '<', Video::STATUS_REFUNDED);
            })*/
            ->get(['id'])->unique('id');

        foreach ($all_ids as $id) {
            $coach_ids[] = $id->id;
        }

        $stripe_ids = self::whereRole(self::getCoachRole())
            ->whereHas('stripe_connection', function ($search) {
                $search->whereIn('stripe_connections.user_id', self::whereRole(self::getCoachRole())
                    ->where('is_hidden', 0)
                    ->get(['id']));
            })->get(['id']);

        return self::whereRole(self::getCoachRole())
            ->whereIsHidden(self::getVisibleOption())
            ->whereIn('id', $stripe_ids)
            //->whereNotIn('id', $coach_ids) //=> old wish - only free coaches
            ->get(['id', 'first_name', 'last_name', 'email']);
    }

    public static function test()
    {
        $coaches = self::whereRole(self::getCoachRole())->get();

        foreach ($coaches as $coach) {
            $data = [];
            if($coach->coachVideos()->count()) {
                $count = $coach->coachVideos;
                foreach ($count as $item) {
                    //dd($item);
                    $data[] = $item->status;
                };

                echo $coach->id . " - " . $coach->coachVideos()->count() . "|" . implode(",", $data) . "<br>";
            }
        }
        //var_dump(count($coaches));
        dd('Stop');
    }

    public static function getCodeRedVideos($days, $search=null)
    {
        $expDate = Carbon::now()->subDays($days)->toDateTimeString();
DB::enableQueryLog();
        $data = PaymentsList::whereDate('created_at', '<=', $expDate)
            ->whereHas('video', function($query) {
                $query->where('videos.status', '<', Video::STATUS_REVIEWED)
                    ->where('videos.pay_status', '=', 1);
                //->where('videos.started_review_status', '=', 1);
            })
            ->whereHas('user',function($query){
               $query->where('is_test',0);
            });

        if($search) {
            $data = $data->where(function($query) use ($search) {
                $query->whereHas('user', function($q) use ($search){
                    $q->orWhere('users.email', 'LIKE', '%'.$search.'%');
                    $q->orWhere('users.first_name', 'LIKE', '%'.$search.'%');
                    $q->orWhere('users.last_name', 'LIKE', '%'.$search.'%');
                })
                    ->orWhereHas('coach', function($q) use ($search){
                        $q->orWhere('users.email', 'LIKE', '%'.$search.'%');
                        $q->orWhere('users.first_name', 'LIKE', '%'.$search.'%');
                        $q->orWhere('users.last_name', 'LIKE', '%'.$search.'%');
                    });
            });
        }

        return $data->get();
    }

    public static function getAvatarsFolder()
    {
        return env('AVATARS_FOLDER');
    }

    public static function getDocumentsFolder()
    {
        return public_path() . '/' . env('UPLOADS_FOLDER') . '/' . env('COACHES_DOCUMENTS_FOLDER');
    }

    public static function getGalleryFolder()
    {
        return public_path() . '/' . env('GALLERY_FOLDER');
    }

    public static function getImagesFolder()
    {
        return public_path() . '/' . env('IMAGES_FOLDER');
    }

    public static function getUploadsFolder()
    {
        return public_path() . '/' . env('UPLOADS_FOLDER');
    }

    public static function getVideosFolder()
    {
        return public_path() . '/' . env('VIDEOS_FOLDER');
    }

    public function changeHiddenOption()
    {
        $hiddenOption = $this->isHidden() ? self::getVisibleOption() : self::getHiddenOption();

        return self::update(['is_hidden' => $hiddenOption]);
    }

    public function isHidden()
    {
        return $this->is_hidden == self::getHiddenOption();
    }

    public static function getHiddenOption()
    {
        return array_search('Hidden', self::$hiddenOptions);
    }

    public static function getVisibleOption()
    {
        return array_search('Visible', self::$hiddenOptions);
    }

    public static function getTestUserName()
    {
        return array_search('Test', self::$testUsers);
    }

    public static function getRealUserName()
    {
        return array_search('Real', self::$testUsers);
    }

    public function isTestUser()
    {
        return $this->is_test == self::getTestUserName();
    }

    public function changeUserTest()
    {
        $isTestOption = $this->isTestUser() ? self::getRealUserName() : self::getTestUserName();

        return self::update(['is_test' => $isTestOption]);
    }

    public static function getAllCoaches(){
        $coaches = DB::table('users as u')
            ->where('u.role','2')
            ->where('u.is_hidden','!=',1)
            ->orderBy('first_name')
            ->paginate(10);

        return $coaches;
    }

    // Bhashkar Functions

    public function coach_performance_level($user_id){
        //echo $user_id;
        $pl = DB::table('performance_levels as pl')
            ->leftjoin('user_performance_levels as upl', 'pl.id', '=', 'upl.performance_level_id')
            //->leftjoin('user_genres as ug', 'u.id', '=', 'ug.user_id')
            ->select('pl.name')
            ->where('upl.user_id',$user_id)
            ->get();
        return $pl;
    }

    public function coach_genres($user_id){
        $ag = DB::table('activity_genres as ag')
            ->leftjoin('user_genres as ug', 'ug.activity_genre_id', '=', 'ag.id')
            //->leftjoin('user_genres as ug', 'u.id', '=', 'ug.user_id')
            ->select('ag.name')
            ->where('ug.user_id',$user_id)
            ->get();
        return $ag;
    }

    public static function getCoachById($coach_id){
        $coaches = DB::table('users as u')
            ->where('u.role','2')
            ->where('u.id',$coach_id)
            ->get();

        return $coaches[0];
    }
    public static function firstCoach(){
        $coaches = DB::table('users as u')
            ->where('u.role','2')
            ->first();
        return $coaches;
    }

    public static function UserPerformanceLevel($user_id){
        $level = DB::table('user_performance_levels as upl')
            ->where('upl.user_id',$user_id)
            ->first();

        return $level->performance_level_id;
    }

    public static function getAllAgency(){
        $agency = DB::table('users as u')
            ->where('u.role',4)
            ->get();

        return $agency;
    }

    public static function getAgencyById($id){
        $agency = DB::table('users as u')
            ->where('u.role',4)
            ->where('u.id',$id)
            ->first();

        return $agency;
    }

    public static function isAgency($id){
        $agency = DB::table('users as u')
            ->where('u.role',4)
            ->where('u.id',$id)
            ->get();
        if(count($agency) > 0)
            return true;
        else
            return false;
        
    }
}
