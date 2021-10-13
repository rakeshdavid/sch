<?php

namespace App\Http\Controllers\Admin;

use App\Models\UserActivityType;
use App\Models\UserGenre;
use App\Models\UserPerformanceLevel;
use File;
use Image;
use Youtube;
use Validator;
use App\Models\User;
use App\Models\CoachGallery;
use App\Models\CoahcesDocuments;
use App\Models\PerformanceLevel;
use App\Models\ActivityGenre;
use App\Models\ActivityType;
use App\Models\RegisterToken;
use Illuminate\Http\Request;
use App\Http\Helpers\Mailer;
use App\Http\Controllers\AdminController;
use App\Http\Requests\StoreCoachRequest;
use App\Http\Requests\UpdateCoachRequest;
use App\Http\Requests\CoachChallengeRequest;
use App\Http\Requests\CoachChallengeUpdateRequest;
use App\Models\Challenges;
use App\Models\ChallengesParticipant;
use App\Models\ChallengeReview;
class CoachController extends AdminController
{
    public function index()
    {
        return view('admin.coaches.index');
    }

    public function coachesData(Request $request)
    {
        return User::getCoachesData($request);
    }

    protected static function storeAvatar($fileName = null)
    {
        $image_path_name = User::getAvatarsFolder() . '/' . $fileName;
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

    public function createCoach(Request $request)
    {
        $activity_types = ActivityType::get()->toArray();
        $perfomance_levels = PerformanceLevel::get()->toArray();

        return view('admin.coaches.create', [
            'activity_types' => $activity_types,
            'perfomance_levels' => $perfomance_levels
        ]);
    }

    public function storeCoach(StoreCoachRequest $request)
    {
        $pass = str_random(6);

        $coach = new User;
        $coach->first_name = $request->first_name;
        $coach->last_name = $request->last_name;
        $coach->title = $request->title;
        $coach->email = $request->email;
        $coach->location = $request->location;
        $coach->location_state = $request->location_state;
        $coach->certifications = $request->certifications;
        $coach->teaching_positions = $request->teaching_positions;
        $coach->performance_credits = $request->performance_credits;
        $coach->contact_email = $request->contact_email;
        $coach->phone = $request->phone;
        $coach->wevsites = $request->wevsites;
        $coach->facebook_link = $request->facebook_link;
        $coach->instagram_link = $request->instagram_link;
        $coach->other_site_spec= $request->other_site_spec;
        $coach->coachs_site= $request->coachs_site;
        $coach->vacation_start = $request->vacation_start;
        $coach->vacation_end = $request->vacation_end;
        $coach->price_summary = $request->price_summary;
        $coach->price_detailed = $request->price_detailed;
        $coach->role = User::COACH_ROLE;
        $coach->password = bcrypt($pass);

        // Coache's avatar upload
        if (!File::exists(User::getAvatarsFolder())) {
            File::makeDirectory(User::getAvatarsFolder(), 0755, true);
        }

        if ($request->hasFile('profile_photo')) {
            $fileProfile = $request->profile_photo;
            $fileProfileName = str_random(5) . "_" . $fileProfile->getClientOriginalName();
            $fileProfile->move(User::getAvatarsFolder(), $fileProfileName);
            self::storeAvatar($fileProfileName);
            $avatar_path = User::getAvatarsFolder() . '/' . $fileProfileName;
            $coach->avatar = $avatar_path;
        }

        // Coach overview
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
        $coach->about = $overview;

        $coach->save();

        // Coache's activities & genres
        if ($request->has('activity_type')) {
            //$coach->activity_types()->sync(array_flatten($request->get('activity_type')));
            // Crunh
            $coach->activity_types()->sync(['0' => $request->get('activity_type')]);
        }
        if ($request->has('genres')) {
            $coach->activity_genres()->sync(array_flatten($request->get('genres')));
        }

        // Coaches Perfomance Levels
        if ($request->has('performance_levels')) {
            $coach->performance_levels()->sync(array_flatten($request->get('performance_levels')));
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
                $documents->user_id = $coach->id;
                $documents->document_name = $documentName;
                $documents->save();
            }
        }

        // Coach gallery photos
        if (!File::exists(User::getGalleryFolder())) {
            File::makeDirectory(User::getGalleryFolder(), 0755, true);
        }

        if ($request->hasFile('gallery_photos')) {
            foreach ($request->gallery_photos as $gallery_photo) {
                $galleryPhotoName = str_random(5) . "_" . $gallery_photo->getClientOriginalName();
                $image_path_name = User::getUploadsFolder() . '/' . $galleryPhotoName;

                $coach_gallery = new CoachGallery;
                $height = env('GALLERY_IMG_H', 290);
                $width = env('GALLERY_IMG_W', 420);
                $background = Image::canvas($width, $height);
                $image = Image::make($gallery_photo->getRealPath())->resize($width, $height, function ($c) {
                    $c->aspectRatio();
                    $c->upsize();
                });
                $background->insert($image, 'center');
                $background->save(User::getGalleryFolder() . '/' . $galleryPhotoName);
                File::delete($image_path_name);

                $coach_gallery->path = $galleryPhotoName;
                $coach_gallery->visible = 1;
                $coach_gallery->type = 'image';
                $coach_gallery->user_id = $coach->id;

                $coach_gallery->save();
            }
        }

        $video_url = $request->get('video');
        if (!empty($video_url)) {
            $matches = explode('/', $video_url);
            $coach_gallery = new CoachGallery([
                'path' => $matches[3],
                'visible' => 1,
                'type' => 'video',
                'user_id' => $coach->id
            ]);
            $coach_gallery->save();
        } elseif ($request->hasFile('gallery_video')) {
            $video_file_name = $request->gallery_video->getClientOriginalName();
            $params = [
                'title' => $request->get('first_name') . ' ' . $request->get('last_name') . ' - Performance Video',
                'description' => $request->get('description'),
            ];

            if (!File::exists(User::getVideosFolder())) {
                File::makeDirectory(User::getVideosFolder(), 0755, true);
            }
            $uploaded_file = User::getVideosFolder() . '/' . $video_file_name;
            $url = Youtube::upload($uploaded_file, $params);
            File::delete($uploaded_file);
            $coach_gallery = new CoachGallery([
                'path' => $url->getVideoId(),
                'visible' => 1, 'type' => 'video',
                'user_id' => $coach->id
            ]);
            $coach_gallery->save();
        }

        $registered_user = [
            'first_name' => $coach->first_name,
            'last_name' => $coach->last_name,
            'email' => $coach->email,
            'contact_email' => $coach->contact_email,
            'location' => $coach->location,
            'location_state' => $coach->location_state,
            'password' => $pass
        ];

        $mail = new Mailer();
        $mail->subject = 'Welcome to Showcase Hub';
        $mail->to_email = $coach->email;
        $mail->sendMail('auth.emails.adminRegisteredCoach', ['user_data' => $registered_user]);

        return redirect(route('admin.coaches.index'))->with(['success' => 'New coach successfully created!']);
    }

