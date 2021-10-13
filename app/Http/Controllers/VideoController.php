<?php

namespace App\Http\Controllers;

use App\Models\PerformanceLevel;
use App\Models\ReviewQuestion;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use App\Models\Video;
use App\Models\User;
use App\Models\Review;
use App\Models\AuditionList;
use App\Models\Notification;
use App\Models\ChallengesParticipant;
use Illuminate\Support\Facades\Log;
use Youtube;
use Storage;
use App\Jobs\ReformatUserVideo;
use DevsWebDev\DevTube\Download;
class VideoController extends Controller
{
    /** @var User $_user */
    private $_user = NULL;

    public function __construct(Request $request, Redirector $redirect)
    {
        $this->_user = auth()->user();
        $this->middleware(['auth']);

        if (empty($this->_user)) {
            $redirect->to('login')->send();
        }
    }

    public function index(Request $request)
    {
        abort_if($this->_user->isCoach(), 404);
        $name = $request->input('video-name');
        $allvideos = $this->_user->videos()->orderBy('created_at', 'DESC')->getQuery()//TODO: uncomment after payment system implementation
                ->where('name','like','%'.$name.'%')
                ->paginate(12);
        $pendingPayment = $this->_user->videos()->orderBy('created_at', 'DESC')->getQuery()
                ->where( 'pay_status', '=', 0)
                ->where('name','like','%'.$name.'%')
                ->paginate(12);
        $waitingreview = $this->_user->videos()->orderBy('created_at', 'DESC')->getQuery()
                ->where( 'pay_status', '=', 1)
                ->where('started_review_status','=',0)
                ->where('name','like','%'.$name.'%')
                ->paginate(12);
        // $reviewed = $this->_user->videos()->orderBy('created_at', 'DESC')->getQuery()
        //         ->where( 'pay_status', '=', 1)
        //         ->where('started_review_status','=',1)
        //         ->where('name','like','%'.$name.'%')
        //         ->paginate(12);
        $reviewed = Video::with(['review'])->where('user_id', $this->_user->id)->where( 'pay_status', '=', 1)->where('started_review_status','=',1)->where('name','like','%'.$name.'%')->orderBy('created_at', 'DESC')->paginate(12);
        $videoratings = Video::with(['review'])->where('user_id', $this->_user->id)->where( 'pay_status', '=', 1)->where('started_review_status','=',1)->get();
        $auditionpendingPay =AuditionList::with(['audition'])->where('user_id', $this->_user->id)
                ->where( 'payment_status', '=', 0)
                //->where('audition_name','like','%'.$name.'%')
                ->paginate(12);
        $temp_participants = AuditionList::with(['auditionreviewnew'])->where('payment_status','=', 1)->where( 'stripe_id', '!=', 'NULL')->where('user_id','=',$this->_user->id)->paginate(12);
        $challengePendingPay = ChallengesParticipant::with(['challenges'])->where('user_id', $this->_user->id)
                ->where( 'payment_status', '=', 0)
                ->paginate(12);
        $challenge_participants = ChallengesParticipant::with(['review'])->where('payment_status','=', 1)->where( 'stripe_id', '!=', 'NULL')->where('user_id','=',$this->_user->id)->paginate(12);
        // echo '<pre>';
        // //print_r($videoratings);

        // echo '</pre>';
        //echo $request->get('video-name');
        //die();
        $model = Review::find(2);
        
        return view('video/index', [
            'videos' =>$allvideos,
            'pendingpayment'=>$pendingPayment,
            'waitingreview'=>$waitingreview,
            'reviewed'=>$reviewed,
            'videoratings'=>$videoratings,
            'searchterm'=>$name,
            'autidionpendingpay'=>$auditionpendingPay,
            'auditionwaitingreview'=>$temp_participants,
            'challengependingpay'=>$challengePendingPay,
            'challengewaitingreview'=>$challenge_participants
        ])->withModel($model);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $coach_id = (int)request()->get('coach');
        $coach = User::whereRole(User::COACH_ROLE)->whereId($coach_id)->first();
        abort_if(is_null($coach), 404);
        $detailed_package = false;
        if (request()->has('package')) {
            $detailed_package = request()->get('package') == "detailed" ? true : false;
        }
        $performance_levels = PerformanceLevel::orderBy('order', 'ASC')->get();
        $user = auth()->user();
        if ($this->_user->role == User::USER_ROLE) {
            $questions_labels = [1 => 'First', 2 => 'Second', 3 => 'Third'];
            return view('video/create', ['coach' => $coach, 'performance_levels' => $performance_levels,
                'questions_labels' => $questions_labels, 'detailed_package' => $detailed_package, 'user' => $user]);
        }
    }

    public function fallbackStatus(Request $request)
    {
        abort_unless($request->ajax(), 404);
        abort_unless($this->_user->isCoach(), 404);
        $video_id = $request->get('video');
        $result = Video::setStatus($video_id, $this->_user->id);
        return response()->json(['success' => $result]);
    }

