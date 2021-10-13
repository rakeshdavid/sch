<?php

namespace App\Http\Controllers;


use App\Http\Requests;
use App\Models\UserActivityType;
use App\Models\UserGenre;
use App\Models\UserPerformanceLevel;
use File;
use Image;
use Youtube;
use Validator;
use App\Models\User;
use App\Models\CoachGallery;
use App\Models\CoahcesDocuments;
use App\Models\PerformanceLevel;
use App\Models\ActivityGenre;
use App\Models\ActivityType;
use App\Models\RegisterToken;
use Illuminate\Http\Request;
use App\Http\Helpers\Mailer;
use App\Http\Controllers\AdminController;
use App\Http\Requests\StoreCoachRequest;
use App\Http\Requests\UpdateCoachRequest;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;
use App\Models\Notification;
class AllCoachList extends Controller
{
	private $_user = NULL;

    public function __construct(Request $request, Redirector $redirect)
    {
        $this->_user = auth()->user();
        $this->middleware(['auth']);

        if (empty($this->_user)) {
            $redirect->to('login')->send();
        }
    }

    public function allCoachList(Request $request){
    	$video_id = (int)request()->route('video_id');
        $coaches = User::getAllCoaches();
        $data = array();
        $activity_types = DB::table('activity_genres')->orderBy('name','asc')->distinct()->get();
        $perfomance_levels = PerformanceLevel::get();
        $genres = DB::table('activity_genres')->orderBy('name','asc')->distinct()->get();
        $coach_id = (int)request()->route('coach_id');
        if($coach_id){
            $coach_detail = User::with(['gallery'])->where('id','=',$coach_id)->first();
        }else{
            $coach_detail = User::with(['gallery'])->first();
        }
        // foreach ($coaches as $key => $coache) {
        // 	$id=$coache->id;
        // 	$coach_levl = User::find($coache->id);
          	
        //     $activity_types = ActivityType::get()->toArray();
        //     $perfomance_levels = PerformanceLevel::get()->toArray();
        //     $coachPerformanceLevels = UserPerformanceLevel::where('user_id', $id)->get()->toArray();

        //     // Crunch
        //     $user_crunch = User::find($id);
        //     $user_activity_types = $user_crunch->activity_types()->select('user_activity_types.activity_type_id')->get()->toArray();
        //     $data[] = array('coaches'=>$coache,'coach_level'=>$coach_levl,'activity_types'=>$activity_types,'perfomance_levels'=>$perfomance_levels,'coachPerformanceLevels'=>$coachPerformanceLevels,'user_crunch'=>$user_crunch,'user_activity_types'=>$user_activity_types);
 
        // }
        // $users = DB::table('users as u')
        //     ->leftjoin('user_performance_levels as upl', 'u.id', '=', 'upl.user_id')
        //     ->leftjoin('user_genres as ug', 'u.id', '=', 'ug.user_id')
        //     ->select('u.*', 'upl.performance_level_id', 'ug.activity_genre_id')
        //     ->where('role',2)
        //     ->distinct()->get();
        $model = User::find(1);
        $filters = '';
        $levels_id ='';
        $genres_id ='';
        $name="";
        $page_no = (request()->query('page') !='') ? request()->query('page') : 1;
        return view( 'coaches', ['coaches'=>$coaches,'video_id'=>$video_id,'activity_types'=>$genres,'levels'=>$perfomance_levels,'filters' => $filters,'levels_id'=>$levels_id,'genres_id'=>$genres_id,'coach_detail'=>$coach_detail,'name'=>$name,'page_no'=>$page_no])->withModel($model);
    }