    public function editCoach($id)
    {
        $coach = User::find($id);

        if($coach && $coach->isCoach()) {
            $coach->toArray();
            $coach_levl = User::find($id);
            $coachDocuments = CoahcesDocuments::where('user_id', $id)->get(['id', 'document_name'])->toArray();
            $userDocunentFolder = User::getDocumentsFolder();
            $activity_types = ActivityType::get()->toArray();
            $perfomance_levels = PerformanceLevel::get()->toArray();
            $coachPerformanceLevels = UserPerformanceLevel::where('user_id', $id)->get()->toArray();

            // Crunch
            $user_crunch = User::find($id);
            $user_activity_types = $user_crunch->activity_types()->select('user_activity_types.activity_type_id')->get()->toArray();

            $activity_genres = [];

            if ($user_activity_types) {
                $user_activity_types_id = [];
                foreach ($user_activity_types as $user_activity_type) {
                    $user_activity_types_id[] = $user_activity_type['activity_type_id'];
                }

                if (count($user_activity_types_id) > 0) {
                    $activity_genres = ActivityGenre::whereIn('activity_type_id', $user_activity_types_id)->get();
                }
            } else {
                $activity_genres = ActivityGenre::whereIn('activity_type_id', ["activity_type_id" => 1])->get();
            }
            // Crunch end

            return view('admin.coaches.edit', [
                'activity_types' => $activity_types,
                'coachPerformanceLevels' => $coachPerformanceLevels,
                'perfomance_levels' => $perfomance_levels,
                'coach' => $coach,
                'coach_levl' => $coach_levl,
                'coachDocuments' => $coachDocuments,
                'userDocunentFolder' => $userDocunentFolder,
                'activity_genres' => $activity_genres,
                'user' => $user_crunch
            ]);
        } else {
            return abort(404);
        }

    }

