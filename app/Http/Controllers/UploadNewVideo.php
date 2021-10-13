<?php

namespace App\Http\Controllers;
use Carbon;
use Thumbnail;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Validator;
use App\Models\Video;
use App\Models\ReviewQuestion;
use App\Models\User;
use FFMpeg\FFMpeg;
use App\Jobs\ReformatUserVideo;
use App\Models\Notification;
use Session;
class UploadNewVideo extends Controller
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
    	return view('video/uploadvideo');
    }

    public function uploadvideo(Request $request){
        
        // $validator = Validator::make(request()->all(), [
        //     'video' => 'required|mimes:mp4,ogx,oga,ogv,ogg,webm',
            
        // ]);

        // if ($validator->fails()) {
        //     $url = url('/').'/video-error/';
        //     return response()->json(['status'=>400,'message'=>'Validation Failed,Please uplaod video file','redirect'=>$url]);
            
        // }

        if(request()->hasFile('file')){
            // Get file extension
            $extension = $request->file('file')->getClientOriginalExtension();
            $validextensions = array("mp4","ogx","oga","ogv","ogg","webm","m4v","mov");
            if(in_array(strtolower($extension), $validextensions)){
                $file = request()->file('file');
                $timestamp = str_replace([' ', ':'], '-', date("y-m-d-h-i-s"));
                $filename = $timestamp.'.'.$extension;
                //$filename = $file->getClientOriginalName();
                $path = public_path().'/user_videos/videos';
                $upload_status = $file->move($path, $filename);
                // Generate Thumbnail image for video
                $extension_type   = $file->getClientMimeType();
                // get file extension
                $extension        = $file->getClientOriginalExtension();

                
                        
                $fb_user_id = $this->_user->id;
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
                }
                $video_id = DB::table('videos')->insertGetId(
                    ['id' => '', 'user_id' => $this->_user->id,'url'=>$filename,'thumbnail'=>$thumbnail_image_name,'created_at'=>mysql_date(),'updated_at'=>mysql_date(),'level'=>1,'status'=>1,'payed'=>'N','pay_status'=>0,'package_id'=>0,'started_review_status'=>0,'is_reformatted'=>0]
                );
                $job = (new ReformatUserVideo(['video_id' => $video_id]));
                dispatch($job);
                $url = url('/').'/upload-successfull/'.$video_id;
                return response()->json(['status'=>200,'message'=>'Uploaded Successfull','redirect'=>$url]);
            }else{
                $url = url('/').'/video-error/';
                return response()->json(['status'=>400,'message'=>'Validation Failed,Please uplaod video file','redirect'=>$url]);
            
            }
            
        }else{
        	return response()->json(['status'=>400,'message'=>'Please uplaod video file','redirect'=>$url]);
        }
    
}

    public function uploadVideoSuccessfull(){
    	$id = (int)request()->route('video_id');
    	$result = Video::getById($id);
        $videourl = $result->url;
        if(strpos($videourl, 'youtube') !== false){
            $videotype="youtube";
        }else{
            $videotype = "file";
        }
    	return view('video/upload-successful',['video_id'=>$id,'result'=>$result,'videotype'=>$videotype]);
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
    public function uploadVideoTitle(){
      $title = request()->get('video_title');
      $video_id = request()->get('video_id');
      if($title !="" && $video_id !=""){
        $where = ['user_id'=>$this->_user->id,'id'=>$video_id];
        if(Session::get('coachid') !=null && Session::get('coachid') !=""){
          DB::table('videos')->where($where)->update(['name' => $title,'coach_id'=>Session::get('coachid'),'video_price'=>Session::get('coachfee'),'package_id'=>Session::get('package_id')]);
          if(Session::get('package_id') == 2){
            $url = url('/').'/ask-question/'.$video_id;
          }else{
            $url = url('/').'/payment/'.$video_id;
          }
          Session::forget('coachid');
          Session::forget('coachfee');
          Session::forget('package_id');
          //$url = url('/').'/ask-question/'.$video_id;
          return response()->json(['status'=>200,'message'=>'Updated Successfully!','redirect'=>$url]);
        }else{
          DB::table('videos')->where($where)->update(['name' => $title]);
            $url = url('/').'/select-coache/'.$video_id;
            return response()->json(['status'=>200,'message'=>'Updated Successfully!','redirect'=>$url]);
        }
        
      }else{
        return response()->json(['status'=>400,'message'=>'Error While Updating!']);
      }
    }

    public function videoError(Request $request){
        $error ="";
        return view('video/video-error',['error'=>$error]);
    }

    public function uploadYoutubeVideo(Request $request){

        $url = $request->input('youtube-link');
        // $parts = parse_url($url);
        
        $rx = '~
          ^(?:https?://)?                           # Optional protocol
          (?:www[.])?                              # Optional sub-domain
          (?:youtube[.]com/watch[?]v=|youtu[.]be/) # Mandatory domain name (w/ query string in .com)
          ([^&]{11})                               # Video id of 11 characters as capture group 1
            ~x';
        $has_match = preg_match($rx, $url, $matches);
 
        if(!empty($matches)){
           $video_id = $matches[1];
         //   echo $video_id;exit;
         // $result = $this->getYouTubeDownloaderUrl($video_id);
         // if($result['status'] == 'error'){
         //    return view('video/video-error',['error'=>$result['message']]);
         // }
         // $youTubeDownloadUrl = $result['youTubeDownloadUrl'];
         // echo $youTubeDownloadUrl;exit;
         $yt = new YouTubeDownloaderController();
         $videoInfo = $yt->media_info($url);
         // $yt = new YouTubeDownloader();
         //  $links = $yt->getDownloadLinks($url);
          $links = $videoInfo['links'];
         //  echo '<pre>';print_r($videoInfo);exit;
          $filename = $video_id.'_'.uniqid().".mp4";
          if(count($links) == 0){
         //     echo '<pre>'.$url;print_r($yt);exit;
              $error = "Error while downloading video from Youtube!";
              return view('video/video-error',['error'=>$error]);
          }
                // $filename = $query['v'].'_'.uniqid().".mp4";
                //$filename = $file->getClientOriginalName();
                $path = public_path().'/user_videos/videos/';
                // $upload_status = $file->move($path, $filename);
              file_put_contents($path.$filename, fopen($links[0]['url'], 'r'));
            //   file_put_contents($path.$filename, fopen($youTubeDownloadUrl, 'r'));
            //   file_get_contents($youTubeDownloadUrl);
            // echo '***';exit;

                $fb_user_id = $this->_user->id;
                
                  // file type is video
                  // set storage path to store the file (image generated for a given video)
                  $thumbnail_path   = public_path().'/user_videos/thumbnails';

                  $video_path       = $path.'/'.$filename;

                  // set thumbnail image name
                  $thumbnail_image  =   $fb_user_id.".".date('Y-m-d-H-i-s').".jpg";
                  
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
                
            $video_id = DB::table('videos')->insertGetId(
                    ['id' => '', 'user_id' => $this->_user->id,'url'=>$filename,'thumbnail'=>$thumbnail_image_name,'created_at'=>mysql_date(),'updated_at'=>mysql_date(),'level'=>1,'status'=>1,'payed'=>'N','pay_status'=>0,'package_id'=>0,'started_review_status'=>0,'is_reformatted'=>0]
                );
            $job = (new ReformatUserVideo(['video_id' => $video_id]));
                dispatch($job);
            return redirect(url('/').'/upload-successfull/'.$video_id);
        }else{
            $error ="Please upload youtube embed url";
            return view('video/video-error',['error'=>$error]);
        }
        
    }

    public function askQuestionForReview(Request $request){

        if($request->ajax()){
           
            $questionNumber = DB::table( 'review_questions' )
            ->where( 'video_id', $request->get('video-id') )
            ->get();
            ReviewQuestion::create([
                'id'=>'',
                'video_id'=>$request->get('video-id'),
                'question'=>$request->get('question-title'),
                'question_number'=>count($questionNumber)+1
            ]);
            return response()->json(['status'=>200,'message'=>'Question Posted. Please wait for answer.']);
        }
    }
    public function aasda(Request $request){
        return view('testvideo');
    }
  public function coachQuestions(Request $request){
    $id = (int)request()->route('video_id');
    if($id){
      if(Video::find($id)->questions()->count()){
         return redirect(url('payment/'.$id));         
      };
      return view('askQuestion',['video_id'=>$id]); 
    }else{
      return redirect(url('/').'/video/');
    }
    
  }
  public function askcoachQuestions(Request $request){
    if($request->ajax()){
        $video_id = $request->get('video-id');
        $questionNumber = DB::table( 'review_questions' )
        ->where( 'video_id', $request->get('video-id') )
        ->get();
        if($request->get('question-1') !=""){
          ReviewQuestion::create([
              'id'=>'',
              'video_id'=>$request->get('video-id'),
              'question'=>$request->get('question-1'),
              'question_number'=>count($questionNumber)+1
          ]);
        }
        if($request->get('question-2') !=""){
          ReviewQuestion::create([
              'id'=>'',
              'video_id'=>$request->get('video-id'),
              'question'=>$request->get('question-2'),
              'question_number'=>count($questionNumber)+1
          ]);
        }
        if($request->get('question-3') !=""){
          ReviewQuestion::create([
              'id'=>'',
              'video_id'=>$request->get('video-id'),
              'question'=>$request->get('question-3'),
              'question_number'=>count($questionNumber)+1
          ]);
        }
        $url = url('/').'/payment/'.$video_id;
        return response()->json(['status'=>200,'message'=>'Question Posted. Please wait for answer!','redirect'=>$url]);
        
    }
  }
    public function testThumbnail(Request $request)
  {

    // get file from input data
    $file             = $request->file('video_file');

    // get file type
    $extension_type   = $file->getClientMimeType();
    
    // set storage path to store the file (actual video)
    $destination_path = storage_path().'/uploads';

    // get file extension
    $extension        = $file->getClientOriginalExtension();


    $timestamp        = str_replace([' ', ':'], '-', date("y-m-d-h-i-s"));
    $file_name        = $timestamp.'.'.$extension;
    
    $upload_status    = $file->move($destination_path, $file_name);         
    $fb_user_id = 152;
    if($upload_status)
    {
      // file type is video
      // set storage path to store the file (image generated for a given video)
      $thumbnail_path   = storage_path().'/images';

      $video_path       = $destination_path.'/'.$file_name;

      // set thumbnail image name
      $thumbnail_image  =   $fb_user_id.".".$timestamp.".jpg";
      
      // set the thumbnail image "palyback" video button
      //$water_mark       = storage_path().'/watermark/p.png';

      // get video length and process it
      // assign the value to time_to_image (which will get screenshot of video at that specified seconds)
      $time_to_image    = 5;


      $thumbnail_status = Thumbnail::getThumbnail($video_path,$thumbnail_path,$thumbnail_image,$time_to_image);
      if($thumbnail_status)
      {
        echo "Thumbnail generated";
      }
      else
      {
        echo "thumbnail generation has failed";
      }
    }
  }
}
