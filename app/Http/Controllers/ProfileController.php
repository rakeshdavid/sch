<?php

namespace App\Http\Controllers;

use File;
use Image;
use Youtube;
use App\Models\ActivityGenre;
use App\Models\ActivityType;
use App\Models\CoachGallery;
use App\Models\PerformanceLevel;
use App\Models\CoahcesDocuments;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Support\Facades\App;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use App\Http\Helpers\Mailer;

class ProfileController extends Controller
{
    private $_user = null;
    public function __construct(Request $request, Redirector $redirect){

        $this->_user = $request->user();

        if(empty($this->_user)){
            $redirect->to('/')->send();
        }
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = User::find($this->_user->id);
        $userDocunentFolder = User::getDocumentsFolder();
        $coachDocuments = CoahcesDocuments::where('user_id', $this->_user->id)->get(['id', 'document_name'])->toArray();
        $user_activity_types = $user->activity_types()->select('user_activity_types.activity_type_id')->get()->toArray();
        $user_activity_types_id = [];
        foreach ($user_activity_types as $user_activity_type) {
            $user_activity_types_id[] = $user_activity_type['activity_type_id'];
        }
        $activity_genres = [];
        if(count($user_activity_types_id) > 0) {
            $activity_genres = ActivityGenre::whereIn('activity_type_id', $user_activity_types_id)->get();
        }

        $user = User::find($this->_user->id);
        //$postfix = $user->role == User::COACH_ROLE ? '_coach' : '';
        if($user->role == User::COACH_ROLE){
            $postfix = '_coach';
        }elseif ($user->role == User::AGENCY_ROLE) {
            $postfix = '_agency';
        }else{
            $postfix = '';
        }
        return view('profile/index'.$postfix, [
            "user" => $user,
            'activity_genres'=>$activity_genres,
            'coachDocuments' => $coachDocuments,
            'userDocunentFolder' => $userDocunentFolder
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /*generate vacation message*/
    private function generateVacationMsg ($target=true, $vacation_end=true){
        if($target){
            $time_left = $target->diffInDays($vacation_end);
            $prefix = 'days';
            if($time_left < 1){
                $time_left = $target->diffInHours($vacation_end);
                $prefix = 'hours';
            }
            //return 'The coach is not available. '.$time_left.' '.$prefix.' left.';
            return 'The coach is not available. Available in ' . $time_left . ' ' . $prefix;
        } else {
            return 'The coach is not available. Come back later.';
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        $userDocunentFolder = User::getDocumentsFolder();
        $postfix = $user->role == User::COACH_ROLE ? '_coach' : '';
        $coachDocuments = CoahcesDocuments::where('user_id', $id)->get(['id', 'document_name'])->toArray();

        //TODO::move vacation code in handler. do better message creations.
        //in coach edit profile page we did not have any validation rules for all field :( go if...
        //we have 4 variant. 1: 2 field empty, 2: vacation start empty, 3: vacation end empty, 4: 2 field !empty.
        /*vacation*/
        $vacation_start =  Carbon::parse($user->vacation_start);
        $vacation_end = Carbon::parse($user->vacation_end);
        $target =  Carbon::now(); //need correct server time
        $valid_years_range = 1; //in which range, we process information. set in years

        $msg = false;
        if( $target->diffInYears($vacation_start)>$valid_years_range and $target->diffInYears($vacation_end)>$valid_years_range ){ //$vacation_start and $vacation_end empty or not valid value
            //not valid or empty field logic
        } else if ($target->diffInYears($vacation_start)>1 and $target->diffInYears($vacation_end)<1 ) { //$vacation_start empty or not valid value
            $check = $target->diffInHours($vacation_end, false);
                if($check > 0){ //$vacation_end valid value. if end of vacation not come
                    $msg = $this->generateVacationMsg($target, $vacation_end);
                }
        } else if ( $target->diffInYears($vacation_start)<$valid_years_range and $target->diffInYears($vacation_end)>$valid_years_range ){ //$vacation_end empty or not valid value
            $check = $target->diffInHours($vacation_start, false);
                if($check < 0){ //$vacation_start valid value. it's vacation time. vacation end undefined.
                    $msg =  $this->generateVacationMsg(false);
                }
        } else if( $target->diffInYears($vacation_start)<$valid_years_range and $target->diffInYears($vacation_end)<$valid_years_range ){ // 2 field valid
            if( $target->diffInHours($vacation_start, false) < $target->diffInHours($vacation_end, false) ){ //if date start < date end logic valid data
                if($target->diffInHours($vacation_start, false) <= 0 and $target->diffInHours($vacation_end, false) >= 0 ){ // go msg
                    $msg = $this->generateVacationMsg($target, $vacation_end);
                }
            } else { //if date start > date end logic...
                //date start > date end logic
            }
        }
        /*vacation end*/

        return view('profile/index'.$postfix, [
            "user" => $user,
            'vacation_msg'=>$msg,
            'flag'=>true,
            'coachDocuments' => $coachDocuments,
            'userDocunentFolder' => $userDocunentFolder
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {

        $user = User::find($this->_user->id);
        $activity_types = ActivityType::all();
        $userDocunentFolder = User::getDocumentsFolder();
        $performance_levels = PerformanceLevel::orderBy('order', 'ASC')->get();
        $coachDocuments = CoahcesDocuments::where('user_id', $id)->get(['id', 'document_name'])->toArray();
        $user_activity_types = $user->activity_types()->select('user_activity_types.activity_type_id')->get()->toArray();
        $user_activity_types_id = [];
        foreach ($user_activity_types as $user_activity_type) {
            $user_activity_types_id[] = $user_activity_type['activity_type_id'];
        }
        $activity_genres = [];
        if(count($user_activity_types_id) > 0) {
            $activity_genres = ActivityGenre::whereIn('activity_type_id', $user_activity_types_id)->get();
        }

        return view('profile/edit',[
            'user' => $user,
            'activity_types' => $activity_types,
            'performance_levels' => $performance_levels,
            'activity_genres' => $activity_genres,
            'coachDocuments' => $coachDocuments,
            'userDocunentFolder' => $userDocunentFolder
        ]);
    }

    protected static function storeAvatar($fileName = null)
    {
        $image_path_name = public_path( "avatars/" ) . $fileName;
        $height = env('AVATAR_IMG_H', 320);
        $width = env('AVATAR_IMG_W', 250);
        $image = Image::make($image_path_name);
        if($image->height() / $image->width() > 1) {
            $background = Image::make($image_path_name)->fit($width, $height)/*->blur(80)*/
            ->fill('#ffffff');
            $image->resize($width, $height, function ($c) {
                $c->aspectRatio();
                $c->upsize();
            });
            $image = $background->insert($image, 'center');
        } else {
            $image->fit($width, $height);
        }
        File::delete($image_path_name);
        $image->save($image_path_name);
        return $image_path_name;
    }

    /** Account Settings page
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function account_settings()
    {
        $user = User::find($this->_user->id);
        return view('profile.account_settings', ['user' => $user]);
    }

    public function new_password(Request $request)
    {
        $data = $request->only('password_old', 'password', 'password_confirmation');
        $result = $this->_user->changePassword($data, $this->_user->id);
        if($result instanceof \Illuminate\Support\MessageBag) {
            return redirect()->back()->withErrors($result);
        }
        return redirect()->back()->with('success_password', 'Password changed successfully!');
    }


    public function agency_account_settings()
    {
        $user = User::find($this->_user->id);
        return view('profile.agency_account_setting', ['user' => $user]);
    }

    public function agency_new_password(Request $request)
    {
        $data = $request->only('password_old', 'password', 'password_confirmation');
        $result = $this->_user->changePassword($data, $this->_user->id);
        if($result instanceof \Illuminate\Support\MessageBag) {
            return redirect()->back()->withErrors($result);
        }
        return redirect()->back()->with('success_password', 'Password changed successfully!');
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        if($request->hasFile( 'avatar' )) {
            $validationRules = [
                'file' => 'image| mimes:jpeg,jpg,png,gif',
            ];
            $validationFieldName = [
                'file' => 'Avatar',
            ];

            $file['file'] = $request->file( "avatar" );
            $validator = Validator::make($file, $validationRules);
            $validator->setAttributeNames($validationFieldName);
            if($validator->fails()) {
                return redirect()
                    ->back()
                    ->withErrors($validator->errors());
            }
        }


        $user = User::find($this->_user->id);
        //TODO: validation
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        //$user->genres = $request->genres;
        //$user->level = $request->level;
        $user->languages = $request->get('languages');
        $user->location = $request->location;
        $user->location_state = $request->location_state;
        $user->activities = $request->activities;
        $user->birthday = $request->birthday;
        //$user->about = $request->about;
        //$user->email = $request->email;
        $user->phone = $request->phone;
        $user->wevsites = $request->wevsites;
        $user->facebook_link = $request->facebook_link;
        $user->instagram_link = $request->instagram_link;
        $user->other_site_spec = $request->other_site_spec;
        $user->coachs_site = $request->coachs_site;


        $user->gender = $request->gender;

        $user->updated_at = mysql_date();

        $user->contact_email = $request->contact_email;
        $user->title = $request->title;
        $user->certifications = $request->certifications;
        $user->teaching_positions = $request->teaching_positions;

        $user->price_summary = (int)$request->price_summary;//int validation
        $user->price_detailed = (int)$request->price_detailed;
        
        $user->vacation_start = $request->vacation_start;
        $user->vacation_end = $request->vacation_end;

        $overview = strip_tags($request->get('overview'),'<p>');
        $overview = str_replace("\r\n", "\n", $overview);
        $string = $overview;
        $prefix = substr($overview, 0, 3) == "<p>" ? '' : "<p>";
        $suffix = substr($overview, 0, 3) == "<p>" ? '' : "</p>";
        $overview =  $prefix.preg_replace(
            array("/([\n]{2,})/i", "/([\r\n]{3,})/i","/([^>])\n([^<])/i"),
            array("</p>\n<p>", "</p>\n<p>", "$1</p>\n<p>$2"),
            trim($string)).$suffix;
        $overview = str_replace("\n", "", $overview);
        $user->about = $overview;

        $user->performance_credits = $request->performance_credits;

        if($request->hasFile( 'avatar' )){
            $file = $request->file( "avatar" );
            $originalName = $file->getClientOriginalName();
            $fileName = str_random( 8 ) . "_" . $originalName;
            $file->move( public_path( "avatars/" ), $fileName );
            self::storeAvatar($fileName);
            File::delete(public_path().'/'.$user->avatar);
            $user->avatar = "/avatars/".$fileName ;
        }
        if($request->hasFile( 'baner' )){
            $file = $request->file( "baner" );
            $originalName = $file->getClientOriginalName();
            $fileName = str_random( 8 ) . "_" . $originalName;
            $file->move( public_path( "baners/" ), $fileName );
            $user->baner = "/baners/".$fileName;
        }
        $user->save();

        $user->performance_levels()->detach();
        if(!empty($request->get('level'))) {
            $user->performance_levels()->attach($request->get('level'));
        }

        $user->activity_types()->detach();
        $user->activity_types()->attach([$request->get('activity_type')]);

        $genres = $request->get('genres');
        if(is_array($genres)) {
            $user->activity_genres()->detach();
            $user->activity_genres()->attach($genres);
        }

        // PDF or other files thst approve Coache's qualification
        if (!File::exists(User::getDocumentsFolder())) {
            File::makeDirectory(User::getDocumentsFolder(), 0755, true);
        }

        if ($request->hasFile('documents')) {
            foreach ($request->documents as $document) {
                $documentName = str_random(5) . "_" . $document->getClientOriginalName();
                $document->move(User::getDocumentsFolder(), $documentName);

                $documents = new CoahcesDocuments;
                $documents->user_id = $user->id;
                $documents->document_name = $documentName;
                $documents->save();
            }
        }

        // Deleting coach documents
        $delete_coach_documents = $request->get('deleted_coach_documents');
        if(!empty($delete_coach_documents)) {
            $delete_documents_ids_confirmed = [];
            $current_document = $user->documents;
            $delete_document_items = explode(',', $delete_coach_documents);
            foreach ($delete_document_items as $key => $delete_document_item) {
                if($current_document->where('id', (int) $delete_document_item)->first()) {
                    $del_file_name = User::getDocumentsFolder() . '/' . $current_document
                            ->where('id', (int) $delete_document_item)->first()['document_name'];
                    if(File::exists($del_file_name)) {
                        File::delete($del_file_name);
                    }
                    $delete_documents_ids_confirmed[] = $delete_document_item;
                }
            }
            if(count($delete_documents_ids_confirmed) > 0) {
                CoahcesDocuments::whereIn('id', $delete_documents_ids_confirmed)->delete();
            }
        }

        $delete_gallery_items = $request->get('deleted_gallery_items');
        if(!empty($delete_gallery_items)) {
            $delete_gallery_ids_confirmed = [];
            $current_gallery = $user->gallery;
            $delete_gallery_items = explode(',', $delete_gallery_items);
            foreach ($delete_gallery_items as $key => $delete_gallery_item) {
                if($current_gallery->where('id', (int) $delete_gallery_item)->first()) {
                    $del_file_name = public_path() . '/gallery/' . $current_gallery
                            ->where('id', (int) $delete_gallery_item)->first()->path;
                    if(File::exists($del_file_name)) {
                        File::delete($del_file_name);
                    }
                    $delete_gallery_ids_confirmed[] = $delete_gallery_item;
                }
            }
            if(count($delete_gallery_ids_confirmed) > 0) {
                CoachGallery::whereIn('id', $delete_gallery_ids_confirmed)->delete();
            }
        }

        $gallery_size = $user->gallery()->count();
        $gallery_max_allowed_size = env('GALLERY_MAX_ITEMS_COUNT', 3);

        $gallery_path = public_path().'/gallery/';
        if(!File::exists($gallery_path)) {
            File::makeDirectory($gallery_path, 0755);
        }

        if($gallery_size < $gallery_max_allowed_size) {
            $processed_gallery = [];
            if($request->has('gallery_photos')) {
                $gallery_photos = explode('::', $request->get('gallery_photos'));
                if(count($gallery_photos) <= $gallery_size + count($gallery_photos)) {
                    foreach ($gallery_photos as $photo) {
                        $image_path_name = public_path().'/uploads/'.$photo;
                        if(File::exists($image_path_name)) {
                            $height = env('GALLERY_IMG_H', 290);
                            $width = env('GALLERY_IMG_W', 420);
                            $background = Image::canvas($width, $height);
                            $image = Image::make($image_path_name)->resize($width, $height, function ($c) {
                                $c->aspectRatio();
                                $c->upsize();
                            });
                            $background->insert($image, 'center');
                            $background->save($gallery_path.$photo);
                            File::delete($image_path_name);
                            $processed_gallery[] = new CoachGallery(['path' => $photo, 'visible' => 1, 'type' => 'image']);
                        }
                    }
                }
            }

            $video_file_name = $request->get('gallery_video');
            $video_url = $request->get('video');
            if(!empty($video_url)) {
                $rx = '~
                       ^(?:https?://)?
                        (?:www\.)?
                        (?:youtube\.com|youtu\.be)
                        /watch\?v=([^&]+)
                        ~x';
                $has_match = preg_match($rx, $video_url, $matches);
                if($has_match && strlen($matches[1]) == 11) { // 11 - youtube video id length
                    $processed_gallery[] = new CoachGallery(['path' => $matches[1], 'visible' => 1, 'type' => 'video']);
                }
            } else {
                if((bool) $video_file_name) {
                    $params = [
                        'title'       => $request->get('first_name') . ' ' . $request->get('last_name') . ' - Performance Video',
                        'description' => $request->get('description'),
                    ];
                    $uploaded_file = public_path() . '/uploads/' . $video_file_name;
                    $url = Youtube::upload($uploaded_file, $params);
                    File::delete($uploaded_file);
                    $processed_gallery[] = new CoachGallery(['path' => $url->getVideoId(), 'visible' => 1, 'type' => 'video']);
                }
            }

            $user->gallery()->saveMany($processed_gallery);
        }

        return back()->with(['success' => 'Updated successfully!']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function setNotifications(Request $request){
        if(!empty($request->ids)){
            $result = Notification::changeStatus($request->ids, 2);
            return response()->json($result);
        }
    }

    public function setRole($role, Redirector $redirect){

        User::setRole($role, $this->_user->id);

        if($this->_user->facebook_id){
            $password = str_random(12);
            $this->_user->password = bcrypt($password);
            $this->_user->save();

            $mail = new Mailer();
            $mail->subject = 'Welcome to Showcase Hub';
            $mail->to_email = $this->_user->email;
            $mail->sendMail('auth.emails.newUserRegister', ['first_name'=>$this->_user->first_name]);
            $redirect->to('profile')->with('site_password', $password)->send();
        }
        $redirect->to('profile')->send();
    }

    public function getNotifications() {
        $notifications = Notification::getByUserId($this->_user->id);
//echo count($notifications);exit;
        $result = [];
        foreach($notifications as $n){
            try {
                $time_ago = Carbon::parse($n->created_at)->diffForHumans();
            } catch (\Exception $e) {
                $time_ago = '';
            }

            $result[] = [
                "id" => $n->id,
                "message" => $n->message,
                "video_id" => $n->video_id,
                "video_url" => !$this->_user->isCoach() ? '/review/'.$n->video_id : '/myreviews#video__'.$n->video_id,
                //"created_at" => !empty($days_ago) ? sprintf(ngettext("%s day ago", "%s days ago", $days_ago), $days_ago) : "Today",
                "created_at" => $time_ago,
                'status' => $n->status //to check if it was reassigned
            ];
            
        }

        return response()->json($result);
    }

    public function savePhoto(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'image'
        ]);

        if ($validator->fails()) {
            return ['error' => 'Uploaded file has to be image'];
        } else {
            return \Plupload::receive('file', function ($file) {
                $fileName = str_random(4) . "_" . $file->getClientOriginalName();
                $file->move(public_path() . '/uploads/', $fileName);
                return ['result' => 'ready', 'file' => $fileName];
            });
        }
    }

    public function updateUserProfile(Request $request){

        $user = User::find($this->_user->id);
        //TODO: validation
        $user->first_name = $request->first_name;
        $user->location = $request->location;
        //$user->location_state = $request->location_state;
        $user->activities = $request->activities;
        $user->birthday = $request->birthday;
        
        $user->phone = $request->phone;
        $user->wevsites = $request->wevsites;
        $user->updated_at = mysql_date();
        $user->about = $request->about;
        $user->save();
       
       

       return back()->with(['success' => 'Updated successfully!']);
    }
}