    public function updateCoach(UpdateCoachRequest $request)
    {
        $coach = User::find($request->id);
        $coach->first_name = $request->first_name;
        $coach->last_name = $request->last_name;
        $coach->title = $request->title;
        $coach->email = $request->email;
        $coach->location = $request->location;
        $coach->location_state = $request->location_state;
        $coach->certifications = $request->certifications;
        $coach->teaching_positions = $request->teaching_positions;
        $coach->performance_credits = $request->performance_credits;
        $coach->contact_email = $request->contact_email;
        $coach->phone = $request->phone;
        $coach->wevsites = $request->wevsites;
        $coach->facebook_link = $request->facebook_link;
        $coach->instagram_link = $request->instagram_link;
        $coach->other_site_spec= $request->other_site_spec;
        $coach->coachs_site= $request->coachs_site;
        $coach->vacation_start = $request->vacation_start;
        $coach->vacation_end = $request->vacation_end;
        $coach->price_summary = $request->price_summary;
        $coach->price_detailed = $request->price_detailed;

        // Coache's avatar upload
        if (!File::exists(User::getAvatarsFolder())) {
            File::makeDirectory(User::getAvatarsFolder(), 0755, true);
        }

        if ($request->hasFile('profile_photo')) {
            $fileProfile = $request->profile_photo;
            $fileProfileName = str_random(5) . "_" . $fileProfile->getClientOriginalName();
            $fileProfile->move(User::getAvatarsFolder(), $fileProfileName);

            self::storeAvatar($fileProfileName);
            File::delete(User::getAvatarsFolder() . '/' . $coach->avatar);

            $avatar_path = User::getAvatarsFolder() . '/' . $fileProfileName;
            $coach->avatar = $avatar_path;
        }

        // Coach overview
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
        $coach->about = $overview;

        $coach->save();

        // Coache's activities & genres
        if ($request->has('activity_type')) {
            //$coach->activity_types()->sync(array_flatten($request->get('activity_type')));
            // Crunh
            $coach->activity_types()->sync(['0' => $request->get('activity_type')]);
        }
        if ($request->has('genres')) {
            $coach->activity_genres()->sync(array_flatten($request->get('genres')));
        }

        // Coaches Perfomance Levels
        if ($request->has('performance_levels')) {
            $coach->performance_levels()->sync(array_flatten($request->get('performance_levels')));
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
                $documents->user_id = $coach->id;
                $documents->document_name = $documentName;
                $documents->save();
            }
        }

