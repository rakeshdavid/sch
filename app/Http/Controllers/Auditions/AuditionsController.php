<?php

namespace App\Http\Controllers\Auditions;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;
use App\Models\UserActivityType;
use App\Models\ActivityGenre;
use App\Models\ActivityType;
use App\Models\Auditions;
use App\Models\AuditionList;
use App\Models\AuditionReview;
use Thumbnail;
use FFMpeg\FFMpeg;
use App\Jobs\ReformatUserVideo;
use Validator;
use Session;
use Stripe;
use App\Http\Helpers\Mailer;
use App\Jobs\SendReminderEmail;
use App\Models\User;
use App\Http\Controllers\YouTubeDownloaderController;
class AuditionsController extends Controller
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

    public function index(){

        $audition_id = (int)request()->route('audition_id');
        if($audition_id){
            $audition_detail = Auditions::auditionById($audition_id);
        }else{
            $audition_detail = Auditions::firstAudition();
        }
      $result = [];
      if(!request()->exists('single')){
         $result = Auditions::where('deadline','>=',date('Y-m-d'))
            ->orWhereHas('participants', function($q){
               $q->where('user_id','=',$this->_user->id)->where('payment_status',1)->where( 'stripe_id', '!=', 'NULL');
            })
            ->orderBy('deadline', 'asc')->get();
            // dd($audition_detail->toArray());
      }
    	$activity_types = ActivityType::all();
    	$genres = DB::table('activity_genres')->distinct()->get();
    	
    	$filter = array('name'=>'','sortby'=>'','talent'=>'');
    	$participant = AuditionList::audition_list( $this->_user->id);
        
        $participated_audition = array();
        $audition_reviews = array();
        if(count($participant) > 0){
            foreach ($participant as $row) {
                $participated_audition[] = $row->audition_id;
                $audition_review = DB::table( 'auditions_review_new' )
                ->where('audition_participant_id','=',$row->id)
                ->get();
                if(count($audition_review) > 0){
                    $audition_reviews[$row->id] = $audition_review;
                }else{
                    $audition_reviews[$row->id] = array();
                }
                
            }
        }
        $model = Auditions::find(1);
        $temp_participants = AuditionList::with(['auditionreviewnew','audition'])->where('payment_status','=', 1)->where( 'stripe_id', '!=', 'NULL')->where('user_id','=',$this->_user->id)->get();
        
    	return view('auditions.index', ['auditions' => $result,'activity_genres'=>$genres,'filter'=>$filter,'participated_audition'=>$participated_audition,'audition_reviews'=>$temp_participants,'p_audtion_data'=>$participant,'audition_detail'=>$audition_detail,'reviews_data'=>$audition_reviews])->withModel($model);
    }

    public function filterAuditions(Request $request){
        $audition_id = (int)request()->route('audition_id');
    	$name = $request->input('audition-name');
    	$sortby = $request->input('sortby');
    	$talent = $request->input('talent');
    	$filter = array('name'=>'','sortby'=>'','talent'=>'');
        $audition_id = (int)request()->route('audition_id');
        if($audition_id){
            $audition_detail = Auditions::auditionById($audition_id);
        }else{
            $audition_detail = Auditions::firstAudition();
        }
        //print_r($audition_detail);
    	$query = DB::table( 'auditions' );
    	if($name !=""){
    		$query->where('audition_name','like','%'.$name.'%');
    		$filter['name']=$name;
    	}
    	if($talent !=""){
    		$query->where('talent','=',$talent);
    		$filter['talent'] = $talent;
    	}
        $query->where('deadline','>=',date('Y-m-d'));
    	$query->orderBy('deadline',$sortby);
    	$filter['sortby']=$sortby;
    	$result = $query->get();

    	$activity_types = ActivityType::all();
    	$genres = DB::table('activity_genres')->distinct()->get();
        //User Participated audtion data
    	$participant = AuditionList::audition_list( $this->_user->id );
        
        $participated_audition = array();
        $audition_reviews = array();
        if(count($participant) > 0){
            foreach ($participant as $row) {
                $participated_audition[] = $row->audition_id;
                $audition_review = DB::table( 'auditions_review_new' )
                ->where('audition_participant_id','=',$row->id)
                ->get();
                if(count($audition_review) > 0){
                    $audition_reviews[$row->id] = $audition_review;
                }else{
                    $audition_reviews[$row->id] = array();
                }
                
            }
        }
        $model = Auditions::find(1);
        $temp_participants = AuditionList::with(['auditionreviewnew','audition'])->where('payment_status','=', 1)->where( 'stripe_id', '!=', 'NULL')->where('user_id','=',$this->_user->id)->get();
    	//$model = AuditionReview::find(1);
    	return view('auditions.index', ['auditions' => $result,'activity_genres'=>$genres,'filter'=>$filter,'participated_audition'=>$participated_audition,'p_audtion_data'=>$participant,'audition_detail'=>$audition_detail,'reviews_data'=>$audition_reviews,'audition_reviews'=>$temp_participants])->withModel($model);
    }

    public function auditionParticipation(Request $request){
    	$audition_id = (int)request()->route('audition_id');
   //  	$request->session()->forget('resume'); 
			// $request->session()->forget('video'); 

        $audition_detail = Auditions::auditionById($audition_id);
    	if($request->ajax()){
            
    	$temp = array("video"=>'',"resume"=>'',"video_type"=>'file');
    	$youtubelink = request()->input('youtube-link');
        $rx = '~
          ^(?:https?://)?                           # Optional protocol
          (?:www[.])?                              # Optional sub-domain
          (?:youtube[.]com/watch[?]v=|youtu[.]be/) # Mandatory domain name (w/ query string in .com)
          ([^&]{11})                               # Video id of 11 characters as capture group 1
            ~x';
        $has_match = preg_match($rx, $youtubelink, $matches);
    	if($youtubelink !=""){
		    if(!empty($matches)){
                $yt = new YouTubeDownloaderController();
                $videoInfo = $yt->media_info($youtubelink);
               $links = $videoInfo['links'];
                $video_id = $matches[1];
                $videoFileName = $video_id.'_'.uniqid().".mp4";
                //$filename = $file->getClientOriginalName();
                $path = public_path().'/uploads/auditions/';
                // $upload_status = $file->move($path, $filename);
                file_put_contents($path.$videoFileName, fopen($links[0]['url'], 'r'));
                $request->session()->put('video', $videoFileName); 
                $request->session()->put('video_type', 'file');
                $this->generateThumbnail($videoFileName);
                return response()->json(['message'=>"Video link saved",'video_link'=>$youtubelink,'status'=>201]);
            }else{
                return response()->json(['message'=>"Video link is not corrent",'video_link'=>$youtubelink,'status'=>422]);
            }
    	}else{
    		if(request()->hasFile('file')){

				$file = request()->file('file');
	            $filename = $file->getClientOriginalName();
	           	$extension = $file->extension();
                if($extension != 'pdf' && $extension != 'doc' && $extension != 'docx'){
    	            $temp["video"] = $file->getClientOriginalName();
                    
                    $file = request()->file('file');
                    $thumbnail_image_name="";
                    $extension = request()->file('file')->getClientOriginalExtension();
                    $validextensions = array("mp4","ogx","oga","ogv","ogg","webm","m4v","mov");
                    if(in_array(strtolower($extension), $validextensions)){
                        $file = request()->file('file');
                        $timestamp =  str_random(5) . uniqid();
                       
                        //$filename = $file->getClientOriginalName();
                        $path = public_path().'/uploads/auditions';
                        $fileName = str_random(3) . uniqid() . "." . $file->getClientOriginalExtension();
                        $upload_status = $file->move(public_path() . config('video.audition_video_path'), $fileName);
                        
                       
                        $request->session()->put('video', $fileName); 
                        $request->session()->put('video_type', 'file');
                        $this->generateThumbnail($fileName);
                        return response()->json(['message'=>"Video file saved",'video_link'=>$fileName,'status'=>201]);
                    }else{
                        return response()->json(['status'=>422,'message'=>'Video type is not allowed.']);
                    }
                }
	            
        	}
    	}
    	if(request()->hasFile('file')){

            $file = request()->file('file');
            $filename = $file->getClientOriginalName();
            $extension = $file->extension();
            if($extension == 'pdf' || $extension == 'doc' || $extension == 'docx'){
                $temp["resume"] = $file->getClientOriginalName();
                $fileName = str_random(3) . uniqid() . "." . $file->extension();
                $request->session()->put('resume', $fileName);
                $path = public_path().'/uploads/auditions';
                $file->move($path, $fileName); 
            }
           
        } 
        if($request->session()->get('resume') !="" && $request->session()->get('video') !=""){
        	//echo $request->session()->get('resume');
        	//echo $request->session()->get('video');
        	$video_link = $request->session()->get('video');
        	$resume = $request->session()->get('resume');
            $video_type = $request->session()->get('video_type');
            $thumbnail_image_name = $request->session()->get('thumbnail_image');
        	$participant_id = DB::table('audition_participant')->insertGetId(
			    ['id' => '','audition_id'=>$audition_id, 'user_id' => $this->_user->id,'agency_id'=>$audition_detail->agency_id,'payment_status'=>0,'video_link'=>$video_link ,'video_type'=>$video_type,'resume'=>$resume,'thumbnail_url'=>$thumbnail_image_name,'created_at'=>mysql_date(),'updated_at'=>mysql_date()]
			);
			$request->session()->forget('resume'); 
			$request->session()->forget('video'); 
            $request->session()->forget('thumbnail_image');
            $job = (new ReformatUserVideo(['video_id' => $participant_id, 'participationType'=>AuditionList::PARTICIPATION_TYPE]));
            dispatch($job);
			$url = url('/').'/auditions/payment/'.$participant_id;
			//return redirect( url('/').'/auditions/payment/'.$participant_id );
			$error = "done";
			return response()->json(['status'=>200,'message'=>$error,'redirect'=>$url]);
        }else{
   //      	$request->session()->forget('resume'); 
			// $request->session()->forget('video');
        	$error = "Please Upload Video and resume together";
        	return response()->json(['message'=>$error]);
        }
    }
    	return view('auditions.participation',['auditionid'=>$audition_id]);
    }

    public function auditionPayment(Request $request){
    	$participant_id = (int)request()->route('participant_id');
    	$au = DB::table('audition_participant')->select('id', 'audition_id')
    	->where('id','=',$participant_id)
    	->get();
    	if(count($au) > 0){
    		$audition = DB::table('auditions')
	    	->where('id','=',$au[0]->audition_id)
	    	->get();
	    	
    	}
    	return view('auditions.payment',['participant_id'=>$participant_id,'audition'=>$audition[0]]);
    }

    public function stripePost(Request $request)
    {
        Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
        if($request->input('participant-id') !="" && $request->input('audition-id')){
            $audition_id = $request->input('audition-id');
            $participant_id = $request->input('participant-id');

            $audition = DB::table('auditions')
            ->where('id','=',$audition_id)
            ->get();
            $user_id = AuditionList::select('user_id','agency_id')->where('id',$participant_id)->first();
            if(count($audition) > 0){
               try{
                  $charge = Stripe\Charge::create ([
                "amount" => $audition[0]->audition_fee * 100,
                "currency" => "usd",
                "source" => $request->stripeToken,
                "description" => "Audition Paticipant Payment user id ".$this->_user->id
                ]);
               }catch(\Exception $e){
                  return response()->json(['error'=>['message'=>$e->getMessage()]]);
               }
               
            if($charge->status  == 'succeeded'){
                $affected = DB::table('audition_participant')
                  ->where('id', $participant_id)
                  ->where('audition_id',$audition_id)
                  ->update(['payment_status' => 1,'stripe_id'=>$charge->id]);
                    Session::flash('success', 'Payment successful!');
                    $user = User::select('first_name', 'last_name', 'email')->where('id', $user_id->user_id)->first();
                    $coach = User::select('first_name','email')->where('id', $user_id ->agency_id)->first();
                    $mail_to_performer = new Mailer();
                    $mail_to_performer->subject = 'Thank you for your payment';
                    $mail_to_performer->to_email = $user->email;
                    $mail_to_performer->sendMail('auth.emails.auditionPaymentReceived',
                        [
                            'first_name'=>$user->first_name,
                            'date'=>date('F d, Y h:i a'),
                            'amount'=>(int)$charge['amount']/100,
                            'agency_name'=>$coach->first_name,
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
        $path = public_path().'/uploads/auditions';
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
            request()->session()->put('thumbnail_image', $thumbnail_image);
        }
        else{
            request()->session()->put('thumbnail_image',"");
        }
          
        return $thumbnail_image_name;
       
    }
}