    public function updateVideoaCoach(Request $request){
    	if($request->ajax()){
    		$coachid = request()->get('coachid');
	    	$video_id = request()->get('video_id');
	    	$coachfee = request()->get('coachfee');
            $package_id = request()->get('package_id');
	    	if($coachid !="" && $video_id !="" && $coachfee !=""){
	    		$where = ['user_id'=>$this->_user->id,'id'=>$video_id];
	    		DB::table('videos')->where($where)->update(['coach_id' => $coachid,'video_price'=>$coachfee,'package_id'=>$package_id]);
                $coach = User::find( $coachid );
                self::storeNewVideoNotification($this->_user, $coach, $video_id, 'added a new video! Pending payment!');
                if($package_id == 1){
                    return response()->json(['status'=>200,'message'=>'Updated Successfully!','redirect'=>'/payment/'.$video_id]);
                }else{
                    return response()->json(['status'=>200,'message'=>'Updated Successfully!','redirect'=>'/ask-question/'.$video_id]);
                }
	            
	    	}else{
	    		return response()->json(['status'=>400,'message'=>'Error While Updating!']);
	    	}
    	}else{
    		return response()->json(['status'=>400,'message'=>'Not Ajax request.']);
    	}
    	
    }
    public static function storeNewVideoNotification($user, $receiver, $video_id, $message)
    {
        $data["user_id"] = $receiver->id;
        $data["sender_id"] = $user->id;
        $data["video_id"] = $video_id;
        $data["status"] = 1;
        $data["message"] = '<a href="/profile/' . $user->id . '">' . $user->first_name . ' ' . $user->last_name
            . '</a> ' . $message;
        $data["created_at"] = mysql_date();
        $data["updated_at"] = mysql_date();
        Notification::saveNotification($data);
    }
    public function saveCoachInSession(Request $request){
        if($request->ajax()){
            $coachid = request()->get('coachid');
            $coachfee = request()->get('coachfee');
            $package_id = request()->get('package_id');
            $request->session()->put('coachid',$coachid);
            $request->session()->put('coachfee',$coachfee);
            $request->session()->put('package_id',$package_id);
            return response()->json(['status'=>200,'message'=>'Session set','redirect'=>'/upload-new-video/']);
            
        }
    }

    public function filterCoach(Request $request){
    	$video_id = (int)request()->route('video_id');
    	$activity_types = DB::table('activity_genres')->orderBy('name','asc')->distinct()->get();
        $performance_levels = PerformanceLevel::orderBy('order', 'ASC')->get();
        $name = $request->get('coach-name');
        $genres = [];
        $genres_id = $request->get('genres');
        $levels_id = $request->get('levels');
        $f_genres_id = $request->get('genres');
        $f_levels_id = $request->get('levels');
        $coach_id = (int)request()->route('coach_id');
        if($coach_id){
            $coach_detail = User::with(['gallery'])->where('id','=',$coach_id)->first();
        }else{
            $coach_detail = User::with(['gallery'])->first();
        }
        //crunch
        if ($request->has('genres') or $request->get('levels')){
            $type_id = 1;
            } else { $type_id = 0; }
        //$type_id = (int) $request->get('type');

        $filters = $type_id > 0 ? true : false;
        $filters = (is_array($genres_id) ? true : $filters) || (is_array($levels_id) ? true : $filters);
        //dd($genres_id);
        if(!is_null($genres_id) && !is_array($genres_id)) {
            $genres_id = explode(',', $genres_id);
            $filters = true;
        }
        if(!is_null($levels_id) && !is_array($levels_id)) {
            $levels_id = explode(',', $levels_id);
            $filters = true;
        }
        if($type_id > 0) {
            $genres = ActivityGenre::whereActivityTypeId($type_id)->get();
        }
        $coaches = User::findCoaches($type_id, $genres_id, $levels_id,$name);
        if(count($coaches) > 0){
            $coach_detail = User::with(['gallery'])->where('id','=',$coaches[0]->id)->first();
        }
        $model = User::find(1);
        $page_no = (request()->query('page') !='') ? request()->query('page') : 1;
        return view( 'coaches', [
            'activity_types' => $activity_types,
            'levels' => $performance_levels,
            'genres' => $genres,
            'coaches' => $coaches,
            'video_id'=>$video_id,
            'genres_id' => $f_genres_id,
            'type_id' => $type_id == 0 ? null : $type_id,
            'levels_id' =>$f_levels_id,
            'filters' => $filters,
            'coach_detail'=>$coach_detail,
            'name'=>$name,
            'page_no'=>$page_no
        ])->withModel($model);
    }

