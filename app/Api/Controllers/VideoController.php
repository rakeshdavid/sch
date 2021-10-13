<?php

namespace App\Api\Controllers;

use App\Api\Transformers\ReviewTransformer;
use App\Api\Transformers\ReviewedVideoTransformer;
use App\Models\Review;
use App\Models\Video;
use App\Models\PerformanceLevel;
use App\Models\UserPerformanceLevel;
use Dingo\Api\Http\Request;
use App\Api\Requests\StoreVideo;
use App\Api\Requests\VideoCoach;
use App\Api\Requests\AskQuestions;
use App\Api\Transformers\VideoTransformer;
use App\Api\Transformers\UpdateVideoCoach;
use App\Models\User;
use App\Models\ReviewQuestion;
use App\Models\Notification;
use App\Api\Requests\StoreVideoFile;
use Illuminate\Support\Facades\DB;
//use Youtube;
use Storage;
use App\Jobs\ReformatUserVideo;

use FFMpeg\FFMpeg;
use Thumbnail;
use App\Http\Controllers\YouTubeDownloaderController;
use Log;
use ApplePayHelper;
/**
 * Videos resource representation.
 *
 * @Resource("Videos", uri="/videos")
 */
class VideoController extends BaseController
{
    /**
     * User videos
     *
     * Get a JSON representation of user videos.
     * **status** can be: 1 - New video; 2 - Accepted proposal by coach; 3 - Reviewed; 4 - Refunded.
     * **pay_status** can be: 0 - Not paid; 1 - Paid.
     * **url** - youtube video identifier.
     * **video_price** - the price in US dollars that the user has to pay the coach for review.
     *
     * @Get("/")
     * @Transaction({
     *     @Request(headers={"Authorization": "Bearer <JWT>"}),
     *     @Response(200, body={"data": {{"id": 1, "name": "Example", "description": "Example description",
     *         "posted_date": "04/26/2018", "status": 1, "url": "W8n6yji9abc", "pay_status": 1, "video_price": 150},
     *         {"id": 2, "name": "Example 2", "description": "Example description 2",
     *         "posted_date": "04/27/2018", "status": 1, "url": "W8n6yji0abc",
     *         "video_thumbnail": "https://img.youtube.com/vi/uFO4Riu5DjU/0.jpg", "pay_status": 0, "video_price": 100}},
     *         "status_code": 200}),
     *     @Response(401, body={"message": "Failed to authenticate because of bad credentials or an invalid authorization header.",
     *         "status_code": 401}),
     *     @Response(500, body={"error":"Server side error message", "status_code": 500})
     * })
     * @Versions({"v1"})
     */
    public function index()
    {
        $user_videos = Video::where('user_id', $user = $this->auth->user()->id)
            ->orderBy('created_at', 'DESC')
            ->get();
        
        if(count($user_videos) > 0){
            foreach ($user_videos as &$user_video){
                $user_video->product_id = ApplePayHelper::priceID($user_video->video_price);
                $user_video->new_price =  ApplePayHelper::newPrice($user_video->video_price);
            }  
        }
        
        return $this->response()->collection($user_videos, new VideoTransformer());
    }

