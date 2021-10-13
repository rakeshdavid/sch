<?php

namespace App\Http\Controllers\Agency;

use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\PaymentsController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\UserActivityType;
use App\Models\ActivityGenre;
use App\Models\ActivityType;
use App\Models\Auditions;
use App\Models\AuditionList;
use App\Models\AuditionReview;
use App\Models\AuditionReviewNew;
use App\Models\PerformanceLevel;
use App\Models\User;
use App\Models\AuditionTempReview;
use App\Models\Notification;
use App\Models\Transaction;
use App\Jobs\ConcatAuditionReviewVideoAudio;
//use Validator;
use Session;
use File;
use Storage;
use App\Http\Helpers\Mailer;
class AuditionAgency extends Controller
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

    public function index(Request $request, Redirector $redirect){
    	$auditions = $this->_user->agencyAudition()->orderBy('created_at', 'DESC')->getQuery()//
               ->paginate(10);
                
    	return view('agency.index',['auditions'=>$auditions]);
    }

    public function auditionParticipant(Request $request, Redirector $redirect){
    	
        $participants = AuditionList::with(['user','audition','auditionreviewnew'])->where('payment_status','=', 1)->where( 'stripe_id', '!=', 'NULL')->where('agency_id',$this->_user->id)->get();
        //print_r($participants);
        
    	return view('agency.participant' ,['participants'=>$participants]);
    }

    public function newAudition(Request $request, Redirector $redirect){
        if($request->method() == 'POST'){
            $validator = Validator::make($request->all(),[
                'audition-name' => 'required',
                'title' => 'required',
                'audition-genres' => 'required',
                'audition-level' => 'required',
                'audition-fee'     => 'required',
                'audition-location' => 'required',
                'audition-deadline' => 'required',
                'audition-description' => 'required',
                'audition-requirement' => 'required',
                
                'logo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
                'header-image'=>'required|image|mimes:jpeg,png,jpg|max:2048'
            ]);
            if ($validator->fails()) {
                return redirect('new-audition')
                        ->withErrors($validator)
                        ->withInput();
            }
            $logo = '';
            $header_image ='';
            if(request()->file('logo')){
                $file = request()->file('logo');
                $timestamp = str_replace([' ', ':'], '-', date("y-m-d-h-i-s"));
                $extension = $request->file('logo')->getClientOriginalExtension();
                $logo = 'logo-'.$timestamp.'.'.$extension;
                //$filename = $file->getClientOriginalName();
                $path = public_path().'/uploads/auditions';
                $upload_status = $file->move($path, $logo);
            }
            if(request()->file('header-image')){
                $file = request()->file('header-image');
                $timestamp = str_replace([' ', ':'], '-', date("y-m-d-h-i-s"));
                $extension = $request->file('header-image')->getClientOriginalExtension();

                $header_image = 'header-image-'.$timestamp.'.'.$extension;
                //$filename = $file->getClientOriginalName();
                $path = public_path().'/uploads/auditions';
                $upload_status = $file->move($path, $header_image);
            }
            $id = DB::table('auditions')->insertGetId([
                'id'=>'',
                'agency_id' => $this->_user->id,
                'audition_name' => $request->input('audition-name'),
                'title' => $request->input('title'),
                'audition_fee'=>$request->input('audition-fee'),
                'deadline'=>$request->input('audition-deadline'),
                'location'=>$request->input('audition-location'),
                'audition_detail'=>'',
                'description'=>$request->input('audition-description'),
                'requirement'=>$request->input('audition-requirement'),
                'talent'=>$request->input('audition-genres'),
                'level'=>$request->input('audition-level'),
                'header_image'=>$header_image,
                'logo'=>$logo,
                'created_at'=>date('Y-m-d h:i:s'),
                'updated_at'=>date('Y-m-d h:i:s')
            ]);
            if($id){
                return redirect()->back()->with('message', 'Audition Added');
            }else{
                return redirect('new-audition')->withInput($request->all());
            }
        }
        $genres = ActivityGenre::all()->sortBy("name");
        $levels = PerformanceLevel::all(); 
    	return view('agency.add-audition',['genres'=>$genres,'levels'=>$levels]);
    }

    public function auditionReview(Request $request, Redirector $redirect){
        $participant_id = (int)request()->route('participant_id');
        $participant = AuditionList::with(['user','audition'])->where('payment_status','=', 1)->where( 'stripe_id', '!=', 'NULL')->where('id','=',$participant_id)->get();
        if($request->method() == 'POST'){
            
            $id = DB::table('audition_reviews')->insertGetId([
                'id'=>'',
                'review_by_user_id' => $this->_user->id,
                'audition_participant_id' => $request->input('participant_id'),
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
                return redirect()->back()->with('message', 'Review Added');
            }else{
                return redirect()->back()->with('error', 'Error While updating');
            }
        }
        return view('agency.add-review',['participant_detail'=>$participant[0],'participant_id'=>$participant_id]);
    }

    public function updateReview(Request $request, Redirector $redirect){
        $participant_id = (int)request()->route('participant_id');

        if($request->method() == 'POST'){
            $review_id = $request->input('review_id');
            $id = DB::table('audition_reviews')->where('id','=',$review_id)->where('audition_participant_id','=',$participant_id)->update([
                'review_by_user_id' => $this->_user->id,
                //'audition_participant_id' => $request->input('participant_id'),
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
                return redirect()->back()->with('message', 'Review Updated');
            }else{
                return redirect()->back()->with('error', 'Error While updating');
            }
        }
        $review = AuditionReviewNew::where('audition_participant_id', $participant_id)->first();
        $participant = AuditionList::with(['user','audition'])->where('payment_status','=', 1)->where( 'stripe_id', '!=', 'NULL')->where('id','=',$participant_id)->get();

        return view('agency.update-review',['participant_detail'=>$participant[0],'participant_id'=>$participant_id,'review'=>$review]);
    }


    public function editAudition(Request $request, Redirector $redirect){
        $audition_id = (int)request()->route('audition_id');
        if($request->method() == 'POST'){
            $validator = Validator::make($request->all(),[
                'audition-name' => 'required',
                'title' => 'required',
                'audition-genres' => 'required',
                'audition-level' => 'required',
                'audition-fee'     => 'required',
                'audition-location' => 'required',
                'audition-deadline' => 'required',
                'audition-description' => 'required',
                'audition-requirement' => 'required',
                
                'logo' => 'image|mimes:jpeg,png,jpg|max:2048',
                'header-image'=>'image|mimes:jpeg,png,jpg|max:2048'
            ]);
            if ($validator->fails()) {
                return redirect('edit-audition/'.$audition_id)
                        ->withErrors($validator)
                        ->withInput();
            }
            $logo = '';
            $header_image ='';
            $audition = Auditions::find($audition_id);
            if(request()->hasFile('logo')){
                $file = request()->file('logo');
                $timestamp = str_replace([' ', ':'], '-', date("y-m-d-h-i-s"));
                $extension = $request->file('logo')->getClientOriginalExtension();
                $logo = 'logo-'.$timestamp.'.'.$extension;
                //$filename = $file->getClientOriginalName();
                $path = public_path().'/uploads/auditions';
                $upload_status = $file->move($path, $logo);
                $audition->logo = $logo;
                $audition->save();
            }
            if(request()->hasFile('header-image')){
                $file = request()->file('header-image');
                $timestamp = str_replace([' ', ':'], '-', date("y-m-d-h-i-s"));
                $extension = $request->file('header-image')->getClientOriginalExtension();

                $header_image = 'header-image-'.$timestamp.'.'.$extension;
                //$filename = $file->getClientOriginalName();
                $path = public_path().'/uploads/auditions';
                $upload_status = $file->move($path, $header_image);
                $audition->header_image = $header_image;
                $audition->save();
            }
            $id = DB::table('auditions')->where('id','=',$audition_id)->where('agency_id','=',$this->_user->id)->update([
                
                'agency_id' => $this->_user->id,
                'audition_name' => $request->input('audition-name'),
                'title' => $request->input('title'),
                'audition_fee'=>$request->input('audition-fee'),
                'deadline'=>$request->input('audition-deadline'),
                'location'=>$request->input('audition-location'),
                'audition_detail'=>'',
                'description'=>$request->input('audition-description'),
                'requirement'=>$request->input('audition-requirement'),
                'talent'=>$request->input('audition-genres'),
                'level'=>$request->input('audition-level'),
                'updated_at'=>date('Y-m-d h:i:s')
            ]);
            if($id){
                return redirect()->back()->with('message', 'Audition updated');
            }else{
                return redirect('edit-audition/'.$audition_id)->withInput($request->all());
            }
        }
        $result = Auditions::find($audition_id);
        $genres = ActivityGenre::all()->sortBy("name");
        $levels = PerformanceLevel::all(); 
        return view('agency.edit-audition',['audition'=>$result,'genres'=>$genres,'levels'=>$levels]);
    }

    public function auditionReviewNew(Request $request, Redirector $redirect){
        $participant_id = (int)request()->route('participant_id');
        $participant = AuditionList::with(['user','audition','audition_temporary_reviews'])->where('payment_status','=', 1)->where( 'stripe_id', '!=', 'NULL')->where('id','=',$participant_id)->first();
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
            $fealdName = [
               'pq-rating' => 'Performance Quality Rating',
               'ta-rating' => 'Technical Ability Rating',
               'es-rating' => 'Energy and Style Rating',
               'storytelling-rating' => 'Story Telling Rating',
               'la-rating' => 'Look and Appearance Rating',
               'performance-quality' => 'Performance Quality Note',
               'technical-ability' =>  'Technical Ability Note',
               'energy-and-style' => 'Energy and Style Note',
               'storytelling' =>  'Story Telling Note',
               'look-and-appearance' =>'Look and Appearance Note',
            ];
            $validator = \Validator::make($request->all(), $rules, $valMsg);
            $validator->setAttributeNames($fealdName);            
            if ($validator->fails()) {
               if($request->wantsJson()){
                  return response()->json(['status' => 'val_error', 'errors' => $validator->errors()]);
               }
               return redirect('audition-review-new/'.$participant_id)
               ->withErrors($validator)
               ->withInput();
            }
            if(!$participant->audition_temporary_reviews){
                if($request->wantsJson()){
                   return response()->json(['status' => 'error', "message" => "Please reload and try again. Temp review not found. "]);
                }
                 return redirect('audition-review-new/' . $participant_id)->withErrors(['Please reload and try again. Temp review not found.']);
             }
            $temp_video = @$participant->audition_temporary_reviews->review_url;
            $temp_path = public_path(config('video.temp_review_path')) . $temp_video;
            if(!file_exists($temp_path) || @$participant->audition_temporary_reviews->review_url == NULL){
                AuditionTempReview::where('id',$participant->audition_temporary_reviews->id)->delete();
                if($request->wantsJson()){
                    return response()->json(['status' => 'error', "message" => "Please reload and try again. Temp file not found"]);
                }
                return redirect('audition-review-new/' . $participant_id)->withErrors(['Please reload and try again. Temp file not found.']);
            }

            $review = AuditionReviewNew::where('audition_participant_id', $participant_id )->first();
            if( $review ){
               
                AuditionReviewNew::where('audition_participant_id', $participant_id)->delete();
               
            }
            $id = DB::table('auditions_review_new')->insertGetId([
                'id'=>'',
                'review_by_user_id' => $this->_user->id,
                'audition_participant_id' => $request->input('participant_id'),
                'audition_id' => $request->input('audition_id'),
                'url'=>$participant->audition_temporary_reviews->url,
                'review_url'=>$participant->audition_temporary_reviews->review_url,
                'name' => $request->input('performer-name'),
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
                AuditionTempReview::moveTemporaryReviewData($participant->audition_temporary_reviews->id);
                self::checkIfApproved($participant);
                if($request->wantsJson()){
                  return response()->json(['status' => 'success', "message" => "Review Added.", "review_id" => $id]);
               }
                return redirect()->back()->with('message', 'Review Added');
            }else{
                return redirect()->back()->with('error', 'Error While updating');
            }        
        }
        return view('agency.add-review',['participant_detail'=>$participant,'participant_id'=>$participant_id]);
    }

    public function updateReviewNew(Request $request, Redirector $redirect){
        $participant_id = (int)request()->route('participant_id');

        if($request->method() == 'POST'){
            $rules = [
               'performer-name' => 'required',
               'pq-rating' => 'required|numeric|min:1',
               'ta-rating' => 'required|numeric|min:1',
               'es-rating' => 'required|numeric|min:1',
               'storytelling-rating' => 'required|numeric|min:1',
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
               'performance-quality' => 'Performance Quality Note',
               'technical-ability' =>  'Technical Ability Note',
               'energy-and-style' => 'Energy and Style Note',
               'storytelling' =>  'Story Telling Note',
               'look-and-appearance' =>'Look and Appearance Note',
            ];
            $validator = \Validator::make($request->all(), $rules, $valMsg);
            $validator->setAttributeNames($fealdName);            
            // $validator = Validator::make($request->all(),[
            //     'performance-quality' => 'required',
            //     'performer-name' => 'required',
            //     'pq-rating' => 'required|min:1'
            // ]);
            if ($validator->fails()) {
               if($request->wantsJson()){
                  return json_encode(['status' => 'val_error', 'errors' => $validator->errors()]);
               }
               return redirect('update-review/'.$participant_id)
                        ->withErrors($validator)
                        ->withInput();
            }
            $review_id = $request->input('review_id');
            $id = DB::table('auditions_review_new')->where('id','=',$review_id)->where('audition_participant_id','=',$participant_id)->update([
                'review_by_user_id' => $this->_user->id,
                'name' => $request->input('performer-name'),
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
               if($request->wantsJson()){
                  return json_encode(['status' => 'success', "message" => "Review Updated.", "review_id" => $id]);
               }
                return redirect()->back()->with('message', 'Review Updated');
            }else{
                return redirect()->back()->with('error', 'Error While updating');
            }
        }
        $review = AuditionReviewNew::where('audition_participant_id', $participant_id)->first();
        $participant = AuditionList::with(['user','audition'])->where('payment_status','=', 1)->where( 'stripe_id', '!=', 'NULL')->where('id','=',$participant_id)->get();

        return view('agency.update-review',['participant_detail'=>$participant[0],'participant_id'=>$participant_id,'review'=>$review]);
    }
    public function storeAuditionAudioFile(Request $request)
    {


        if ($this->_user->role == User::AGENCY_ROLE) {
          //  $data['url'] = "/audio/" . $request->filename;
            $data['url'] = $request->filename;
            $data["user_id"] = $this->_user->id;
            $data["participant_id"] = $request->video_id;
            $data["play_time"] = $request->play_time;
            $data["created_at"] = mysql_date();
            $data["updated_at"] = mysql_date();
            $id = AuditionTempReview::saveTempReview($data);

    $job = (new ConcatAuditionReviewVideoAudio(['participant_id' => $request->get('video_id')]));
            dispatch($job);

            return response()->json(["status" => "success", "message" => "Review successfully added.", "review_id" => $id]);
        }else{

        }


    }
    public function checkIfApproved($participant)
    {
        if($participant->status == AuditionList::STATUS_REVIEWED){
           return true;
        } 
        if( !Transaction::where('video_id', $participant->id)->where('participation_type',AuditionList::PARTICIPATION_TYPE)->exists() ) { // !Transaction
            $transfer = PaymentsController::transfer( $participant->id, AuditionList::PARTICIPATION_TYPE );
            if($transfer===false){
                return response()->json(['error'=>true, 'msg'=>'Transfer error. Try later.']);
            }
        }
        $user_id = $participant->user_id;
        $notification["user_id"] = $user_id;
        $notification["sender_id"] = $this->_user->id;
        $notification["video_id"] = $participant->id;
        $notification["participation_type"] = AuditionList::PARTICIPATION_TYPE;
        $notification["status"] = 1;
        $notification["message"] = '<a href="/profile/' . $this->_user->id . '">' . $this->_user->first_name
            . ' ' . $this->_user->last_name . '</a> added a new review.';
        $notification["created_at"] = mysql_date();
        $notification["updated_at"] = mysql_date();
        Notification::saveNotification($notification);

        AuditionList::changeStatus($participant->id, AuditionList::STATUS_REVIEWED);
        /* mail to performer and coach */

        $performer = User::select('first_name', 'email')->where('id', $user_id)->first();
        $performer_mail = new Mailer();
        $performer_mail->subject = 'Agency completed a audition participant review ';
        $performer_mail->to_email = $performer->email;
        $performer_mail->sendMail('auth.emails.coachCompletedReview',
            [
               'reviewer' => 'agency',
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
