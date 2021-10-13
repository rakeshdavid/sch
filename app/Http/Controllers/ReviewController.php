<?php

namespace App\Http\Controllers;

use App\Models\PerformanceLevel;
use App\Models\ReviewQuestion;
use App\Models\TemporaryReview;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use App\Http\Requests;
use App\Models\Video;
use App\Models\User;
use App\Models\Review;
use App\Models\Notification;
use App\Models\Transaction;
use File;
use Storage;
use Carbon\Carbon;
use App\Http\Helpers\Mailer;
use Illuminate\Support\Facades\DB;
use App\Jobs\ConcatReviewVideoAudio;
use Illuminate\Support\Facades\Log;
use Validator;
use Session;
class ReviewController extends Controller
{
    private $_user = null;

    public function __construct(Request $request, Redirector $redirect)
    {

        $this->_user = $request->user();

        if (empty($this->_user)) {
            $redirect->to('login')->send();
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if ($this->_user->role == User::COACH_ROLE) {
            $result = Review::getByUserIdList($this->_user->id);

            foreach ($result as &$review)
                if (!empty($review)) {
                    $review->overall_rating = round((
                            $review->artisty +
                            $review->formation +
                            $review->interpretation +
                            $review->creativity +
                            $review->style +
                            $review->energy +
                            $review->precision +
                            $review->timing +
                            $review->footwork +
                            $review->alingment +
                            $review->balance +
                            $review->focus
                        ) / 12, 2);

                    $review->days_ago = floor((time() - strtotime($review->created_at)) / 3600 / 24);
                }
            return view('review/index', ["reviews" => $result]);
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        if ($this->_user->role == User::COACH_ROLE) {
            //$video = Video::getById($id);
            $performance_levels = PerformanceLevel::all();
            $video = Video::where(['id' => $id, 'coach_id' => auth()->user()->id])
                ->with(['temporary_review', 'review'])
                ->firstOrFail();
            if(!$video->review) Video::changeStatus($id, Video::STATUS_ACCEPT_PROPOSAL);

            /* select perfomer data */
            $user = DB::table('videos as v')
                ->join('users as u', 'v.user_id', '=', 'u.id')
                ->where('v.id', '=', $id)
                ->select("u.first_name as user_first_name", "u.id as user_id", "u.email as mail")
                ->first();


            if ($video->started_review_status == 0) {
                Video::where('id', $id)->update(['started_review_status' => 1]);
                $mail = new Mailer();
                $mail->subject = 'Coach started a review';
                $mail->to_email = $user->mail;
                $mail->sendMail('auth.emails.coachStartedReview',
                    [
                        'user_name' => $user->user_first_name,
                        'coach_name' => $this->_user->first_name
                    ]);
            }

            if($video->temporary_review){
                return view('review/create_first_step_temp', ["video" => $video, 'performance_levels' => $performance_levels]);
            }else{
                return view('review/create_first_step_new', ["video" => $video, 'performance_levels' => $performance_levels]);
            }
        }
    }

    public function createSecondStep($video_id)
    {
        if ($this->_user->role == User::COACH_ROLE) {
            //$video = Video::getById($id);
            $performance_levels = PerformanceLevel::all();
            $video = Video::whereId($video_id)->with(['temporary_review', 'review'])->first();
            
            if($video->temporary_review){
                $temp_video = $video->temporary_review->review_url;
                $temp_path = public_path(config('video.temp_review_path')) . $temp_video;
                if(!file_exists($temp_path) || $video->temporary_review->review_url == NULL){
                    TemporaryReview::where('id',$video->temporary_review->id)->delete();
                    return redirect('review/create/' . $video_id)->withErrors(['Please Re-record again. Temp review or file does not exist.']);
                }
            }

            if($video->temporary_review){
                if($video->package_id == 1){
                    return view('review/create_audition_second_step', ["video" => $video, 'performance_levels' => $performance_levels]);
                }else{
                    return view('review/create_second_step', ["video" => $video, 'performance_levels' => $performance_levels]);
                }
            }else{
                return redirect('review/create/' . $video_id);
            }
        }else{
            abort(404);
        }
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

//        if ($this->_user->role == User::COACH_ROLE) {
//            $filename = $request->filename;
//            $video_id = $request->video_id;
//            $data['url'] = "/audio/" . $filename;
//            $data["user_id"] = $this->_user->id;
//            $data["video_id"] = $video_id;
//            $data["status"] = 1;
//            $data["play_time"] = $request->play_time;
//            $data["created_at"] = mysql_date();
//            $data["updated_at"] = mysql_date();
//            $id = Review::saveReview($data);
//
//            //Video::changeStatus($video_id, Video::STATUS_REVIEWED);
//
//            return response()->json(["status" => "success", "message" => "Review successfully added.", "review_id" => $id]);
//        }

        if ($this->_user->role == User::COACH_ROLE) {
          //  $data['url'] = "/audio/" . $request->filename;
            $data['url'] = $request->filename;
            $data["user_id"] = $this->_user->id;
            $data["video_id"] = $request->video_id;
            $data["play_time"] = $request->play_time;
            $data["created_at"] = mysql_date();
            $data["updated_at"] = mysql_date();
            $id = TemporaryReview::saveTempReview($data);

            $job = (new ConcatReviewVideoAudio(['video_id' => $request->get('video_id')]));
            dispatch($job);

            return response()->json(["status" => "success", "message" => "Temporary Review successfully added.", "review_id" => $id]);
        }


    }

    public function storeAudioFile(Request $request)
    {
        //abort_unless($this->_user->isCoach(), 404);
        if( !$this->_user->isCoach() && !$this->_user->role == User::AGENCY_ROLE){
            abort(404);
        }
        if ($request->hasFile('chunk')) {
            $temp_path = public_path(config('video.review_audio_path') . 'temp');
            if (!File::exists($temp_path)) {
                File::makeDirectory($temp_path, 0755, true);
            }
            $part = $request->file('chunk');
            $chunk = file_get_contents($part);
            $file = $temp_path . $request->get('name');
            file_put_contents($file, $chunk, FILE_APPEND | LOCK_EX);
            $total = (int)$request->get('total');
            $current = (int)$request->get('current');
            if ($current < $total) {
                return json_encode(['status' => 'progress', 'success' => true]);
            }
            $filename = $this->_user->id . '_' . $request->get('name');
            $final_path = public_path(config('video.review_audio_path')) . $filename;
            File::move($file, $final_path);

            return response()->json(['status' => 'complete', 'success' => true, 'name' => $filename]);
        } else
            abort(403);
    }

    public function saveRatings(Request $request)
    {
        if ($request->isMethod('post') && $this->_user->role == User::COACH_ROLE) {

            $ratings = $request->ratings;

            /*
            TODO::Create a review video separated into different entities, data validation is not found.
            It would be nice to do one handler. And do validation and verification there. In one place.
            */
            /*TECHNIQUE SCORE, EXPRESSION, CHOREOGRAPHY validation block*/
            $rules = array(
               'timing' => 'required|numeric|min:0|not_in:0',
               'footwork' => 'required|numeric|min:0|not_in:0',
               'alingment' => 'required|numeric|min:0|not_in:0',
               'balance' => 'required|numeric|min:0|not_in:0',
               'focus' => 'required|numeric|min:0|not_in:0',
               'precision' => 'required|numeric|min:0|not_in:0',
               'energy' => 'required|numeric|min:0|not_in:0',
               'style' => 'required|numeric|min:0|not_in:0',
               'creativity' => 'required|numeric|min:0|not_in:0',
               'interpretation' => 'required|numeric|min:0|not_in:0',
               'formation' => 'required|numeric|min:0|not_in:0',
               'artisty' => 'required|numeric|min:0|not_in:0',
                'timing_comment' => 'required|string|max:500',
                'footwork_comment' => 'required|string|max:500',
                'alingment_comment' => 'required|string|max:500',
                'balance_comment' => 'required|string|max:500',
                'focus_comment' => 'required|string|max:500',
                'precision_comment' => 'required|string|max:500',
                'energy_comment' => 'required|string|max:500',
                'style_comment' => 'required|string|max:500',
                'creativity_comment' => 'required|string|max:500',
                'interpretation_comment' => 'required|string|max:500',
                'formation_comment' => 'required|string|max:500',
                'artisty_comment' => 'required|string|max:500',
            );
            $valMsg = [
               'required' => 'The :attribute field is required!',
               'max' => 'Maximum length of the field :attribute is 500 characters!',
            ];
            $fealdName = [
               'timing' => 'Timing Rating',
               'footwork' => 'Footwork Rating',
               'alingment' => 'Alignment Rating',
               'balance' => 'Balance Rating',
               'focus' => 'Focus Rating',
               'precision' => 'Precision Rating',
               'energy' => 'Energy Rating',
               'style' => 'Style Rating',
               'creativity' => 'Creativity Rating',
               'interpretation' => 'Interpretation Rating',
               'formation' => 'Formation Rating',
               'artisty' => 'Artisty Rating',
               'timing_comment' => 'Timing Comment',
                'footwork_comment' => 'Footwork Comment',
                'alingment_comment' => 'Alignment Comment',
                'balance_comment' => 'Balance Comment',
                'focus_comment' => 'Focus Comment',
                'precision_comment' => 'Precision Comment',
                'energy_comment' => 'Energy Comment',
                'style_comment' => 'Style Comment',
                'creativity_comment' => 'Creativity Comment',
                'interpretation_comment' => 'Interpretation Comment',
                'formation_comment' => 'Formation Comment',
                'artisty_comment' => 'Artistry Comment',
            ];
            $validator = \Validator::make($ratings, $rules, $valMsg);
            $validator->setAttributeNames($fealdName);
            if ($validator->fails()) return json_encode(['status' => 'val_error', 'errors' => $validator->errors()]);
            /*validation block end*/

            $video = Video::whereId($request->video_id)->with(['review','temporary_review'])->first();
            if($video->review){
                Review::removeReviewFile($video->review->review_url);
                $id = $video->review->id;
                Review::whereId($video->review->id)->update([
                "url" => $video->temporary_review->url,
                "user_id" => $video->user_id,
                "video_id" => $video->id,
                "review_url" => $video->temporary_review->review_url,
                "status" => 3,
                "play_time" => $video->temporary_review->play_time,
                "updated_at" => mysql_date()
                ]);
                Video::where('id', $video->id)->update(['status' => 3]);
            }else{
                $data['url'] = $video->temporary_review->url;
                $data["user_id"] = $video->user_id;
                $data["video_id"] = $video->id;
                $data["review_url"] = $video->temporary_review->review_url;
                $data["status"] = 3;
                $data["play_time"] = $video->temporary_review->play_time;
                $data["created_at"] = mysql_date();
                $data["updated_at"] = mysql_date();
                $id = Review::saveReview($data);
                Video::where('id', $video->id)->update(['status' => 3]);
            }


            Review::setRating($id, $ratings);
            Review::levelPlacement($id, $request->get('performance_level_placement'));
            $answers = $request->get('answers');
            $video_id = $request->get('video_id');
            $result = ReviewQuestion::saveAnswers($video_id, $answers);
            $owner_id = $request->get('owner_id');

            //вынести
            /*            $notification["user_id"] = $owner_id;
                        $notification["sender_id"] = $this->_user->id;
                        $notification["video_id"] = $video_id;
                        $notification["status"] = 1;
                        $notification["message"] = '<a href="/profile/'. $this->_user->id . '">' . $this->_user->first_name
                            . ' ' . $this->_user->last_name . '</a> add new review.';
                        $notification["created_at"] = mysql_date();
                        $notification["updated_at"] = mysql_date();
                        Notification::saveNotification($notification);*/

            //вынести
            //Video::changeStatus($video_id, Video::STATUS_REVIEWED);

            TemporaryReview::moveTemporaryReviewData($video->temporary_review->id);
            $completed_review_path = public_path() . config('video.completed_review_path');
            self::checkIfApproved($video);
            if(!file_exists($completed_review_path . $video->temporary_review->review_url)){
                TemporaryReview::where('id',$video->temporary_review->id)->delete();
                Video::where('id', $video->id)->update(['started_review_status' => 1]);
                session()->flash('message', 'Something went wrong...please rerecord review again!');
                return redirect('review/create/' . $video->id); 
            }
           
            return response()->json(["status" => "success", "message" => "Review successfully added.", "review_id" => $id]);
        }

    }

    public function checkIfApproved($video)
    {
        if($video->status == Video::STATUS_REVIEWED) return true;

        if( !Transaction::where('video_id', $video->id)->where('participation_type',Video::PARTICIPATION_TYPE)->exists() ) { // !Transaction
            $transfer = PaymentsController::transfer( $video->id );
            if($transfer===false){
                return json_encode(['error'=>true, 'msg'=>'Transfer error. Try later.']);
            }
        }
        $user_id = $video->user_id;
        $notification["user_id"] = $user_id;
        $notification["sender_id"] = $this->_user->id;
        $notification["video_id"] = $video->id;
        $notification["status"] = 1;
        $notification["message"] = '<a href="/profile/' . $this->_user->id . '">' . $this->_user->first_name
            . ' ' . $this->_user->last_name . '</a> added a new review.';
        $notification["created_at"] = mysql_date();
        $notification["updated_at"] = mysql_date();
        Notification::saveNotification($notification);

        Video::changeStatus($video->id, Video::STATUS_REVIEWED);
        /* mail to performer and coach */

        $performer = User::select('first_name', 'email')->where('id', $user_id)->first();
        $performer_mail = new Mailer();
        $performer_mail->subject = 'Coach completed a review ';
        $performer_mail->to_email = $performer->email;
        $performer_mail->sendMail('auth.emails.coachCompletedReview',
            [
                'reviewer' => 'coach',
                'user_name' => $performer->first_name,
                'coach_name' => $this->_user->first_name
            ]);

        $coach_mail = new Mailer();
        $coach_mail->subject = 'Payment sent by Showcase';
        $coach_mail->to_email = $this->_user->email;
        $coach_mail->sendMail('auth.emails.paymentSentByShowcase',
            [
                'user_name' => $performer->first_name,
                'coach_name' => $this->_user->first_name
            ]);


        return true;
    }

//    public function approvedVideo(Request $request)
//    {
//        $video_id = $request->get('video_id');
//        $video = Video::where('id', $video_id)->select('id', 'user_id', 'status')->first();
//        $user_id = $video->user_id;
//
//        if ($video->status == 3) {
//            return json_encode(['error' => true, 'msg' => 'Video already approved!']);
//        }
//
//        if( !Transaction::where('video_id', $video_id)->exists() ) { // !Transaction
//            $transfer = PaymentsController::transfer( $request->get('video_id') );
//            if($transfer===false){
//                return json_encode(['error'=>true, 'msg'=>'Transfer error. Try later.']);
//            }
//        }
//
//        $notification["user_id"] = $user_id;
//        $notification["sender_id"] = $this->_user->id;
//        $notification["video_id"] = $video_id;
//        $notification["status"] = 1;
//        $notification["message"] = '<a href="/profile/' . $this->_user->id . '">' . $this->_user->first_name
//            . ' ' . $this->_user->last_name . '</a> added a new review.';
//        $notification["created_at"] = mysql_date();
//        $notification["updated_at"] = mysql_date();
//        Notification::saveNotification($notification);
//
//        Video::changeStatus($video_id, Video::STATUS_REVIEWED);
//        /* mail to performer and coach */
//
//        $performer = User::select('first_name', 'email')->where('id', $user_id)->first();
//        $performer_mail = new Mailer();
//        $performer_mail->subject = 'Coach completed a review ';
//        $performer_mail->to_email = $performer->email;
//        $performer_mail->sendMail('auth.emails.coachCompletedReview',
//            [
//                'user_name' => $performer->first_name,
//                'coach_name' => $this->_user->first_name
//            ]);
//
//        $coach_mail = new Mailer();
//        $coach_mail->subject = 'Payment sent by Showcase';
//        $coach_mail->to_email = $this->_user->email;
//        $coach_mail->sendMail('auth.emails.paymentSentByShowcase',
//            [
//                'user_name' => $performer->first_name,
//                'coach_name' => $this->_user->first_name
//            ]);
//
//
//        return json_encode(['error' => false, 'msg' => 'Video successfully approved!']);
//    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if ($this->_user->role == User::USER_ROLE) {
            //$result = Review::getByVideoIdFull($id);
            $result = Review::where('video_id', $id)->first();
            abort_if(is_null($result), 404);
            $result->overall_rating = round((
                    $result->artisty +
                    $result->formation +
                    $result->interpretation +
                    $result->creativity +
                    $result->style +
                    $result->energy +
                    $result->precision +
                    $result->timing +
                    $result->footwork +
                    $result->alingment +
                    $result->balance +
                    $result->focus
                ) / 12, 1);
            try {
                $result->days_ago = Carbon::parse($result->created_at)->diffForHumans();
            } catch (\Exception $exception) {
                $result->days_ago = '';
            }
            return view('review/show', ["review" => $result]);
        }
    }