    /**
     * Review data
     *
     * **audio_url** link to the audio file with the trainer's comments to the video.
     * **play_time** recording pauses in video playback.
     * **url** - youtube video identifier.
     *
     * @Get("/sample-review")
     * @Transaction({
     *     @Request(headers={"Authorization": "Bearer <JWT>"}),
     *     @Response(200, body={"data": {"id":1, "level": "Advanced", "level_placement": "Advanced", "summary": "Summary long text",
     *         "additional_tips": "Additional tips text", "scores": {"timing": {"rating": 4, "comment": "comment"},
     *         "footwork": {"rating": 4, "comment": "comment"}, "alignment": {"rating": 4, "comment": "comment"},
     *         "balance": {"rating": 4, "comment": "comment"}, "focus": {"rating": 4, "comment": "comment"}, "precision":
     *         {"rating": 4, "comment": "comment"}, "energy": {"rating": 4, "comment": "comment"}, "style": {"rating": 4, "comment":
     *         "comment"}, "creativity": {"rating": 4, "comment": "comment"}, "interpretation": {"rating": 4, "comment": "comment"},
     *         "formation": {"rating": 4, "comment": "comment"}, "artistry": {"rating": 4, "comment": "comment"},
     *     "overall_rating": 3.9, "questions": {{"question_number": 1, "question": "Did you see a dog in video?", "answer": "no"}},
     *     "url": "W8n6yji0abc", "video_thumbnail": "https://img.youtube.com/vi/uFO4Riu5DjU/0.jpg"}}, "status_code": 200}),
     *     @Response(401, body={"message": "Failed to authenticate because of bad credentials or an invalid authorization header.",
     *         "status_code": 401}),
     *     @Response(422, body={"message": "422 Unprocessable Entity", "status_code": 422, "errors":{"review not found":
     *         {".env SAMPLE_REVIEW_VIDEO_ID not found"}}}),
     *     @Response(500, body={"error":"Server side error message", "status_code": 500})
     * })
     * @Versions({"v1"})
     *
     */
    public function sampleReview()
    {
        $review = Review::where('video_id', env('SAMPLE_REVIEW_VIDEO_ID', 0))->first();
        if(!$review){
            return $this->response->array([
                'message' => "422 Unprocessable Entity",
                'errors' => ['review not found' => ['.env SAMPLE_REVIEW_VIDEO_ID not found']],
                'status_code' => 422,
            ])->setStatusCode(422);
        }else{
            return $this->response->item($review, new ReviewTransformer());
        }
    }

    /**
     * Review data
     *
     * **audio_url** link to the audio file with the trainer's comments to the video.
     * **play_time** recording pauses in video playback.
     * **url** - youtube video identifier.
     *
     * @Get("/<video_id>/review")
     * @Transaction({
     *     @Request(headers={"Authorization": "Bearer <JWT>"}),
     *     @Response(200, body={"data": {"id":1, "level": "Advanced", "level_placement": "Advanced", "summary": "Summary long text",
     *         "additional_tips": "Additional tips text", "scores": {"timing": {"rating": 4, "comment": "comment"},
     *         "footwork": {"rating": 4, "comment": "comment"}, "alignment": {"rating": 4, "comment": "comment"},
     *         "balance": {"rating": 4, "comment": "comment"}, "focus": {"rating": 4, "comment": "comment"}, "precision":
     *         {"rating": 4, "comment": "comment"}, "energy": {"rating": 4, "comment": "comment"}, "style": {"rating": 4, "comment":
     *         "comment"}, "creativity": {"rating": 4, "comment": "comment"}, "interpretation": {"rating": 4, "comment": "comment"},
     *         "formation": {"rating": 4, "comment": "comment"}, "artistry": {"rating": 4, "comment": "comment"},
     *     "overall_rating": 3.9, "questions": {{"question_number": 1, "question": "Did you see a dog in video?", "answer": "no"}},
     *     "url": "W8n6yji0abc", "video_thumbnail": "https://img.youtube.com/vi/uFO4Riu5DjU/0.jpg"}}, "status_code": 200}),
     *     @Response(401, body={"message": "Failed to authenticate because of bad credentials or an invalid authorization header.",
     *         "status_code": 401}),
     *     @Response(500, body={"error":"Server side error message", "status_code": 500})
     * })
     * @Versions({"v1"})
     *
     * @param Video $video
     * @return \Dingo\Api\Http\Response
     */
    public function review(Video $video)
    {
        $review = $video->review;
        if($review){
            $video_id = $video->review->video_id;
            $temp = &$video->review;
            $coach_id = Video::select('coach_id')->where('id',  $video_id)->first()->coach_id;
            $coach_avatar = User::select('avatar')->where('id',  $coach_id)->first();
            if($coach_avatar){
                $temp->coach_avatar = url('/').'/'.$coach_avatar->avatar;
            }else{
                $temp->coach_avatar = '';
            }
            $temp->package_id = $video->package_id;
        }
        return $this->response->item($review, new ReviewTransformer());
        //return $this->response->item($video->review, new ReviewTransformer());
    }