        // Deleting coach documents
        $delete_coach_documents = $request->get('deleted_coach_documents');
        if(!empty($delete_coach_documents)) {
            $delete_documents_ids_confirmed = [];
            $current_document = $coach->documents;
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

        // Coach gallery photos
        if (!File::exists(User::getGalleryFolder())) {
            File::makeDirectory(User::getGalleryFolder(), 0755, true);
        }

        if ($request->hasFile('gallery_photos')) {
            foreach ($request->gallery_photos as $gallery_photo) {
                $galleryPhotoName = str_random(5) . "_" . $gallery_photo->getClientOriginalName();
                $image_path_name = User::getUploadsFolder() . '/' . $galleryPhotoName;

                $coach_gallery = new CoachGallery;
                $height = env('GALLERY_IMG_H', 290);
                $width = env('GALLERY_IMG_W', 420);
                $background = Image::canvas($width, $height);
                $image = Image::make($gallery_photo->getRealPath())->resize($width, $height, function ($c) {
                    $c->aspectRatio();
                    $c->upsize();
                });
                $background->insert($image, 'center');
                $background->save(User::getGalleryFolder() . '/' . $galleryPhotoName);
                File::delete($image_path_name);

                $coach_gallery->path = $galleryPhotoName;
                $coach_gallery->visible = 1;
                $coach_gallery->type = 'image';
                $coach_gallery->user_id = $coach->id;

                $coach_gallery->save();
            }
        }

        // Deleting gallery photos
        $delete_gallery_items = $request->get('deleted_gallery_items');
        if(!empty($delete_gallery_items)) {
            $delete_gallery_ids_confirmed = [];
            $current_gallery = $coach->gallery;
            $delete_gallery_items = explode(',', $delete_gallery_items);
            foreach ($delete_gallery_items as $key => $delete_gallery_item) {
                if($current_gallery->where('id', (int) $delete_gallery_item)->first()) {
                    $del_file_name = User::getGalleryFolder() . '/' . $current_gallery
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

        $video_url = $request->get('video');
        if (!empty($video_url)) {
            $matches = explode('/', $video_url);
            $coach_gallery = new CoachGallery([
                'path' => $matches[3],
                'visible' => 1,
                'type' => 'video',
                'user_id' => $coach->id
            ]);
            $coach_gallery->save();
        } elseif ($request->hasFile('gallery_video')) {
            $video_file_name = $request->gallery_video->getClientOriginalName();
            $params = [
                'title' => $request->get('first_name') . ' ' . $request->get('last_name') . ' - Performance Video',
                'description' => $request->get('description'),
            ];

            if (!File::exists(User::getVideosFolder())) {
                File::makeDirectory(User::getVideosFolder(), 0755, true);
            }
            $uploaded_file = User::getVideosFolder() . '/' . $video_file_name;
            $url = Youtube::upload($uploaded_file, $params);
            File::delete($uploaded_file);
            $coach_gallery = new CoachGallery([
                'path' => $url->getVideoId(),
                'visible' => 1, 'type' => 'video',
                'user_id' => $coach->id
            ]);
            $coach_gallery->save();
        }

        return back()->with(['success' => 'Coach credentials successfully updated!']);
        //return redirect(route('admin.coaches.index'))->with(['success' => 'Coach credentials successfully updated!']);
    }

    public function invites()
    {
        return view('admin.coaches.invites');
    }

    public function invitesData(Request $request)
    {
        return RegisterToken::toDataTable($request);
    }

    public function createInvite(Request $request)
    {
        abort_unless($request->ajax(), 404);
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255'
        ]);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors'=>$validator->errors()]);
        }

        $newInvite = RegisterToken::create($request->only('email'));

        return response()->json(['success' => $newInvite]);
    }

    public function deleteInvite(Request $request)
    {
        abort_unless($request->ajax(), 404);
        $invite = RegisterToken::findOrFail($request->get('inviteId'));

        return response()->json(['success' => $invite->delete()]);
    }

    public function delete(Request $request, $id)
    {
        abort_unless($request->ajax(), 404);
        $coach = User::find($id);

        if(count($coach->videos())) {
            $coach->videos()->delete();
            foreach ($coach->videos as $video) {
                File::delete(User::getVideosFolder(). '/' . $video->name);
            }
        }

        if(count($coach->gallery())) {
            $coach->gallery()->delete();
            foreach ($coach->gallery as $gallery) {
                File::delete(User::getGalleryFolder(). '/' . $gallery->path);
            }
        }

        if(count($coach->documents())) {
            $coach->documents()->delete();
            foreach ($coach->documents as $document) {
                File::delete(User::getDocumentsFolder(). '/' . $document->document_name);
            }
        }

        UserGenre::where('user_id', $id)->delete();
        UserActivityType::where('user_id', $id)->delete();
        UserPerformanceLevel::where('user_id', $id)->delete();

        return response()->json(['success' => $coach->delete()]);
    }
    public function coachChallenges(Request $request){
        $challenges = Challenges::with(['user'])->get();
        return view('admin.coaches.challenges',['challenges'=>$challenges]);
    }