    public function showMy($id)
    {
        if ($this->_user->role == User::COACH_ROLE) {
            //$result = Review::getByUserIdFull($id, $this->_user->id);
            $video = Video::where('id', $id)->select('id', 'user_id', 'status')->first();
            $result = Review::where('video_id', $id)->first();
            $package_id = Video::getVideoPackage($id);
            if($package_id == 1){
                $result->overall_rating = round((
                            $result->performance_quality_rating +
                            $result->technical_ability_rating +
                            $result->energy_style_rating +
                            $result->storytelling_rating +
                            $result->look_appearance_rating
                            
                            ) / 5, 2);
            }else{
                $result->overall_rating = round((
                    $result->artisty +
                    $result->formation +
                    $result->interpretation +
                    $result->creativity +
                    $result->style +
                    $result->energy +
                    $result->precision +
                    $result->timing +
                    $result->footwork +
                    $result->alingment +
                    $result->balance +
                    $result->focus
                ) / 12, 1);
            }
            $temp = &$result;
            $temp->package_id = $package_id;
            //$result->days_ago = floor((time() - strtotime($result->created_at))/3600/24);
            try {
                $result->days_ago = Carbon::parse($result->created_at)->diffForHumans();
            } catch (\Exception $exception) {
                $result->days_ago = '';
            }
//            dd($result->toArray());
            return view('review/show-completed', ["review" => $result, "video_id" => $id, "video_status" => $video->status]);
        }
    }