    /**
     * Store video
     *
     * Store new video.
     * **genres** - array of genres id's.
     * **level** - performance level id.
     * **seeking_auditions** - should contains one of this values: yes, no, maybe, not_yet.
     * **site_target** - should contains one of this values: internet_search, advertisement, friend, other.
     * **other_site_spec** - available in case site_target == other.
     *
     * @Post("/store")
     * @Transaction({
     *     @Request(headers={"Authorization": "Bearer <JWT>"}, body={"coach": 1, "name": "Video title",
     *         "url": "https://www.youtube.com/watch?v=hYrN7EIeXmY", "description": "Parturient sodales duis curabitur semper.",
     *         "genres": {1, 2}, "question": {"First question", "Second question", "Third question"}, "level": 1, "activity_experience": 2,
     *      "seeking_auditions": "not_yet", "about": "about yourself", "site_target":"other", "other_site_spec":"reading in the magazine"}),
     *     @Response(200, body={"data": {"video":{"description": "video description","name": "video name","level": "1","activity_experience": "1",
     *     "seeking_auditions": "not_yet","updated_at": "2018-06-26 07:34:55","created_at": "2018-06-26 07:34:55","id": 146,
     *     "url": "hYrN7EIeXmY","status": 1,"user_id": 136,"coach_id": 85,"video_price": 10000},
     *      "message": "Video successfully stored!"}, "status_code": 200}),
     *     @Response(401, body={"message": "Failed to authenticate because of bad credentials or an invalid authorization header.",
     *         "status_code": 401}),
     *     @Response(422, body={"message": "422 Unprocessable Entity", "status_code": 422, "errors":{"url":
     *         {"The Url field is required"}}}),
     *     @Response(500, body={"error":"Server side error message", "status_code": 500})
     * })
     * @Versions({"v1"})
     *
     * @param Request $request
     */ 
    public function store(StoreVideo $request)
    {
        Log::warning('Store API Call');
        $video_tax = DB::table('taxrate')->select('taxrate')->where('name','video_tax')->first();
        if ($request->get('price') == 'price_detailed') {
            $v_price = User::select('price_detailed')->where('id', (int)$request->get('coach'))->first()->price_detailed;
            if($video_tax){
                $v_price = number_format(($v_price + ($v_price * (int)$video_tax->taxrate / 100)),2 );
            }
            $v_price = ($v_price) ? $v_price : env('DEFAULT_SUMMARY_DETAILED');
            $package_id = 2;
        } else {
            $v_price = User::select('price_summary')->where('id', (int)$request->get('coach'))->first()->price_summary;
            if($video_tax){
                $v_price = number_format(($v_price + ($v_price * (int)$video_tax->taxrate / 100)),2 );
            }
            $v_price = ($v_price) ? $v_price : env('DEFAULT_SUMMARY_PRICE');
            $package_id = 1;
        }
        $filename = $request->url;
        $user = $this->auth->user();
        $thumbnail_image_name = '';
        $fb_user_id = $this->auth->user()->id;
        if (strpos($request->url, 'youtube') == false && strpos($request->url, 'youtu.be') == false) {
            Log::warning('Before Thumbnil creation');
          $path = public_path().'/user_videos/videos';
          // set storage path to store the file (image generated for a given video)
          $thumbnail_path   = public_path().'/user_videos/thumbnails';
          $filename = basename($request->url);
          $video_path       = $path.'/'.$filename;
          //Log::warning('Something could be going wrong.'.$video_path );
          $timestamp = str_replace([' ', ':'], '-', date("y-m-d-h-i-s"));
          // set thumbnail image name
          $thumbnail_image  =   $fb_user_id.".".$timestamp.".jpg";
          
          $time_to_image    = 2;


          $thumbnail_status = Thumbnail::getThumbnail($video_path,$thumbnail_path,$thumbnail_image,$time_to_image);
          if($thumbnail_status)
          {
            $thumbnail_image_name =$thumbnail_image;
          }
          else
          {
            $thumbnail_image_name ='';
          }
          Log::warning('After Thumbnil creation');
        }else{
            $url = $request->url;
            // $parts = parse_url($url);
            
            $rx = '~
              ^(?:https?://)?                           # Optional protocol
              (?:www[.])?                              # Optional sub-domain
              (?:youtube[.]com/watch[?]v=|youtu[.]be/) # Mandatory domain name (w/ query string in .com)
              ([^&]{11})                               # Video id of 11 characters as capture group 1
                ~x';
            $has_match = preg_match($rx, $url, $matches);
                  
            if(!empty($matches)){
                $yt = new YouTubeDownloaderController();
                $videoInfo = $yt->media_info($url);
                $video_id = $matches[1];
                $filename = $video_id.'_'.uniqid().".mp4";
                $links = $videoInfo['links'];
                $path = public_path().'/user_videos/videos/';
                if(count($links) == 0){
                    $error = "Error while downloading video from Youtube!";
                    return view('video/video-error',['error'=>$error]);
                }
                file_put_contents($path.$filename, fopen($links[0]['url'], 'r'));
                $fb_user_id = $this->auth->user()->id;
                $thumbnail_path   = public_path().'/user_videos/thumbnails';
                $video_path       = $path.'/'.$filename;
                $thumbnail_image  =   $fb_user_id.".".date('Y-m-d-H-i-s').".jpg";
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
                return $this->response->array([
                'message' => "Youtube url is not correct!",
                'errors' => '',
                'status_code' => 422,
                ])->setStatusCode(422);
            }
        }
        $video = Video::create($request->except('genres'));
        //$video->url = self::getVideoCode($request->url);
        $video->level = User::UserPerformanceLevel($user->id);
        $video->url = $filename;
        $video->status = Video::STATUS_NEW;
        $user = User::find($user->id);
        $video->user()->associate($user);
        $coach = User::find($request->get('coach'));
        $video->coach()->associate($coach);
        $video->activity_genres()->attach($request->get('genres'));
        
        $video->video_price = (int)$v_price; //to->cent
        $video->thumbnail= $thumbnail_image_name;
        $video->package_id = $package_id;
        $video->update();

        User::whereId($user->id)->update([
            'site_target' => $request->get('site_target'),
            'other_site_spec' => $request->get('site_target') != 'other' ? '' : $request->get('other_site_spec'),
            'about' => $request->get('about')
        ]);

        $video_id = $video->id;
        self::storeNewVideoNotification($user, $coach, $video->id, 'added a new video! Pending payment!');

        $video = collect($video);
        $video['video_price'] = (int)$video['video_price'];
        //$video['video_thumbnail'] = 'https://img.youtube.com/vi/' . $video['url'] . '/0.jpg';
        //$video['video_thumbnail'] = url('/') . '/images/default_thumbnail.jpg';
        $job = (new ReformatUserVideo(['video_id' => $video_id]));
        dispatch($job);
        return $this->response()->array([
            'data' => ['video' => $video->forget(['user','coach']), 'video_id'=>$video_id,'message' => 'Video successfully stored!'],
            'status_code' => 200
        ]);
    }

    /**
     * Store video file
     *
     * Update user profile.
     *
     * @param StoreVideoFile $request
     *
     * @Post("/store/video-file")
     * @Transaction({
     *     @Request(body={"video_file": "File: avi, mpeg4, wmv, mp4", "title": "video title", "description": "video description"}
     *     , headers={"Authorization": "Bearer <JWT>"}),
     *     @Response(200, body={"data": {"url": "https://www.youtube.com/watch?v=4y33h81phKU"}, "status_code": 200}),
     *     @Response(401, body={"message": "Failed to authenticate because of bad credentials or an invalid authorization header.",
     *         "status_code": 401}),
     *     @Response(422, body={"message": "422 Unprocessable Entity", "status_code": 422, "errors":{
     *         "description":{"The description field is required."}}}),
     *     @Response(500, body={"error":"Server side error message", "status_code": 500})
     * })
     * @Versions({"v1"})
     */
    public function storeVideoFile(StoreVideoFile $request)
    {
        $file = $request->file( "video_file" );
        $fileName = str_random(3) . uniqid() . "." . $file->getClientOriginalExtension();
        $file->move(public_path() . config('video.user_video_path'), $fileName);
//        $params = [
//            'title'       => $request->get( 'title' ),
//            'description' => $request->get( 'description' ),
//        ];
//        $uploaded_file = public_path().'/uploads/' . $fileName;
//        $url = Youtube::upload($uploaded_file, $params);
//        \File::delete($uploaded_file);
        // $thumbnail_image_name = '';
        // $fb_user_id = $this->auth->user()->id;
        // if ($fileName) {
        //   $path = public_path().'/user_videos/videos';
        //   // set storage path to store the file (image generated for a given video)
        //   $thumbnail_path   = public_path().'/user_videos/thumbnails';

        //   $video_path       = $path.'/'.$fileName;
        //   $timestamp = str_replace([' ', ':'], '-', date("y-m-d-h-i-s"));
        //   // set thumbnail image name
        //   $thumbnail_image  =   $fb_user_id.".".$timestamp.".jpg";
          
        //   // get video length and process it
        //   // assign the value to time_to_image (which will get screenshot of video at that specified seconds)
        //   $time_to_image    = 5;


        //   $thumbnail_status = Thumbnail::getThumbnail($video_path,$thumbnail_path,$thumbnail_image,$time_to_image);
        //   if($thumbnail_status)
        //   {
        //     $thumbnail_image_name =$thumbnail_image;
        //   }
        //   else
        //   {
        //     $thumbnail_image_name ='';
        //   }
        // }
        Log::warning('Video file moved to folder - '.$fileName);
        return $this->response->array([
            'data' => ['url' => $fileName,'full_url'=>url('/').'/user_videos/videos/'.$fileName],
            'status_code' => 200
        ]);
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

    public function myreview(){
        $user_videos = Review::with(['video'])->where('user_id','=',$this->auth->user()->id)->orderBy('id', 'desc')->get();
       
        foreach ($user_videos as &$user_video) {
            $user_video->video->overall_rating  = round((
                    $user_video->artisty +
                    $user_video->formation +
                    $user_video->interpretation +
                    $user_video->creativity +
                    $user_video->style +
                    $user_video->energy +
                    $user_video->precision +
                    $user_video->timing +
                    $user_video->footwork +
                    $user_video->alingment +
                    $user_video->balance +
                    $user_video->focus
                ) / 12, 1);
            $user_video->video->level = $this->levelName($user_video->video->level);
        }
        return $this->response()->collection($user_videos, new ReviewedVideoTransformer());
    }

    public function levelName($id){
        $level = PerformanceLevel::where('id','=',$id)->first();
        return $level->name;
    }

    public function updateVideoCoach(VideoCoach $request){
        $video_tax = DB::table('taxrate')->select('taxrate')->where('name','video_tax')->first();

        if ($request->get('package_type') == 'price_detailed') {
            $v_price = User::select('price_detailed')->where('id', (int)$request->get('coach_id'))->first()->price_detailed;
            if($video_tax){
                $v_price = number_format(($v_price + ($v_price * (int)$video_tax->taxrate / 100)),2 );
            }
            $v_price = ($v_price) ? $v_price : env('DEFAULT_SUMMARY_DETAILED');
        } else {
            $v_price = User::select('price_summary')->where('id', (int)$request->get('coach_id'))->first()->price_summary;
            if($video_tax){
                $v_price = number_format(($v_price + ($v_price * (int)$video_tax->taxrate / 100)),2 );
            }
            $v_price = ($v_price) ? $v_price : env('DEFAULT_SUMMARY_PRICE');
        }

        $video = Video::find((int)$request->get('video_id'));
        if(empty($video)){
            return $this->response->array([
                'data' => ['message' =>"Video id not found"],
                'status_code' => 400
            ]);
        }
        $video->coach_id = (int)$request->get('coach_id');
        $video->video_price = $v_price;
        $video->update();
        $updatedvideo = Video::where('id','=',(int)$request->get('video_id'))->first();
        $videodata = Video::with(['coach'])->where('id','=',(int)$request->get('video_id'))->first();
        $performances = UserPerformanceLevel::where('user_id','=',$videodata->coach->id)->get();
        //print_r($performances);
        $temp2 = array();
        foreach ($performances as $performance) {
            $performance->performance_level_id;
            $temp = PerformanceLevel::where('id','=',$performance->performance_level_id)->first();
            $temp2[] = $temp->name;
        }
        
        $temp3 = &$videodata;
        $videodata->level = $temp2;
        // return $this->response->array([
        //     'data' => ['message' =>"Video coach updated successfully"],
        //     'status_code' => 200
        // ]);
        return $this->response()->item($videodata, new UpdateVideoCoach());
    }

    public function postReviewQuestion(AskQuestions $request){

        $video = Video::find((int)$request->get('video_id'));
        if(empty($video)){
            return $this->response->array([
                'data' => ['message' =>"Video id not found"],
                'status_code' => 400
            ]);
        }

        $allquestion = ReviewQuestion::where('video_id','=',(int)$request->get('video_id'))->get();
        if(count($allquestion) < 3){
            $question = ReviewQuestion::create();
            $question->video_id =  (int)$request->get('video_id');
            $question->question =  $request->get('question');
            $question->question_number = count($allquestion)+1;
            $question->update();
            return $this->response->array([
                'data' => ['message' =>"Question post successfully."],
                'status_code' => 200
            ]);
        }else{
            return $this->response->array([
                'data' => ['message' =>"You can only ask 3 questions for your review."],
                'status_code' => 422
            ]);
        }

    }

    public function askQuestion(Request $request){
        
        $video_id = $request->get('video_id');
        $video = Video::find((int)$request->get('video_id'));
        if(empty($video)){
            return $this->response->array([
                'data' => ['message' =>"Video id not found".$video_id],
                'status_code' => 400
            ]);
        }
        $questionNumber = DB::table( 'review_questions' )
        ->where( 'video_id', $request->get('video_id') )
        ->get();
        if($request->get('question_1') !=""){
          ReviewQuestion::create([
              'id'=>'',
              'video_id'=>$request->get('video_id'),
              'question'=>$request->get('question_1'),
              'question_number'=>count($questionNumber)+1
          ]);
        }
        if($request->get('question_2') !=""){
          ReviewQuestion::create([
              'id'=>'',
              'video_id'=>$request->get('video_id'),
              'question'=>$request->get('question_2'),
              'question_number'=>2
          ]);
        }
        if($request->get('question_3') !=""){
          ReviewQuestion::create([
              'id'=>'',
              'video_id'=>$request->get('video_id'),
              'question'=>$request->get('question_3'),
              'question_number'=>3
          ]);
        }
        $url = url('/').'/payment/'.$video_id;
        return response()->json(['status'=>200,'message'=>'Question Posted. Please wait for answer!','redirect'=>$url]);
    }
}