    public function challengeParticiapnts(Request $request){
        $challenge_id = (int)request()->route('challenge_id');

        $challenge = Challenges::where('id', $challenge_id)->first();
        $user = User::find($challenge->coach_id);
        $participants = ChallengesParticipant::with(['user','challenges','review'])->where('payment_status','=', 1)->where( 'stripe_id', '!=', 'NULL')->where('challenge_id',$challenge_id)->get();
        $model = ChallengeReview::find(1);
        return view('admin.coaches.participants',['participants'=>$participants,'agency'=>$user])->withModel($model);
    }

     public function newCoachChallenges(Request $request){
        $coaches = User::where('role',2)->get();
        return view('admin.coaches.addChallenges',['coaches'=>$coaches]);
    }

    public function addCoachChallenege(CoachChallengeRequest $request){
        $challenge = new Challenges;
        $challenge->coach_id = request()->get('coach_id');
        $challenge->added_by = auth()->user()->id;
        $challenge->challenges_name = request()->get('challenge-name');
        $challenge->title = request()->get('title');
        $challenge->challenges_fee = request()->get('challenge-fee');
        $challenge->deadline = request()->get('challenge-deadline');
        $challenge->short_desc = request()->get('short-desc');
        //$challenge->challenges_detail = request()->get('challenge-detail');
        $challenge->description = request()->get('challenge-description');
        $challenge->requirement = request()->get('challenge-requirement');
        $challenge->gift = request()->get('gift');
        $challenge->additional_gift = request()->get('additional-gift');
        // if ($request->hasFile('logo')) {
        //     $fileProfile = $request->logo;
        //     $fileProfileName = str_random(8) . "logo." . $fileProfile->extension();
        //     $path = public_path().'/uploads/challenge';
        //     $fileProfile->move($path, $fileProfileName);
            
        //     $avatar_path = $fileProfileName;
        //     $challenge->logo = $avatar_path;
        // }
        if ($request->hasFile('header_image')) {
            $fileProfile = $request->header_image;
            $fileProfileName = str_random(8) . "header-image." . $fileProfile->extension();
            $path = public_path().'/uploads/challenge';
            $fileProfile->move($path, $fileProfileName);
            
            $image_path =  $fileProfileName;
            $challenge->header_image = $image_path;
        }
        $challenge->save();

        return back()->with(['success' => 'New challenge for coach has been added!']);
    }

    public function adminEditChallenge(Request $request){
        $challenge_id = (int)request()->route('challenge_id');
        if($challenge_id == ''){
            return abort(404);
        }
        $challenge_detail = Challenges::find($challenge_id);
        $coaches = User::where('role',2)->get();
        return view('admin.coaches.edit-challenge',['coaches'=>$coaches,'challenges'=>$challenge_detail]);
    }

    public function editCoachChallenege(CoachChallengeUpdateRequest $request){
        $challenge = Challenges::find(request()->get('challenge_id'));
        $challenge->coach_id = request()->get('coach_id');
        $challenge->added_by = auth()->user()->id;
        $challenge->challenges_name = request()->get('challenge-name');
        $challenge->title = request()->get('title');
        $challenge->challenges_fee = request()->get('challenge-fee');
        $challenge->deadline = request()->get('challenge-deadline');
        $challenge->short_desc = request()->get('short-desc');
        //$challenge->challenges_detail = request()->get('challenge-detail');
        $challenge->description = request()->get('challenge-description');
        $challenge->requirement = request()->get('challenge-requirement');
        $challenge->gift = request()->get('gift');
        $challenge->additional_gift = request()->get('additional-gift');
        // if ($request->hasFile('logo')) {
        //     $fileProfile = $request->logo;
        //     $fileProfileName = str_random(8) . "logo." . $fileProfile->extension();
        //     $path = public_path().'/uploads/challenge';
        //     $fileProfile->move($path, $fileProfileName);
            
        //     $avatar_path =  $fileProfileName;
        //     $challenge->logo = $avatar_path;
        // }
        if ($request->hasFile('header_image')) {
            $fileProfile = $request->header_image;
            $fileProfileName = str_random(8) . "header-image." . $fileProfile->extension();
            $path = public_path().'/uploads/challenge';
            $fileProfile->move($path, $fileProfileName);
            
            $image_path = $fileProfileName;
            $challenge->header_image = $image_path;
        }
        $challenge->save();

        return back()->with(['success' => 'Challenge has been updated for coach!']);
    }
}
