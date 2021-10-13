<?php

namespace App\Api\Controllers;

use App\Api\Transformers\ChallengeTransformer;
use App\Api\Transformers\CParticipantTransformer;
use App\Api\Transformers\ChallengeDetailTransformer;
use App\Api\Transformers\AfterCParticipateTransformer;

use Dingo\Api\Http\Request;
use App\Models\User;
use App\Models\Notification;
//use Youtube;
use Storage;
use App\Models\Challenges;
use App\Models\ChallengesParticipant;
use Illuminate\Support\Facades\DB;
use App\Api\Requests\ChallengeUpload;
use ApplePayHelper;
/**
 * Auditions resource representation.
 *
 * @Resource("Auditions", uri="/auditions")
 */
class ChallengeController extends BaseController
{
	public function index(){
		$challenges = Challenges::all();
		
		foreach ($challenges as &$challenge){
			if (!empty($challenge)) {
				$challenge->participated = $this->checkUserParticipation($challenge->id);
				$challenge->product_id = ApplePayHelper::priceID($challenge->challenges_fee);
				$challenge->product_price = ApplePayHelper::newPrice($challenge->challenges_fee);
			}
		}
		
		return $this->response()->collection($challenges,new ChallengeTransformer());
	}

	public function checkUserParticipation($challenge_id){
		$challengelist = ChallengesParticipant::where('user_id','=',$this->auth->user()->id)->where('payment_status','=',1)->where('stripe_id','!=',NULL)->where('challenge_id','=',$challenge_id)->get();
		return (count($challengelist) > 0) ? 1 : 0;
	}

	public function challengeDetail(){
		$challenge_id = (int)request()->route('challenge_id');
		$challenge = Challenges::find($challenge_id);
		
		$challengetemp = &$challenge;
		if (!empty($challengetemp)) {
				
				$challengetemp->participated = $this->checkUserParticipation($challenge_id);
				$challengetemp->product_id = ApplePayHelper::priceID($challengetemp->challenges_fee);
				$challengetemp->product_price = ApplePayHelper::newPrice($challengetemp->challenges_fee);
			}
		return $this->response()->item($challenge,new ChallengeDetailTransformer());
	}

	public function store(ChallengeUpload $request){
		$video_type = $request->input( "video_type" );
		if($video_type == 'file'){
			if( !$request->file( "video_file" ))
				return response()->json(['status'=>422,'message'=>'Validation Failed,Please upload video file']);

			$file = $request->file( "video_file" );
			
	        $fileName = str_random(3) . uniqid() . "." . $file->getClientOriginalExtension();
	        $file->move(public_path() . config('video.challenge_video_path'), $fileName);

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
		$challenge_id = $request->input( "challenge_id" );
		//For resume
		$resume_file = $request->file( "resume" );
		$challenge = Challenges::where('id','=',$challenge_id)->first();
		if(empty($challenge)){
			return response()->json(['status'=>422,'message'=>'Challenge id is not valid']);
		}
        $resume_file_name = str_random(3) . uniqid() . "." . $resume_file->getClientOriginalExtension();
        $resume_file->move(public_path() . config('video.challenge_video_path'), $resume_file_name);

        $participant_id = DB::table('challenge_participant')->insertGetId(
			    ['id' => '','challenge_id'=>$challenge_id, 'user_id' => $this->auth->user()->id,'coach_id'=>$challenge->coach_id,'payment_status'=>0,'video_link'=>$video_url ,'video_type'=>$video_type,'resume'=>$resume_file_name,'created_at'=>mysql_date(),'updated_at'=>mysql_date()]);
        if($participant_id){
        	$participant = ChallengesParticipant::with('challenges')->where('user_id','=',$this->auth->user()->id)->where('id','=',$participant_id)->first();
        	return $this->response()->item($participant,new AfterCParticipateTransformer());
        }else{
        	return response()->json(['status'=>400,'message'=>'Something went wrong while creating user participation!']);
        }
	}

	public function review(ChallengesParticipant $challengelist){
		
		$challenge_id = (int)request()->route('challenge_id');
		$challengelist = ChallengesParticipant::with('challenges','review')->where('user_id','=',$this->auth->user()->id)->where('payment_status','=',1)->where('stripe_id','!=',NULL)->where('challenge_id','=',$challenge_id)->first();
		// echo "<pre>";
		// print_r($challengelist);
		// echo "</pre>";
		return $this->response()->item($challengelist,new CParticipantTransformer());
	}

}