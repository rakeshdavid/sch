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
use App\Api\Requests\ApplePayAuditionRequest;
use App\Models\User;
use App\Models\Notification;
//use Youtube;
use Storage;
use App\Models\Auditions;
use App\Models\AuditionList;
use App\Models\NewPaymentlist;
use App\Models\ActivityGenre;
use Illuminate\Support\Facades\DB;
use App\Api\Requests\AuditionUpload;
use Stripe\Stripe;
use App\Jobs\SendReminderEmail;
use Stripe\Charge;
use App\Http\Helpers\Mailer;
use Dingo\Api\Exception\StoreResourceFailedException;
use Stripe\EphemeralKey;
use Stripe\Customer;
use Thumbnail;
use FFMpeg\FFMpeg;
use YouTube\YouTubeDownloader;
use ApplePayHelper;
/**
 * Auditions resource representation.
 *
 * @Resource("Auditions", uri="/auditions")
 */
class AuditionController extends BaseController
{
	public function index(){
		$today = date('Y-m-d');
		$auditions = Auditions::where('deadline','>=' ,$today)->get();
        $audition_tax = DB::table('taxrate')->select('taxrate')->where('name','audition_tax')->first();
       
		foreach ($auditions as &$audition){
			if (!empty($audition)) {
				if($audition_tax){
					$audition->audition_fee_total = number_format(($audition->audition_fee + ($audition->audition_fee * (int)$audition_tax->taxrate / 100)),2 );
					$audition->audition_fee_tax = number_format(($audition->audition_fee * (int)$audition_tax->taxrate / 100),2 );
				}
				$audition->participated = $this->checkUserParticipation($audition->id);
				$audition->product_id = ApplePayHelper::priceID($audition->audition_fee);
				$audition->product_price = ApplePayHelper::newPrice($audition->audition_fee);
				$audition->talent_name = ActivityGenre::select('name')->where('id', $audition->talent)->first()->name;
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
		if($auditionlist==""){
			return $this->response->array([
                'message' => "No review has been added for your participation. Please wait we are reviewing!",
                'status_code' => 200,
            ])->setStatusCode(200);
		}
		
		if($auditionlist->auditionreviewnew ==""){
			return $this->response->array([
                'message' => "No review has been added for your participation. Please wait we are reviewing!",
                'status_code' => 200,
            ])->setStatusCode(200);
		}
		$temp = DB::table('user_performance_levels as upl')
            ->leftJoin('performance_levels as pl', 'upl.performance_level_id', '=', 'pl.id')
            ->where('upl.user_id','=',$this->auth->user()->id)
            ->select('pl.name')
            ->get();
        $levels = array();
        foreach ($temp as $key => $value) {
        	$levels[] = $value->name;
        }
        
        $temp2 = &$auditionlist;
        $temp2->performance_levels = $levels;
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
		
        $audition_tax = DB::table('taxrate')->select('taxrate')->where('name','audition_tax')->first();
		$auditiontemp = &$audition;
		if (!empty($auditiontemp)) {
				if($audition_tax){
					$auditiontemp->audition_fee_total = number_format(($audition->audition_fee + ($audition->audition_fee * (int)$audition_tax->taxrate / 100)),2 );
					$audition->audition_fee_tax = number_format(($audition->audition_fee * (int)$audition_tax->taxrate / 100),2 );
				}
				$talent = DB::table('activity_genres')->where('id','=',$audition->talent)->pluck('name');
				$lavel = DB::table('performance_levels')->where('id','=',$audition->level)->pluck('name');
				$auditiontemp->participated = $this->checkUserParticipation($audition_id);
				$auditiontemp->product_id = ApplePayHelper::priceID($auditiontemp->audition_fee);
				$auditiontemp->product_price =ApplePayHelper::newPrice($auditiontemp->audition_fee);
				$auditiontemp->talent = $talent[0];
				$auditiontemp->level = $lavel[0];
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
			$thumbnail_image_name="";
	        $extension = $request->file('video_file')->getClientOriginalExtension();
            $validextensions = array("mp4","ogx","oga","ogv","ogg","webm","m4v");
            if(in_array(strtolower($extension), $validextensions)){
                $file = $request->file('video_file');
                $timestamp =  str_random(5) . uniqid();
               
                //$filename = $file->getClientOriginalName();
                $path = public_path().'/uploads/auditions';
                $fileName = str_random(3) . uniqid() . "." . $file->getClientOriginalExtension();
	        	$upload_status = $file->move(public_path() . config('video.audition_video_path'), $fileName);
	        	$filename = $fileName;
	        	$video_url = $fileName;
                // Generate Thumbnail image for video
                $extension_type   = $file->getClientMimeType();
                // get file extension
                $extension        = $file->getClientOriginalExtension();

                
                        
                $fb_user_id = $this->auth->user()->id;
                if($upload_status)
                {
                  // file type is video
                  // set storage path to store the file (image generated for a given video)
                  $thumbnail_path   = public_path().'/user_videos/thumbnails';

                  $video_path       = $path.'/'.$filename;

                  // set thumbnail image name
                  $thumbnail_image  =   $fb_user_id.".".$timestamp.".jpg";
                  
                  // get video length and process it
                  // assign the value to time_to_image (which will get screenshot of video at that specified seconds)
                  $time_to_image    = 5;

                  $thumbnail_image_name ='';

                  $thumbnail_status = Thumbnail::getThumbnail($video_path,$thumbnail_path,$thumbnail_image,$time_to_image);
                  if($thumbnail_status)
                  {
                    $thumbnail_image_name =$thumbnail_image;
                  }
                  else
                  {
                    $thumbnail_image_name ='';
                  }
                }else{
                	return response()->json(['status'=>422,'message'=>'Error while uploading!']);
                }
            }else{
            	return response()->json(['status'=>422,'message'=>'Video type is not allowed.']);
            }

		}else{
			$video_url = $request->input( "youtube_link" );
			$url = $request->input( "youtube_link" );
            // $parts = parse_url($url);
            
            $rx = '~
              ^(?:https?://)?                           # Optional protocol
              (?:www[.])?                              # Optional sub-domain
              (?:youtube[.]com/watch[?]v=|youtu[.]be/) # Mandatory domain name (w/ query string in .com)
              ([^&]{11})                               # Video id of 11 characters as capture group 1
                ~x';
            $has_match = preg_match($rx, $url, $matches);
                  
            if(!empty($matches)){
                $yt = new YouTubeDownloader();
                $links = $yt->getDownloadLinks($url);
                $video_id = $matches[1];
                $filename = $video_id.'_'.uniqid().".mp4";
                
                $path = public_path().'/uploads/auditions/';
                
                file_put_contents($path.$filename, fopen($links[0]['url'], 'r'));
                $fb_user_id = $this->auth->user()->id;
                $thumbnail_path   = public_path().'/user_videos/thumbnails';
                $video_path       = $path.'/'.$filename;
                $thumbnail_image  =   $fb_user_id.".".uniqid().".jpg";
                $time_to_image    = 5;
                $thumbnail_image_name ='';
                $thumbnail_status = Thumbnail::getThumbnail($video_path,$thumbnail_path,$thumbnail_image,$time_to_image);
                if($thumbnail_status)
                {
                    $thumbnail_image_name =$thumbnail_image;
                }
                else
                {
                    $thumbnail_image_name ='';
                }
            
            }else{
                return response()->json(['status'=>422,'message'=>'Video Url is missing or not correct!']);
            }
            $video_url = $filename;
		}
		if(empty($video_url)){
			return response()->json(['status'=>422,'message'=>'Video Url is missing.']);
		}
		$video_type = 'file';
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
			    ['id' => '','audition_id'=>$audition_id, 'user_id' => $this->auth->user()->id,'agency_id'=>$audition->agency_id,'payment_status'=>0,'video_link'=>$video_url ,'video_type'=>$video_type,'resume'=>$resume_file_name,'thumbnail_url'=>$thumbnail_image_name,'created_at'=>mysql_date(),'updated_at'=>mysql_date()]);
        if($participant_id){
        	$participant = AuditionList::with('audition')->where('user_id','=',$this->auth->user()->id)->where('id','=',$participant_id)->first();
        	$temp_participant = &$participant;
        	
	        $temp_participant->aud_product_id = ApplePayHelper::priceID($participant->audition->audition_fee);
	        $temp_participant->aud_price = ApplePayHelper::newPrice($participant->audition->audition_fee);
        	return $this->response()->item($participant,new AfterParticipateTransformer());
        }else{
        	return response()->json(['status'=>400,'message'=>'Something went wrong while creating user participation!']);
        }
	}

	public function payAudition(PayAuditionRequest $request){
	        Stripe::setApiKey(config('services.stripe.secret')); //auth
	        $participantdetail = AuditionList::select('id','audition_id','user_id','payment_status','agency_id','stripe_id')->where('id','=',(int)$request->participant_id)->first(); 
	        $audition = Auditions::where('id','=',$participantdetail->audition_id)->first();
	        if($audition == ''){
	        	return response()->json(["message" => "Agency id in not found", 'status_code' => 422]);
	        }
	        $coach = User::select('first_name', 'email')->where('id', $participantdetail->agency_id)->first();
            if($coach == ''){
        		return response()->json(["message" => "Agency id not found", 'status_code' => 422]);
        	}
        	$audition_tax = DB::table('taxrate')->select('taxrate')->where('name','audition_tax')->first();
        	$audition_fee = $audition->audition_fee;
        	if($audition_tax){
				$audition_fee = number_format(($audition_fee + ($audition_fee * (int)$audition_tax->taxrate / 100)),2 );
			}
	        $charge_array = [
	            'currency' => 'USD',
	            'amount' => $audition_fee*100,
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
	            return response()->json(["message" => "Error pay exception", 'status_code' => 422,'response'=>$e]);
	        }

	        if ($charge['status'] == 'succeeded') { // success
	            //create payment in database
	            $new_pay = new NewPaymentlist();
	            $new_pay->user_id = $participantdetail->user_id;
	            $new_pay->participant_id = $participantdetail->id;
	            $new_pay->type = 'audition';
	            $new_pay->amount = (float)$charge['amount'];
	            $new_pay->stripe_id = (string)$charge['id'];
	            $new_pay->save();

	            //change video status
	            $participantdetail->payment_status = 1;
	            $participantdetail->stripe_id = (string)$charge['id'];
	            $participantdetail->update();

	            $user = User::select('first_name', 'last_name', 'email')->where('id', $participantdetail->user_id)->first();
	            //not
	            // $nt_data = [];
	            // $nt_data["user_id"] = $video->coach_id;
	            // $nt_data["sender_id"] = $video->user_id;
	            // $nt_data["video_id"] = $video->id;
	            // $nt_data["status"] = 1;
	            // $nt_data["message"] = '<a href="/profile/' . $video->user_id . '">' . $user->first_name . ' ' . $user->last_name
	            //     . '</a> paid video!';
	            // $nt_data["created_at"] = mysql_date();
	            // $nt_data["updated_at"] = mysql_date();
	            // Notification::saveNotification($nt_data);
	            //not

	            
	            $mail_to_performer = new Mailer();
	            $mail_to_performer->subject = 'Thank you for your payment';
	            $mail_to_performer->to_email = ($request->stripeEmail == $user->email) ? $user->email : $request->stripeEmail;
	            $mail_to_performer->sendMail('auth.emails.paymentReceived',
	                [
	                    'first_name' => $user->first_name,
	                    'date' => date('F d, Y h:i a'),
	                    'amount' => (float)$charge['amount'] / 100,
	                    'coach_name' => $coach->first_name,
	                    'card_ending' => $charge['source']['exp_month'] . '/' . $charge['source']['exp_year'],
	                    'card_type' => $charge['source']['brand'],
	                    'card_last4' => $charge['source']['last4']
	                ]);

	            $mail_to_coach = new Mailer();
	            $mail_to_coach->subject = 'Payment from the user for Audition';
	            $mail_to_coach->to_email = $coach->email;
	            $mail_to_coach->sendMail('auth.emails.paymentFromUser',
	                [
	                    'user_name' => $user->first_name,
	                    'coach_name' => $coach->first_name
	                ]);

	            /* reminder functionality */
	            // $params = [
	            //     'video_id' => $video->id,
	            //     'performer_name' => $user->first_name,
	            //     'coach_name' => $coach->first_name,
	            //     'coach_email' => $coach->email,
	            //     'date' => mysql_date(),
	            //     'days_left' => 3
	            // ];

	            // $job_3_days = (new SendReminderEmail($params))->delay(4 * 24 * 60 * 60);

	            // $params['days_left'] = 2;
	            // $job_2_days = (new SendReminderEmail($params))->delay(5 * 24 * 60 * 60);

	            // $params['days_left'] = 1;
	            // $job_1_days = (new SendReminderEmail($params))->delay(6 * 24 * 60 * 60);

	            // dispatch($job_3_days);
	            // dispatch($job_2_days);
	            // dispatch($job_1_days);

	            return response()->json(["message" => "Success pay!", 'status_code' => 200]);
	        } else {
	            return response()->json(["message" => "Error pay", 'status_code' => 422]);
	        }
	    
		}
	
	//Audition Pay from Apple Pay

	public function applePayAudition(ApplePayAuditionRequest $request){
	        
	        $participantdetail = AuditionList::select('id','audition_id','user_id','payment_status','agency_id','stripe_id')->where('id','=',(int)$request->participant_id)->first(); 
	        $audition = Auditions::where('id','=',$participantdetail->audition_id)->first();
	        if($audition == ''){
	        	return response()->json(["message" => "Agency id in not found", 'status_code' => 422]);
	        }
	        $coach = User::select('first_name', 'email')->where('id', $participantdetail->agency_id)->first();
            if($coach == ''){
        		return response()->json(["message" => "Agency id not found", 'status_code' => 422]);
        	}
	        
	        
	        if ($request->status == 200) { // success
	            //create payment in database
	            $new_pay = new NewPaymentlist();
	            $new_pay->user_id = $participantdetail->user_id;
	            $new_pay->participant_id = $participantdetail->id;
	            $new_pay->type = 'audition';
	            $new_pay->amount = $request->amount;
	            $new_pay->stripe_id = $request->transaction_id;
	            $new_pay->save();

	            //change video status
	            $participantdetail->payment_status = 1;
	            $participantdetail->stripe_id = $request->transaction_id;
	            $participantdetail->update();

	            $user = User::select('first_name', 'last_name', 'email')->where('id', $participantdetail->user_id)->first();
	            //not
	            // $nt_data = [];
	            // $nt_data["user_id"] = $video->coach_id;
	            // $nt_data["sender_id"] = $video->user_id;
	            // $nt_data["video_id"] = $video->id;
	            // $nt_data["status"] = 1;
	            // $nt_data["message"] = '<a href="/profile/' . $video->user_id . '">' . $user->first_name . ' ' . $user->last_name
	            //     . '</a> paid video!';
	            // $nt_data["created_at"] = mysql_date();
	            // $nt_data["updated_at"] = mysql_date();
	            // Notification::saveNotification($nt_data);
	            //not

	            
	            $mail_to_performer = new Mailer();
	            $mail_to_performer->subject = 'Thank you for your payment';
	            $mail_to_performer->to_email = ($request->stripeEmail == $user->email) ? $user->email : $request->stripeEmail;
	            $mail_to_performer->sendMail('auth.emails.paymentReceived',
	                [
	                    'first_name' => $user->first_name,
	                    'date' => date('F d, Y h:i a'),
	                    'amount' => $request->amount,
	                    'coach_name' => $coach->first_name,
	                    'card_ending' => 'Using Apple Pay',
	                    'card_type' => '',
	                    'card_last4' => ''
	                ]);

	            $mail_to_coach = new Mailer();
	            $mail_to_coach->subject = 'Payment from the user for Audition';
	            $mail_to_coach->to_email = $coach->email;
	            $mail_to_coach->sendMail('auth.emails.paymentFromUser',
	                [
	                    'user_name' => $user->first_name,
	                    'coach_name' => $coach->first_name
	                ]);

	            /* reminder functionality */
	            // $params = [
	            //     'video_id' => $video->id,
	            //     'performer_name' => $user->first_name,
	            //     'coach_name' => $coach->first_name,
	            //     'coach_email' => $coach->email,
	            //     'date' => mysql_date(),
	            //     'days_left' => 3
	            // ];

	            // $job_3_days = (new SendReminderEmail($params))->delay(4 * 24 * 60 * 60);

	            // $params['days_left'] = 2;
	            // $job_2_days = (new SendReminderEmail($params))->delay(5 * 24 * 60 * 60);

	            // $params['days_left'] = 1;
	            // $job_1_days = (new SendReminderEmail($params))->delay(6 * 24 * 60 * 60);

	            // dispatch($job_3_days);
	            // dispatch($job_2_days);
	            // dispatch($job_1_days);

	            return response()->json(["message" => "Success pay!", 'status_code' => 200]);
	        } else {
	            return response()->json(["message" => "Error pay", 'status_code' => 422]);
	        }
	    
		}

}