    public function coachesList(Request $request){
        $activity_types = ActivityType::all();
        $perfomance_levels = PerformanceLevel::get();
        $model = User::find(1);
        $filters = '';
        $levels_id ='';
        $genres_id ='';
        $f_levels_id ='';
        $f_genres_id ='';
        $coach_id = (int)request()->route('coach_id');
        $page_no = (request()->query('page') !='') ? request()->query('page') : 1;

        $name = "";
        if($request->get('coach-name')){
         //   echo 'dfd';exit;
            $name = $request->get('coach-name');
            $filters = true;
         }
        if($request->get('genres')){
            $genres_id = $request->get('genres');
            $f_genres_id = $request->get('genres');
            }
        if($request->get('levels')){
            $levels_id = $request->get('levels');
            $f_levels_id = $request->get('levels');
        }
        if ($request->has('genres') or $request->get('levels')){
            $type_id = 1;
            $genres = ActivityGenre::whereActivityTypeId($type_id)->get();
         } else { 
            $type_id = 0; 
            $genres = DB::table('activity_genres')->distinct()->get();
         }
         $filters = $type_id > 0 ? true : false;
         $filters = (is_array($genres_id) ? true : $filters) || (is_array($levels_id) ? true : $filters);
         // var_dump($genres_id);
         if(!empty($genres_id) && !is_null($genres_id) && !is_array($genres_id)) {
             $genres_id = explode(',', $genres_id);
             $filters = true;
         }
         if(!empty($levels_id) && !is_null($levels_id) && !is_array($levels_id)) {
             $levels_id = explode(',', $levels_id);
             $filters = true;
         }
         $coaches = [];
         if($request->has('coach-name') or $request->has('genres') or $request->get('levels')){
            // echo 'www';exit;
            $coaches = User::findCoaches($type_id, $genres_id, $levels_id,$name);
         }elseif(!isset($request->single)){
            $coaches = User::getAllCoaches();
         }
        if($coach_id){
            $coach_detail = User::with(['gallery'])->where('id','=',$coach_id)->first();
         }elseif(count($coaches) > 0){
            $coach_detail = User::with(['gallery'])->where('id','=',$coaches[0]->id)->first();
        }else{
            $coach_detail = User::with(['gallery'])->first();
        }  
        return view( 'allcoaches', ['coaches'=>$coaches,'activity_types'=>$genres,'levels'=>$perfomance_levels,'filters' => $filters,'levels_id'=>$f_levels_id,'genres_id'=>$f_genres_id,'coach_detail'=>$coach_detail,'name'=>$name,'page_no'=>$page_no])->withModel($model);
    }

    public function filterCoachesList(Request $request){

    	$activity_types = DB::table('activity_genres')->orderBy('name','asc')->distinct()->get();
        $performance_levels = PerformanceLevel::orderBy('order', 'ASC')->get();
        $genres = [];
        $genres_id = $request->get('genres');
        $levels_id = $request->get('levels');
      //   dd($request->toArray());
         //   var_dump($levels_id);exit;
        $f_genres_id = $request->get('genres');
        $f_levels_id = $request->get('levels');
        $name = $request->get('coach-name');
        $coach_id = (int)request()->route('coach_id');
        if($coach_id){
            $coach_detail = User::with(['gallery'])->where('id','=',$coach_id)->first();
        }else{
            $coach_detail = User::with(['gallery'])->first();
        }
        // print_r($coach_detail);
        // die();
        //crunch
        if ($request->has('genres') or $request->get('levels')){
            $type_id = 1;
            } else { $type_id = 0; }
        //$type_id = (int) $request->get('type');

        $filters = $type_id > 0 ? true : false;
        $filters = (is_array($genres_id) ? true : $filters) || (is_array($levels_id) ? true : $filters);
        //dd($genres_id);
        if(!empty($genres_id) && !is_null($genres_id) && !is_array($genres_id)) {
            $genres_id = explode(',', $genres_id);
            $filters = true;
        }
        if(!empty($levels_id) && !is_null($levels_id) && !is_array($levels_id)) {
            $levels_id = explode(',', $levels_id);
            $filters = true;
        }
        if($type_id > 0) {
            $genres = ActivityGenre::whereActivityTypeId($type_id)->get();
        }
        $coaches = User::findCoaches($type_id, $genres_id, $levels_id,$name);
      //   echo '<pre>';print_r(DB::getQueryLog());exit;
        if(count($coaches) > 0){
            $coach_detail = User::with(['gallery'])->where('id','=',$coaches[0]->id)->first();
        }
        $model = User::find(1);
        $page_no = (request()->query('page') !='') ? request()->query('page') : 1;
       // print_r($coaches);
        return view( 'allcoaches', [
            'activity_types' => $activity_types,
            'levels' => $performance_levels,
            'genres' => $genres,
            'coaches' => $coaches,
            'genres_id' => $f_genres_id,
            'type_id' => $type_id == 0 ? null : $type_id,
            'levels_id' => $f_levels_id,
            'filters' => $filters,
            'coach_detail'=>$coach_detail,
            'name'=>$name,
            'page_no'=>$page_no
        ])->withModel($model);
    }
} 
 