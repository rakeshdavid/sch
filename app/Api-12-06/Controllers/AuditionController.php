<?php

namespace App\Api\Controllers;

use App\Api\Transformers\AuditionTransformer;
use App\Api\Transformers\ParticipantTransformer;
use App\Api\Transformers\AuditionDetailTransformer;
use App\Api\Transformers\AfterParticipateTransformer;
use App\Models\Video;
use Dingo\Api\Http\Request;
use App\Api\Requests\StoreVideo;
use App\Api\Requests\PayAuditionRequest;
use App\Models\User;
use App\Models\Notification;
//use Youtube;
use Storage;
use App\Models\Auditions;
use App\Models\AuditionList;
use Illuminate\Support\Facades\DB;
use App\Api\Requests\AuditionUpload;
use ApplePayHelper;
/**
 * Auditions resource representation.
 *
 * @Resource("Auditions", uri="/auditions")
 */
class AuditionController extends BaseController
{
	public function index(){
		$auditions = Auditions::all();
		
		foreach ($auditions as &$audition){
			if (!empty($audition)) {
				$audition->participated = $this->checkUserParticipation($audition->id);
				$audition->product_id = ApplePayHelper::priceID($audition->audition_fee);
				$audition->product_price = ApplePayHelper::newPrice($audition->audition_fee);
			}
		}
		
		return $this->response()->collection($auditions,new AuditionTransformer());
	}

	/*

	Get detail of participant using audition id
	url = '/auditions/{audition_id}/participants'
	*/
	public function review(AuditionList $auditionist){
		
		$audition_id = (int)request()->route('audition_id');
		$auditionlist = AuditionList::with('audition','auditionreviewnew')->where('user_id','=',$this->auth->user()->id)->where('payment_status','=',1)->where('stripe_id','!=',NULL)->where('audition_id','=',$audition_id)->first();
		
		return $this->response()->item($auditionlist,new ParticipantTransformer());
	}

	public function checkUserParticipation($audition_id){
		$auditionlist = AuditionList::where('user_id','=',$this->auth->user()->id)->where('payment_status','=',1)->where('stripe_id','!=',NULL)->where('audition_id','=',$audition_id)->get();
		return (count($auditionlist) > 0) ? 1 : 0;
	}

	/*

	Get detail of audition using audition id

	*/

	public function auditionDetail(){
		$audition_id = (int)request()->route('audition_id');
		$audition = Auditions::find($audition_id);
		
		$auditiontemp = &$audition;
		if (!empty($auditiontemp)) {
				$talent = DB::table('activity_genres')->where('id','=',$audition->talent)->pluck('name');
				$lavel = DB::table('performance_levels')->where('id','=',$audition->level)->pluck('name');
				$auditiontemp->participated = $this->checkUserParticipation($audition_id);
				$auditiontemp->talent = $talent[0];
				$auditiontemp->level = $lavel[0];
				$auditiontemp->product_id = ApplePayHelper::priceID($auditiontemp->audition_fee);
				$auditiontemp->product_price =ApplePayHelper::newPrice($auditiontemp->audition_fee);
			}

		// echo "<pre>";
		// print_r($audition);
		// echo "</pre>";
		return $this->response()->item($audition,new AuditionDetailTransformer());
	}

	public function store(AuditionUpload $request){
		$video_type = $request->input( "video_type" );
		if($video_type == 'file'){
			if( !$request->file( "video_file" ))
				return response()->json(['status'=>422,'message'=>'Validation Failed,Please upload video file']);

			$file = $request->file( "video_file" );
			
	        $fileName = str_random(3) . uniqid() . "." . $file->getClientOriginalExtension();
	        $file->move(public_path() . config('video.audition_video_path'), $fileName);

	        $video_url = $fileName;

		}else{
			$video_url = $request->input( "youtube_link" );
		}
		if(empty($video_url)){
			return response()->json(['status'=>422,'message'=>'Video Url is missing.']);
		}
		if($video_type !="file" && $video_type !="youtube"){
			return response()->json(['status'=>422,'message'=>'Video type must be "file or youtube"!']);
		}
		$audition_id = $request->input( "audition_id" );
		//For resume
		$resume_file = $request->file( "resume" );
		$audition = Auditions::where('id','=',$audition_id)->first();
		if(empty($audition)){
			return response()->json(['status'=>422,'message'=>'Audition id is not valid']);
		}
        $resume_file_name = str_random(3) . uniqid() . "." . $resume_file->getClientOriginalExtension();
        $resume_file->move(public_path() . config('video.audition_video_path'), $resume_file_name);

        $participant_id = DB::table('audition_participant')->insertGetId(
			    ['id' => '','audition_id'=>$audition_id, 'user_id' => $this->auth->user()->id,'agency_id'=>$audition->agency_id,'payment_status'=>0,'video_link'=>$video_url ,'video_type'=>$video_type,'resume'=>$resume_file_name,'created_at'=>mysql_date(),'updated_at'=>mysql_date()]);
        if($participant_id){
        	$participant = AuditionList::with('audition')->where('user_id','=',$this->auth->user()->id)->where('id','=',$participant_id)->first();
        	return $this->response()->item($participant,new AfterParticipateTransformer());
        }else{
        	return response()->json(['status'=>400,'message'=>'Something went wrong while creating user participation!']);
        }
	}

