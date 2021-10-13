<?php

namespace App\Http\Controllers\Challenges;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;
use App\Models\UserActivityType;
use App\Models\ActivityGenre;
use App\Models\ActivityType;
use App\Models\Challenges;
use Validator;
use Session;
use Stripe;
use App\Models\ChallengesParticipant;
use App\Http\Helpers\Mailer;
use App\Jobs\SendReminderEmail;
use App\Models\User;
use Thumbnail;
use FFMpeg\FFMpeg;
use App\Jobs\ReformatUserVideo;
use YouTube\YouTubeDownloader;
use App\Http\Controllers\YouTubeDownloaderController;
class ChallengesController extends Controller
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

    public function index(Request $request){

    	$filter = array('name'=>'');
        $challenge_id = (int)request()->route('challenge_id');
        if($challenge_id){
            $challenge_detail = Challenges::challengeById($challenge_id);
        }else{
            $challenge_detail = Challenges::firstChallenge();
        }
      $result = [];
      if(!request()->exists('single')){
         $query = Challenges::where('deadline','>=',date('Y-m-d'))
            ->orWhereHas('participants', function($q){
               $q->where('payment_status',1)->where('user_id','=',$this->_user->id);
            });
         $query->orderBy('deadline','asc');
         if(!empty($request->input('challenge-name'))){
            $query->where('challenges_name','like','%'.$request->input('challenge-name').'%');
            $filter['name']=$request->input('challenge-name');
         }
         $result = $query->get();
      }
        $participant = DB::table('challenge_participant')->where('user_id','=',$this->_user->id)->where('payment_status','=',1)->get();

        $participant_temp = ChallengesParticipant::with(['challenges','review'])->where('user_id','=',$this->_user->id)->where('payment_status','=',1)->get();
        
        $participated_challenge = array();
        
        if(count($participant) > 0){
            foreach ($participant as $row) {
                $participated_challenge[] = $row->challenge_id;
               
            }
        }
        $model = Challenges::find(1);
       
    	return view('challenges.index', ['challenges' => $result,'filter'=>$filter,'participated_challenge'=>$participated_challenge,'challenge_detail'=>$challenge_detail,'p_audtion_data'=>$participant_temp])->withModel($model);
    }

    public function participation(Request $request){
        $challenge_id = (int)request()->route('challenge_id');
        $temp = array("video"=>'',"resume"=>'',"video_type"=>'file');
        $thumbnail_image_name="";
        if($request->ajax()){
        
            $youtubelink = request()->input('youtube-link');
            $rx = '~
            ^(?:https?://)?                           # Optional protocol
            (?:www[.])?                              # Optional sub-domain
            (?:youtube[.]com/watch[?]v=|youtu[.]be/) # Mandatory domain name (w/ query string in .com)
            ([^&]{11})                               # Video id of 11 characters as capture group 1
                ~x';
            $has_match = preg_match($rx, $youtubelink, $matches);
            if($youtubelink !=""){
                $temp["resume"] = '';
            
                if(!empty($matches)){
                // echo $url;exit;
                //  $yt = new YouTubeDownloader();
                //  $links = $yt->getDownloadLinks($youtubelink);
                    $yt = new YouTubeDownloaderController();
                    $videoInfo = $yt->media_info($youtubelink);
                    $links = $videoInfo['links'];
                    $video_id = $matches[1];
                    $videoFileName = $video_id.'_'.uniqid().".mp4";
                    //$filename = $file->getClientOriginalName();
                    $path = public_path().'/uploads/challenge/';
                    // $upload_status = $file->move($path, $filename);
                    file_put_contents($path.$videoFileName, fopen($links[0]['url'], 'r'));
                    $temp["video"] = $videoFileName;
                    $temp['video_type'] = 'file';
                    $thumbnail_image_name = $this->generateThumbnail($videoFileName);
                    
                }else{
                    return response()->json(['message'=>"Video link is not corrent",'video_link'=>$youtubelink,'status'=>422]);
                } 
            }else{
                if(request()->hasFile('file')){

                    
                    $file = $request->file( "file" );
                    $thumbnail_image_name="";
                    $extension = $request->file('file')->getClientOriginalExtension();
                    $validextensions = array("mp4","ogx","oga","ogv","ogg","webm","m4v","mov");
                    if(in_array(strtolower($extension), $validextensions)){
                        $file = $request->file('file');
                        $timestamp =  str_random(5) . uniqid();
                    
                        //$filename = $file->getClientOriginalName();
                        $path = public_path().'/uploads/challenge';
                        $fileName = str_random(3) . uniqid() . "." . $file->getClientOriginalExtension();
                        $upload_status = $file->move(public_path() . config('video.challenge_video_path'), $fileName);
                        $filename = $fileName;
                        $video_url = $fileName;
                        $temp["video"] = $fileName;
                        $temp['video_type'] = 'file';
                    
                        $thumbnail_image_name = $this->generateThumbnail($fileName);

                    }else{
                        return response()->json(['status'=>422,'message'=>'Video type is not allowed.']);
                    }
                    
                }
            }
         
      
            $coach_id = Challenges::getChallengeCoachId($challenge_id);
            $participant_id = DB::table('challenge_participant')->insertGetId(
                ['id' => '','challenge_id'=>$challenge_id, 'user_id' => $this->_user->id,'coach_id'=>$coach_id,'payment_status'=>0,'video_link'=>$temp['video'],'video_type'=>$temp['video_type'],'resume'=>'','status'=>0,'thumbnail_url'=>$thumbnail_image_name,'created_at'=>mysql_date(),'updated_at'=>mysql_date()]
            );
            $job = (new ReformatUserVideo(['video_id' => $participant_id, 'participationType'=>ChallengesParticipant::PARTICIPATION_TYPE]));
            dispatch($job);
        
            $url = url('/').'/challenge/pay/'.$participant_id;
            
            $error = "done";
            return response()->json(['status'=>200,'message'=>$error,'redirect'=>$url]);
   
        }
        
        return view('challenges.challengeparticipation',['challenge_id'=>$challenge_id]);
    }

    public function payForChallenge(Request $request){
    	$participant_id = (int)request()->route('participant_id');
    	$cp = DB::table('challenge_participant')->select('id', 'challenge_id')
    	->where('id','=',$participant_id)
    	->get();
    	if(count($cp) > 0){
    		$challenge = DB::table('challenges')
	    	->where('id','=',$cp[0]->challenge_id)
	    	->get();
	    	
	    	$coach_detail = DB::table('users')
	    	->where('id','=',$challenge[0]->coach_id)
	    	->get();
    	}else{

            $url = url('/').'/challenge/';
            return back($url);
        }
    	
    	return view('challenges.payment',['participant_id'=>$participant_id,'challenge_detail'=>$challenge[0],'coach_detail'=>$coach_detail[0]]);
    }

    public function stripePost(Request $request)
    {
        Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
        if($request->input('participant-id') !="" && $request->input('challenge-id')){
            $challenge_id = $request->input('challenge-id');
            $participant_id = $request->input('participant-id');

            $challenge = DB::table('challenges')
            ->where('id','=',$challenge_id)
            ->get();
            $user_id = ChallengesParticipant::select('user_id','coach_id')->where('id',$participant_id)->first();
            if(count($challenge) > 0){
               try{
                  $charge = Stripe\Charge::create ([
                  "amount" => $challenge[0]->challenges_fee * 100,
                  "currency" => "usd",
                  "source" => $request->stripeToken,
                  "description" => "Payment from Showcase Platform for Challenge Participant." 
                  ]);
               }catch(\Exception $e){
                  return response()->json(['error'=>['message'=>$e->getMessage()]]);
               }
               
            if($charge->status  == 'succeeded'){
                $affected = DB::table('challenge_participant')
                  ->where('id', $participant_id)
                  ->where('challenge_id',$challenge_id)
                  ->update(['payment_status' => 1,'stripe_id'=>$charge->id]);
                    Session::flash('success', 'Payment successful!');
                    $user = User::select('first_name', 'last_name', 'email')->where('id', $user_id->user_id)->first();
                    $coach = User::select('first_name','email')->where('id', $user_id->coach_id)->first();
                    $mail_to_performer = new Mailer();
                    $mail_to_performer->subject = 'Thank you for your payment';
                    $mail_to_performer->to_email = $user->email;
                    $mail_to_performer->sendMail('auth.emails.paymentReceived',
                        [
                            'first_name'=>$user->first_name,
                            'date'=>date('F d, Y h:i a'),
                            'amount'=>(int)$charge['amount']/100,
                            'coach_name'=>$coach->first_name,
                            'card_ending'=>$charge['source']['exp_month'] . '/' . $charge['source']['exp_year'],
                            'card_type'=>$charge['source']['brand'],
                            'card_last4'=>$charge['source']['last4']
                        ]);

                    $mail_to_coach = new Mailer();
                    $mail_to_coach->subject = 'Payment from the user';
                    $mail_to_coach->to_email = $coach->email;
                    $mail_to_coach->sendMail('auth.emails.paymentFromUser',
                        [
                            'user_name'=>$user->first_name,
                            'coach_name'=>$coach->first_name
                        ]);
                    return back();
            }else{
                Session::flash('error', 'Payment Failed Please try again.');
          
                return back();
            }   
                
            }else{
                Session::flash('error', 'Wrong Challenge ID.');
          
                return back();  
            }
            
        }else{
            Session::flash('error', 'Error missing payment information.');
          
            return back();  
        }
        
        
    }
    public function generateThumbnail($videoFileName){
        $fb_user_id = $this->_user->id;
        $path = public_path().'/uploads/challenge';
        $thumbnail_path   = public_path().'/user_videos/thumbnails';
        $video_path       = $path.'/'.$videoFileName;

        // set thumbnail image name
        $thumbnail_image  =   $fb_user_id.".".str_random(3) . uniqid().".jpg";
          
        // get video length and process it
        // assign the value to time_to_image (which will get screenshot of video at that specified seconds)
        $time_to_image    = 5;

        $thumbnail_image_name ='';

        $thumbnail_status = Thumbnail::getThumbnail($video_path,$thumbnail_path,$thumbnail_image,$time_to_image);
        if($thumbnail_status)
        {
            $thumbnail_image_name =$thumbnail_image;
            //request()->session()->put('thumbnail_image', $thumbnail_image);
        }
       
          
        return $thumbnail_image_name;
       
    }
}