    public function store(Request $request)
    {
        $coach = User::find($request->get('coach'));
        $this->validate($request, Video::$rules, Video::$messages);

        $user_data = $request->only(User::$public_fields);
        $user_update = [];
        if (is_array($user_data)) {
            foreach ($user_data as $key => $val) {
                if (!is_null($val)) {
                    $user_update[$key] = $val;
                }
            }
            $user_update['other_site_spec'] = $user_update['site_target'] != 'other' ? '' : $user_update['other_site_spec'];
        }

        if (count($user_update)) {
            $rules = array_intersect_key(User::$internal_rules, $user_update);
            $this->validate($request, $rules);
            User::whereId($this->_user->id)->update($user_update);
        }

        abort_if(is_null($coach), 404);
        if (!($this->_user->role == User::USER_ROLE)) {
            return \Response::redirectToIntended();
        }

        if (count($request->get('question')) > 0) {
            $v_price = User::select('price_detailed')->where('id', (int)$request->get('coach'))->first()->price_detailed;
            $v_price = ($v_price) ? $v_price : env('DEFAULT_SUMMARY_DETAILED');
        } else {
            $v_price = User::select('price_summary')->where('id', (int)$request->get('coach'))->first()->price_summary;
            $v_price = ($v_price) ? $v_price : env('DEFAULT_SUMMARY_PRICE');
        }

        $video = Video::create($request->except('genres'));
        $video->url = $request->get('file_name');
        $video->status = Video::STATUS_NEW;
        $user = User::find($this->_user->id);
        $video->user()->associate($user);
        $video->coach()->associate($coach);
        $video->activity_genres()->attach($request->get('genres'));
        $questions_req = $request->get('question');
        if (!empty($questions_req)) {
            $questions = [];
            $i = 0;
            foreach ($questions_req as $key => $question_req) {
                if ($question_req) {
                    $i++;
                    $questions[] = new ReviewQuestion(['question' => $question_req, 'question_number' => $i]);
                }
            }
            if (!empty($questions)) {
                $video->questions()->saveMany($questions);
            }
        }

        $video->video_price = (int)$v_price * 100; //to->cent
        $video->update();

        $job = (new ReformatUserVideo(['video_id' => $video->id]));
        dispatch($job);

        self::storeNewVideoNotification($this->_user, $coach, $video->id, 'added a new video! Pending payment!');

        return back()->with([
            'status' => 'Video successfully added!',
            'created_video_id' => $video->id,
        ]);
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

    private function getVideoCode($url = '')
    {
        if (empty($url))
            return '';

        $code = $url;
        if (filter_var($url, FILTER_VALIDATE_URL)) {

            $parts = parse_url($url);

            if ($parts["path"] == "/watch") {
                parse_str($parts['query'], $query);
                $code = $query['v'];
            } elseif ($parts["path"][0] == '/') {
                $code = substr($parts["path"], 1);
            }
        }

        return $code;
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if ($this->_user->role == User::USER_ROLE) {
            $result = Video::getById($id);

            return view('video/show', ["video" => $result]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if ($this->_user->role == User::USER_ROLE) {
            $result = Video::getById($id);

            return view('video/edit', ["video" => $result]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
//        $result = Video::delete($id);
    }

    public function saveFile(Request $request)
    {
        if ($request->get('chunk') == '0') {
            $originalName = $request->get('name');
            $filePath = storage_path() . '/plupload/' . $originalName . '.part';
            if (file_exists($filePath) && filemtime($filePath) < time() - 600) {
                @unlink($filePath);
            }
        }
        return \Plupload::receive('file', function ($file) {
            $fileName = str_random(3) . uniqid() . "." . $file->getClientOriginalExtension();
            $file->move(public_path() . config('video.user_video_path'), $fileName);
            return ['result' => 'ready', 'file' => $fileName];
        });
    }

    public function fallbackVideoFile(Request $request)
    {
        abort_unless($request->ajax(), 404);
        abort_unless($this->_user->isUser(), 404);

        if(is_file(public_path(config('video.user_video_path')) . $request->videofile)){
            unlink(public_path(config('video.user_video_path')) . $request->videofile);
            return json_encode(['success' => true]);
        }else{
            return json_encode(['success' => false]);
        }

    }
    public function download()
    {
    $dl = new Download($url = "https://www.youtube.com/watch?v=ye5BuYf8q4o", $format = "mp4", $download_path = "/home/beta-showcase/www/public/music" );

    //Saves the file to specified directory
    $media_info = $dl->download();
    $media_info = $media_info->first();

    // Return as a download
    return response()->download($media_info['file']->getPathname());

    }

    public function updateVideoSource(){
        $video_id = request()->get('video_id');
        if($this->updateVideoSourceRepeat($video_id)){
            $result = Video::getById($video_id);
            $videourl = $result->url;
            return response()->json(['status'=>200,'message'=>'Successfully get!','video_src'=>$videourl]);
        }else{
            $result = Video::getById($video_id);
            $videourl = $result->url;
            return response()->json(['status'=>200,'message'=>'Successfully get!','video_src'=>$videourl]); 
        }
        
    }

    public function updateVideoSourceRepeat($video_id){

        $result = Video::getById($video_id);
        $videourl = $result->url;
        if(strpos($videourl, 'formatted') !== false){
            return true;
        }else{
            $this->updateVideoSourceRepeat($video_id);
        }
    }
}