	public function payAudition(PayAuditionRequest $request){
	        Stripe::setApiKey(config('services.stripe.secret')); //auth
	        $participantdetail = AuditionList::select('id','audition_id','user_id','payment_status','agency_id')->where('id','=',(int)$request->participant_id)->first(); 
	        $audition = Auditions::where('id','=',$participantdetail->audition_id)->first();
	        //$video = Video::select('id', 'user_id', 'coach_id', 'video_price', 'pay_status')
	        //    ->where('id', (int)$request->video_id)->first();
	        $charge_array = [
	            'currency' => 'USD',
	            'amount' => $audition->audition_fee,
	            'description' => "Charge main balance, paid audition participant " . $participantdetail->id,
	        ];
	        if($request->source) $charge_array['source'] = $request->source;
	        if($request->need_customer_id){
	            if(auth()->user()->stripe_customer_id){
	                $stripe_customer_id = self::checkStripeCustomer();
	                $charge_array['customer'] = $stripe_customer_id;
	            }else{
	                $stripe_customer = Customer::create([
	                    'email' => auth()->user()->email,
	                    'description' => 'User for stripe ephemeral key',
	                ]);
	                $charge_array['customer'] = $stripe_customer->id;
	                User::whereId(auth()->user()->id)->update(['stripe_customer_id' => $stripe_customer->id]);
	            }
	        }else{
	            if($request->customer) $charge_array['customer'] = $request->customer;
	        }
	        //try charge
	        try {
	            $charge = Charge::create($charge_array);
	        } catch (\Exception $e) {
	            return response()->json(["message" => "Error pay", 'status_code' => 422]);
	        }

	        if ($charge['status'] == 'succeeded') { // success
	            //create payment in database
	            $new_pay = new PaymentsList();
	            $new_pay->user_id = $video->user_id;
	            $new_pay->coach_id = $video->coach_id;
	            $new_pay->video_id = $video->id;
	            $new_pay->amount = (int)$charge['amount'];
	            $new_pay->stripe_id = (string)$charge['id'];
	            $new_pay->save();

	            //change video status
	            $video->pay_status = 1;
	            $video->update();

	            $user = User::select('first_name', 'last_name', 'email')->where('id', $video->user_id)->first();
	            //not
	            $nt_data = [];
	            $nt_data["user_id"] = $video->coach_id;
	            $nt_data["sender_id"] = $video->user_id;
	            $nt_data["video_id"] = $video->id;
	            $nt_data["status"] = 1;
	            $nt_data["message"] = '<a href="/profile/' . $video->user_id . '">' . $user->first_name . ' ' . $user->last_name
	                . '</a> paid video!';
	            $nt_data["created_at"] = mysql_date();
	            $nt_data["updated_at"] = mysql_date();
	            Notification::saveNotification($nt_data);
	            //not

	            $coach = User::select('first_name', 'email')->where('id', $video->coach_id)->first();
	            $mail_to_performer = new Mailer();
	            $mail_to_performer->subject = 'Thank you for your payment';
	            $mail_to_performer->to_email = ($request->stripeEmail == $user->email) ? $user->email : $request->stripeEmail;
	            $mail_to_performer->sendMail('auth.emails.paymentReceived',
	                [
	                    'first_name' => $user->first_name,
	                    'date' => date('F d, Y h:i a'),
	                    'amount' => (int)$charge['amount'] / 100,
	                    'coach_name' => $coach->first_name,
	                    'card_ending' => $charge['source']['exp_month'] . '/' . $charge['source']['exp_year'],
	                    'card_type' => $charge['source']['brand'],
	                    'card_last4' => $charge['source']['last4']
	                ]);

	            $mail_to_coach = new Mailer();
	            $mail_to_coach->subject = 'Payment from the user';
	            $mail_to_coach->to_email = $coach->email;
	            $mail_to_coach->sendMail('auth.emails.paymentFromUser',
	                [
	                    'user_name' => $user->first_name,
	                    'coach_name' => $coach->first_name
	                ]);

	            /* reminder functionality */
	            $params = [
	                'video_id' => $video->id,
	                'performer_name' => $user->first_name,
	                'coach_name' => $coach->first_name,
	                'coach_email' => $coach->email,
	                'date' => mysql_date(),
	                'days_left' => 3
	            ];

	            $job_3_days = (new SendReminderEmail($params))->delay(4 * 24 * 60 * 60);

	            $params['days_left'] = 2;
	            $job_2_days = (new SendReminderEmail($params))->delay(5 * 24 * 60 * 60);

	            $params['days_left'] = 1;
	            $job_1_days = (new SendReminderEmail($params))->delay(6 * 24 * 60 * 60);

	            dispatch($job_3_days);
	            dispatch($job_2_days);
	            dispatch($job_1_days);

	            return response()->json(["message" => "Success pay!", 'status_code' => 200]);
	        } else {
	            return response()->json(["message" => "Error pay", 'status_code' => 422]);
	        }
	    
		}
	

}