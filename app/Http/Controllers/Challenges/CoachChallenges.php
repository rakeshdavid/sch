<?php

namespace App\Http\Controllers\Challenges;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\PaymentsController;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\UserActivityType;
use App\Models\ActivityGenre;
use App\Models\PerformanceLevel;
use App\Models\ChallengeReview;
use App\Models\ChallengeReviewNew;
use App\Models\ChallengeTempReview;
use App\Models\Challenges;
use App\Models\ChallengesParticipant;
use App\Models\User;
use App\Models\Notification;
use App\Models\Transaction;
use App\Jobs\ConcatChallengeReviewVideoAudio;
use File;
use Storage;
use App\Http\Helpers\Mailer;
class CoachChallenges extends Controller
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

    public function allChallenges(Request $request, Redirector $redirect){
    	$challenges = $this->_user->coachChallenges()->orderBy('created_at', 'DESC')->getQuery()//
               ->paginate(10);
                
    	return view('coachchallenge.index',['challenges'=>$challenges]);
    }

    public function newChallenge(Request $request, Redirector $redirect){
        if($request->method() == 'POST'){
            $validator = Validator::make($request->all(),[
                'challenge-name' => 'required',
                'title' => 'required',
                'gift' => 'required',
                'additional-gift' => 'required',
                'challenge-fee'     => 'required',
                'short-desc' => 'required',
                'challenge-deadline' => 'required',
                'challenge-description' => 'required',
                'challenge-requirement' => 'required',
               
                //'logo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
                //'header-image'=>'required|image|mimes:jpeg,png,jpg|max:2048'
            ]);
            if ($validator->fails()) {
                return redirect('new-challenge')
                        ->withErrors($validator)
                        ->withInput();
            }
            $logo = '';
            $header_image ='';
            // if(request()->file('logo')){
            //     $file = request()->file('logo');
            //     $timestamp = str_replace([' ', ':'], '-', date("y-m-d-h-i-s"));
            //     $extension = $request->file('logo')->getClientOriginalExtension();
            //     $logo = 'logo-'.str_random(5).'.'.$extension;
            //     //$filename = $file->getClientOriginalName();
            //     $path = public_path().'/uploads/challenge';
            //     $upload_status = $file->move($path, $logo);
            // }
            if(request()->file('header-image')){
                $file = request()->file('header-image');
                $timestamp = str_replace([' ', ':'], '-', date("y-m-d-h-i-s"));
                $extension = $request->file('header-image')->getClientOriginalExtension();

                $header_image = 'header-image-'.str_random(5).'.'.$extension;
                //$filename = $file->getClientOriginalName();
                $path = public_path().'/uploads/challenge';
                $upload_status = $file->move($path, $header_image);
            }
            $id = DB::table('challenges')->insertGetId([
                'id'=>'',
                'coach_id' => $this->_user->id,
                'added_by' => $this->_user->id,
                'challenges_name' => $request->input('challenge-name'),
                'title' => $request->input('title'),
                'challenges_fee'=>$request->input('challenge-fee'),
                'deadline'=>$request->input('challenge-deadline'),
                'short_desc'=>$request->input('short-desc'),
                
                'description'=>$request->input('challenge-description'),
                'requirement'=>$request->input('challenge-requirement'),
                'gift'=>$request->input('gift'),
                'additional_gift'=>$request->input('additional-gift'),
                'header_image'=>$header_image,
                'logo'=>$logo,
                'created_at'=>date('Y-m-d h:i:s'),
                'updated_at'=>date('Y-m-d h:i:s')
            ]);
            if($id){
                return redirect()->back()->with('message', 'Challenge created!');
            }else{
                return redirect('new-challenge')->withInput($request->all());
            }
        }
         
    	return view('coachchallenge.add-challenges');
    }
    public function editChallenge(Request $request, Redirector $redirect){
    	$challenge_id = (int)request()->route('challenge_id');
        if($request->method() == 'POST'){
            $validator = Validator::make($request->all(),[
                'challenge-name' => 'required',
                'title' => 'required',
                'gift' => 'required',
                'additional-gift' => 'required',
                'challenge-fee'     => 'required',
                'short-desc' => 'required',
                'challenge-deadline' => 'required',
                'challenge-description' => 'required',
                'challenge-requirement' => 'required',
                
                //'logo' => 'image|mimes:jpeg,png,jpg|max:2048',
                //'header-image'=>'image|mimes:jpeg,png,jpg|max:2048'
            ]);
            if ($validator->fails()) {
                return redirect('edit-challenge/'.$challenge_id)
                        ->withErrors($validator)
                        ->withInput();
            }
            $logo = '';
            $header_image ='';
            // if(request()->file('logo')){
            //     $file = request()->file('logo');
            //     $timestamp = str_replace([' ', ':'], '-', date("y-m-d-h-i-s"));
            //     $extension = $request->file('logo')->getClientOriginalExtension();
            //     $logo = 'logo-'.str_random(5).'.'.$extension;
            //     //$filename = $file->getClientOriginalName();
            //     $path = public_path().'/uploads/challenge';
            //     $upload_status = $file->move($path, $logo);
            // }
            if(request()->file('header-image')){
                $file = request()->file('header-image');
                $timestamp = str_replace([' ', ':'], '-', date("y-m-d-h-i-s"));
                $extension = $request->file('header-image')->getClientOriginalExtension();

                $header_image = 'header-image-'.str_random(5).'.'.$extension;
                //$filename = $file->getClientOriginalName();
                $path = public_path().'/uploads/challenge';
                $upload_status = $file->move($path, $header_image);
            }
            $id = DB::table('challenges')->where('id','=',$challenge_id)->where('coach_id','=',$this->_user->id)->update([
                // 'id'=>'',
                'added_by' => $this->_user->id,
                'challenges_name' => $request->input('challenge-name'),
                'title' => $request->input('title'),
                'challenges_fee'=>$request->input('challenge-fee'),
                'deadline'=>$request->input('challenge-deadline'),
                'short_desc'=>$request->input('short-desc'),
                
                'description'=>$request->input('challenge-description'),
                'requirement'=>$request->input('challenge-requirement'),
                'gift'=>$request->input('gift'),
                'additional_gift'=>$request->input('additional-gift'),
                'created_at'=>date('Y-m-d h:i:s'),
                'updated_at'=>date('Y-m-d h:i:s')
            ]);
            
            if($header_image !=""){
                $id = DB::table('challenges')->where('id','=',$challenge_id)->where('coach_id','=',$this->_user->id)->update([
                    'header_image'=>$header_image
                ]);
            }
            if($logo !=""){
                $id = DB::table('challenges')->where('id','=',$challenge_id)->where('coach_id','=',$this->_user->id)->update([
                    'logo'=>$logo
                ]);
            }
            if($id){
                return redirect()->back()->with('message', 'Challenge Updated!');
            }else{
                return redirect('edit-challenge/'.$challenge_id)->withInput($request->all());
            }
        }
        $result = Challenges::find($challenge_id); 
    	return view('coachchallenge.edit-challenges',['challenge'=>$result]);
    }

    public function challengeParticipant(Request $request, Redirector $redirect){
        $participants = ChallengesParticipant::with(['user','challenges','review'])->where('coach_id','=',$this->_user->id)->where('payment_status','=', 1)->where( 'stripe_id', '!=', 'NULL')->get();

       // echo "<pre>";
       // print_r($participants);
       // echo "</pre>";
        return view('coachchallenge.participants',['participants'=>$participants]);
    }

    public function challengeReview(Request $request, Redirector $redirect){
        $participant_id = (int)request()->route('participant_id');
        $participant = ChallengesParticipant::with(['user','challenges'])->where('payment_status','=', 1)->where( 'stripe_id', '!=', 'NULL')->where('id','=',$participant_id)->get();
        if($request->method() == 'POST'){
            
            $id = DB::table('challenge_reviews')->insertGetId([
                'id'=>'',
                'review_by_user_id' => $this->_user->id,
                'challenge_participant_id' => $request->input('participant_id'),
                'artisty' => $request->input('artisty-rating'),
                'artisty_comment'=>$request->input('artisty-comment'),
                'formation'=>$request->input('formation-rating'),
                'formation_comment'=>$request->input('formation-comment'),
                'interpretation'=>$request->input('interpretation-rating'),
                'interpretation_comment'=>$request->input('interpretation-comment'),
                'creativity'=>$request->input('creativity-rating'),
                'creativity_comment'=>$request->input('creativity-comment'),
                'style'=>$request->input('style-rating'),
                'style_comment'=>$request->input('style-comment'),
                'energy'=>$request->input('energy-rating'),
                'energy_comment'=>$request->input('energy-comment'),
                'precision'=>$request->input('precision-rating'),
                'precision_comment'=>$request->input('precision-comment'),
                'timing'=>$request->input('timing-rating'),
                'timing_comment'=>$request->input('timing-comment'),
                'footwork'=>$request->input('footwork-rating'),
                'footwork_comment'=>$request->input('footwork-comment'),
                'balance'=>$request->input('balance-rating'),
                'balance_comment'=>$request->input('balance-comment'),
                'focus'=>$request->input('focus-rating'),
                'focus_comment'=>$request->input('focus-comment'),
                'feedback'=>$request->input('feedback-rating'),
                'feedback_summary'=>$request->input('feedback-comment'),
                'additional_tips'=>$request->input('additional_tips'),
                'created_at'=>date('Y-m-d h:i:s'),
                'updated_at'=>date('Y-m-d h:i:s')
            ]);
            if($id){
                return redirect()->back()->with('message', 'Review Added!');
            }else{
                return redirect()->back()->with('error', 'Error While updating');
            }
        }
        return view('coachchallenge.add-review',['participant_detail'=>$participant[0],'participant_id'=>$participant_id]);
    }

    public function challengeReviewUpdate(Request $request, Redirector $redirect){
        $participant_id = (int)request()->route('participant_id');
        $participant = ChallengesParticipant::with(['user','challenges'])->where('payment_status','=', 1)->where( 'stripe_id', '!=', 'NULL')->where('id','=',$participant_id)->get();
        if($request->method() == 'POST'){
            $rules = [
               'performer-name' => 'required',
               'pq-rating' => 'required|numeric|min:1',
               'ta-rating' => 'required|numeric|min:1',
               'es-rating' => 'required|numeric|min:1',
               'storytelling-rating' => 'required|numeric|min:1',
               'la-rating' => 'required|numeric|min:1',
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
            $fieldName = [
               'pq-rating' => 'Performance Quality Rating',
               'ta-rating' => 'Technical Ability Rating',
               'es-rating' => 'Energy and Style Rating',
               'storytelling-rating' => 'Story Telling Rating',
               'la-rating' => 'Look and Appearance Rating',
               'performance-quality' => 'Performance Quality Comment',
               'technical-ability' =>  'Technical Ability Comment',
               'energy-and-style' => 'Energy and Style Comment',
               'storytelling' =>  'Story Telling Comment',
               'look-and-appearance' =>'Look and Appearance Comment',
            ];
            $validator = \Validator::make($request->all(), $rules, $valMsg);
            $validator->setAttributeNames($fieldName);                        
            if ($validator->fails()) {
                return redirect('challenge-review-edit/'. $participant_id)
                        ->withErrors($validator)
                        ->withInput();
            }
            $review_id = $request->input('review_id');
            $id = DB::table('challenge_review_new')->where('id','=',$review_id)->where('challenge_participant_id','=',$participant_id)->update([
                'review_by_user_id' => $this->_user->id,
                'challenge_participant_id' => $request->input('participant_id'),
                'challenge_id' => $participant[0]->challenge_id,
                'level_placement' =>$request->input('level-placement'),
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
                'feedback'=>$request->input('feedback'),
                'updated_at'=>date('Y-m-d h:i:s')
            ]);
            if($id){
                return redirect()->back()->with('message', 'Review Updated!');
            }else{
                return redirect()->back()->with('error', 'Error While updating');
            }
        }
        $review = ChallengeReviewNew::where('challenge_participant_id', $participant_id)->first();
        $participant = ChallengesParticipant::with(['user','challenges'])->where('coach_id','=',$this->_user->id)->where('payment_status','=', 1)->where( 'stripe_id', '!=', 'NULL')->where('id','=', $participant_id)->get();
        //$question = ReviewQuestion::where('id','=',$participant_id)->limit(3)->get();
        
        return view('coachchallenge.update-review',['participant_detail'=>$participant[0],'participant_id'=>$participant_id,'review'=>$review]);
    }
    public function challengeReviewNew(Request $request, Redirector $redirect){
        $participant_id = (int)request()->route('participant_id');
        // echo "<pre>";
        // print_r($participant);
        // echo "</pre>";
        // die();
        if($request->method() == 'POST'){
            $rules = [
               'performer-name' => 'required',
               'pq-rating' => 'required|numeric|min:1',
               'ta-rating' => 'required|numeric|min:1',
               'es-rating' => 'required|numeric|min:1',
               'storytelling-rating' => 'required|numeric|min:1',
               'la-rating' => 'required|numeric|min:1',
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
            $fieldName = [
               'pq-rating' => 'Performance Quality Rating',
               'ta-rating' => 'Technical Ability Rating',
               'es-rating' => 'Energy and Style Rating',
               'storytelling-rating' => 'Story Telling Rating',
               'la-rating' => 'Look and Appearance Rating',
               'performance-quality' => 'Performance Quality Comment',
               'technical-ability' =>  'Technical Ability Comment',
               'energy-and-style' => 'Energy and Style Comment',
               'storytelling' =>  'Story Telling Comment',
               'look-and-appearance' =>'Look and Appearance Comment',
            ];
            $validator = \Validator::make($request->all(), $rules, $valMsg);
            $validator->setAttributeNames($fieldName);                        
         // $validator = Validator::make($request->all(),[
         //        'performance-quality' => 'required',
         //        'performer-name' => 'required',
         //        'pq-rating' => 'required|min:1'
         //    ]);
            if ($validator->fails()) {
               if($request->wantsJson()){
                  return response()->json(['status' => 'val_error', 'errors' => $validator->errors()]);
               }
               return redirect('challenge-review-new/'.$participant_id)
                        ->withErrors($validator)
                        ->withInput();
            }
            $participant = ChallengesParticipant::with(['user','challenges','challenge_temporary_reviews'])->where('payment_status','=', 1)->where( 'stripe_id', '!=', 'NULL')->where('id','=',$participant_id)->first();
            // if($participant->status == challengesParticipant::STATUS_REVIEWED){
            //    echo 'dfd';exit;
            // }else{
            //    // echo 'aaa';exit;
            // }
            if(!$participant->challenge_temporary_reviews){
                if($request->wantsJson()){
                   return response()->json(['status' => 'error', "message" => "Please reload and try again. Temp review not found. "]);
                }
                 return redirect('challenge-review/' . $participant_id)->withErrors(['Please reload and try again. Temp review not found.']);
             }
            $temp_video = @$participant->challenge_temporary_reviews->review_url;
            $temp_path = public_path(config('video.temp_review_path')) . $temp_video;
            if(!file_exists($temp_path) || @$participant->challenge_temporary_reviews->review_url == NULL){
                ChallengeTempReview::where('id',$participant->challenge_temporary_reviews->id)->delete();
                if($request->wantsJson()){
                    return response()->json(['status' => 'error', "message" => "Please reload and try again. Temp file not found"]);
                }
                return redirect('challenge-review/' . $participant_id)->withErrors(['Please reload and try again. Temp file not found.']);
            }
            $review = ChallengeReviewNew::where('challenge_participant_id', $participant_id )->first();
            if( $review ){
               
                ChallengeReviewNew::where('challenge_participant_id', $participant_id)->delete();
               
            }
            $id = DB::table('challenge_review_new')->insertGetId([
                'id'=>'',
                'review_by_user_id' => $this->_user->id,
                'challenge_participant_id' => $request->input('participant_id'),
                'challenge_id' => $participant->challenge_id,
                'url'=>$participant->challenge_temporary_reviews->url,
                'review_url'=>$participant->challenge_temporary_reviews->review_url,
                'name' => $request->input('performer-name'),
                'level_placement' =>$request->input('level-placement'),
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
                'feedback'=>$request->input('feedback'),
                'created_at'=>date('Y-m-d h:i:s'),
                'updated_at'=>date('Y-m-d h:i:s')
            ]);
            if($id){
                ChallengeTempReview::moveTemporaryReviewData($participant->challenge_temporary_reviews->id);
               //  $affected = DB::table('challenge_participant')
               //    ->where('id', $participant_id)
               //    ->where('challenge_id',$participant->challenge_id)
               //    ->update(['status' => 1]);
                  self::checkIfApproved($participant);
                  if($request->wantsJson()){
                     return response()->json(['status' => 'success', "message" => "Review Added."]);
                  }
                  return redirect()->back()->with('message', 'Review Added');
            }else{
               if($request->wantsJson()){
                  return response()->json(['status' => 'error', "message" => "Error While updating"]);
               }
                return redirect()->back()->with('error', 'Error While updating');
            }
            
       }
        $participant = ChallengesParticipant::with(['user','challenges','challenge_temporary_reviews'])->where('payment_status','=', 1)->where( 'stripe_id', '!=', 'NULL')->where('id','=',$participant_id)->get();
       return view('coachchallenge.add-review',['participant_detail'=>$participant[0],'participant_id'=>$participant_id]);
    }

    public function storeChallengeAudioFile(Request $request)
    {

        if ($this->_user->role == User::COACH_ROLE) {
          //  $data['url'] = "/audio/" . $request->filename;
            $data['url'] = $request->filename;
            $data["user_id"] = $this->_user->id;
            $data["participant_id"] = $request->video_id;
            $data["play_time"] = $request->play_time;
            $data["created_at"] = mysql_date();
            $data["updated_at"] = mysql_date();
            $id = ChallengeTempReview::saveTempReview($data);

            $job = (new ConcatChallengeReviewVideoAudio(['participant_id' => $request->get('video_id')]));
            dispatch($job);

            return response()->json(["status" => "success", "message" => "Temp Review and file successfully added.", "review_id" => $id]);
        }


    }
    public function checkIfApproved($participant)
    {
        if($participant->status == challengesParticipant::STATUS_REVIEWED){
           return true;
        } 

        if( !Transaction::where('video_id', $participant->id)->where('participation_type',ChallengesParticipant::PARTICIPATION_TYPE)->exists() ) { // !Transaction
            $transfer = PaymentsController::transfer( $participant->id, ChallengesParticipant::PARTICIPATION_TYPE );
            if($transfer===false){
                return response()->json(['error'=>true, 'msg'=>'Transfer error. Try later.']);
            }
        }
        $user_id = $participant->user_id;
        $notification["user_id"] = $user_id;
        $notification["sender_id"] = $this->_user->id;
        $notification["video_id"] = $participant->id;
        $notification["participation_type"] = ChallengesParticipant::PARTICIPATION_TYPE;
        $notification["status"] = 1;
        $notification["message"] = '<a href="/profile/' . $this->_user->id . '">' . $this->_user->first_name
            . ' ' . $this->_user->last_name . '</a> added a new review.';
        $notification["created_at"] = mysql_date();
        $notification["updated_at"] = mysql_date();
        Notification::saveNotification($notification);

        ChallengesParticipant::changeStatus($participant->id, ChallengesParticipant::STATUS_REVIEWED);
        /* mail to performer and coach */

        $performer = User::select('first_name', 'email')->where('id', $user_id)->first();
        $performer_mail = new Mailer();
        $performer_mail->subject = 'Coach completed a challenge participant review ';
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
}