    public function saveAuditionRatings(Request $request)
    {
        
        if ($request->isMethod('post') && $this->_user->role == User::COACH_ROLE) {
            
            /*
            TODO::Create a review video separated into different entities, data validation is not found.
            It would be nice to do one handler. And do validation and verification there. In one place.
            */
            /*TECHNIQUE SCORE, EXPRESSION, CHOREOGRAPHY validation block*/
            $rules = [
               'pq-rating' => 'required|numeric|min:0|not_in:0',
               'ta-rating' => 'required|numeric|min:0|not_in:0',
               'es-rating' => 'required|numeric|min:0|not_in:0',
               'storytelling-rating' => 'required|numeric|min:0|not_in:0',
               'la-rating' => 'required|numeric|min:0|not_in:0',
                'performance-quality' => 'required|max:500',
                'technical-ability' =>  'required|max:500',
                'energy-and-style' => 'required|max:500',
                'storytelling' =>  'required|max:500',
                'look-and-appearance' =>'required|max:500',
            ];           
            $valMsg = [
               'required' => 'The :attribute field is required!',
               'max' => 'Maximum length of the field :attribute is 500 characters!',
            ];
            $fealdName = [
               'pq-rating' => 'Performance Quality Rating',
               'ta-rating' => 'Technical Ability Rating',
               'es-rating' => 'Energy and Style Rating',
               'storytelling-rating' => 'Story Telling Rating',
               'la-rating' => 'Look and Appearance Rating',
               'performance-quality' => 'Performance Quality',
               'technical-ability' =>  'Technical Ability',
               'energy-and-style' => 'Energy and Style',
               'storytelling' =>  'Story Telling',
               'look-and-appearance' =>'Look and Appearance',
            ];
            $validator = \Validator::make($request->all(), $rules, $valMsg);
            $validator->setAttributeNames($fealdName);            
            if ($validator->fails()){
                //return json_encode(['status' => 'val_error', 'errors' => $validator->errors()]);
                return redirect('review/create/second_step/'.$request->video_id)->withErrors($validator)->withInput();
            }
            /*validation block end*/

            $video = Video::whereId($request->video_id)->with(['review','temporary_review'])->first();
            $temp_path = public_path(config('video.temp_review_path')) . @$video->temporary_review->review_url;
            if(!$video->temporary_review || empty(@$video->temporary_review->review_url) || empty(@$video->temporary_review->url) || !file_exists($temp_path)){
                if($request->wantsJson()){
                    return response()->json(['status' => 'error', "message" => "Please Re-record again. Temp review or file does not exist."]);
                }      
                return redirect('review/create/'.$request->video_id)->withErrors(['Please Re-record again. Temp review or file does not exist.']);
            }
            if($video->review){
                Review::removeReviewFile($video->review->review_url);
                $id = $video->review->id;
                Review::whereId($video->review->id)->update([
                "url" => $video->temporary_review->url,
                "user_id" => $video->user_id,
                "video_id" => $video->id,
                "review_url" => $video->temporary_review->review_url,
                "status" => 3,
                "play_time" => $video->temporary_review->play_time,
                "updated_at" => mysql_date()
                ]);
                Video::where('id', $video->id)->update(['status' => 3]);
            }else{
                $data['url'] = $video->temporary_review->url;
                $data["user_id"] = $video->user_id;
                $data["video_id"] = $video->id;
                $data["review_url"] = $video->temporary_review->review_url;
                $data["status"] =3;
                $data["play_time"] = $video->temporary_review->play_time;
                $data["created_at"] = mysql_date();
                $data["updated_at"] = mysql_date();
                $id = Review::saveReview($data);
                Video::where('id', $video->id)->update(['status' => 3]);
            }
            $ratings = array(
                'performance_quality'=>$request->input('performance-quality'),
                'performance_quality_rating'=>$request->input('pq-rating'),
                'technical_ability'=>$request->input('technical-ability'),
                'technical_ability_rating'=>$request->input('ta-rating'),
                'energy_style'=>$request->input('energy-and-style'),
                'energy_style_rating'=>$request->input('es-rating'),
                'storytelling'=>$request->input('storytelling'),
                'storytelling_rating'=>$request->input('storytelling-rating'),
                'look_appearance'=>$request->input('look-and-appearance'),
                'look_appearance_rating'=>$request->input('la-rating'),
                'message'=>$request->input('review_message'),
                'performance_level_id'=>$request->input('performance_level_placement'),
            );

            Review::setRating($id, $ratings);
            Review::levelPlacement($id, $request->get('performance_level_placement'));
            $answers = $request->get('answers');
            $video_id = $request->get('video_id');
            $result = ReviewQuestion::saveAnswers($video_id, $answers);
            $owner_id = $request->get('owner_id');

            //вынести
            /*            $notification["user_id"] = $owner_id;
                        $notification["sender_id"] = $this->_user->id;
                        $notification["video_id"] = $video_id;
                        $notification["status"] = 1;
                        $notification["message"] = '<a href="/profile/'. $this->_user->id . '">' . $this->_user->first_name
                            . ' ' . $this->_user->last_name . '</a> add new review.';
                        $notification["created_at"] = mysql_date();
                        $notification["updated_at"] = mysql_date();
                        Notification::saveNotification($notification);*/

            //вынести
            //Video::changeStatus($video_id, Video::STATUS_REVIEWED);

            TemporaryReview::moveTemporaryReviewData($video->temporary_review->id);
            self::checkIfApproved($video);
            //return response()->json(["status" => "success", "message" => "Review successfully added.", "review_id" => $id]);
            return redirect()->to('/review/show-my/'.$video_id);
            //return redirect()->back()->with('message', 'Review successfully added.');
        }
        return redirect()->to('/review/show-my/'.$request->get('video_id'));
        //return redirect()->back()->with('message', 'Review successfully added.');

    }

}
