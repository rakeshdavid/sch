<?php

namespace App\Http\Controllers\Admin;

use App\Models\UserGenre;
use App\Models\UserActivityType;
use App\Models\UserPerformanceLevel;
use File;
use Image;
use Youtube;
use Validator;
use App\Models\User;
use App\Models\PerformanceLevel;
use App\Models\ActivityType;
use App\Models\ActivityGenre;
use App\Models\RegisterToken;
use Illuminate\Http\Request;
use App\Http\Helpers\Mailer;
use App\Http\Controllers\AdminController;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;

class UsersController extends AdminController
{
    public function index()
    {
        return view('admin.users.index');
    }

    public function usersData(Request $request)
    {
        return User::getUsersData($request);
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

    public function createUser(Request $request)
    {
        $activity_types = ActivityType::get()->toArray();
        $performance_levels = PerformanceLevel::get()->toArray();

        return view('admin.users.create', [
            'activity_types' => $activity_types,
            'performance_levels' => $performance_levels
        ]);
    }

    public function storeUser(StoreUserRequest $request)
    {
        $pass = str_random(6);

        $user = new User;
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->birthday = $request->birthday;
        $user->gender= isset($request->gender) ? $request->gender : '';
        $user->location = $request->location;
        $user->location_state = $request->location_state;
        $user->contact_email = $request->contact_email;
        $user->phone = $request->phone;
        $user->wevsites = $request->wevsites;
        $user->social_links = $request->social_links;
        $user->role = User::USER_ROLE;
        $user->password = bcrypt($pass);

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
            $user->avatar = $avatar_path;
        }

        // User overview
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

        $user->save();

        // Users activities & genres
        if ($request->has('activity_type')) {
            //$user->activity_types()->sync(array_flatten($request->get('activity_type')));
            // Crunh
            $user->activity_types()->sync(['0' => $request->get('activity_type')]);
        }
        if ($request->has('genres')) {
            $user->activity_genres()->sync(array_flatten($request->get('genres')));
        }

        // Users Perfomance Levels
        if ($request->has('performance_levels')) {
            $user->performance_levels()->sync(array_flatten($request->get('performance_levels')));
        }

        $registered_user = [
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'contact_email' => $user->contact_email,
            'location' => $user->location,
            'location_state' => $user->location_state,
            'password' => $pass
        ];

        $mail = new Mailer();
        $mail->subject = 'Welcome to Showcase Hub';
        $mail->to_email = $user->email;
        $mail->sendMail('auth.emails.userRegisteredCoach', ['user_data' => $registered_user]);

        return redirect(route('admin.users.index'))->with(['success' => 'New user successfully created!']);
    }

    public function editUser($id)
    {
        $user = User::find($id);

        if($user && $user->isUser()) {
            $user_levl = User::find($id);
            $activity_types = ActivityType::get()->toArray();
            $performance_levels = PerformanceLevel::get()->toArray();
            $userActivityTypes = UserActivityType::where('user_id', $id)->get()->toArray();
            $userPerformanceLevels = UserPerformanceLevel::where('user_id', $id)->first();

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

            return view('admin.users.edit', [
                'activity_types' => $activity_types,
                'userPerformanceLevels' => isset($userPerformanceLevels) ? $userPerformanceLevels->toArray() : false,
                'userActivityTypes' => $userActivityTypes,
                'performance_levels' => $performance_levels,
                'user' => $user,
                'user_levl' => $user_levl,
                'activity_genres' => $activity_genres,
                'user_crunch' => $user_crunch
            ]);
        } else {
            return abort(404);
        }

    }

    public function updateUser(UpdateUserRequest $request)
    {
        $user = User::find($request->id);
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->gender = $request->gender;
        $user->birthday = $request->birthday;
        $user->location = $request->location;
        $user->location_state = $request->location_state;
        $user->contact_email = $request->contact_email;
        $user->phone = $request->phone;
        $user->wevsites = $request->wevsites;
        $user->social_links = $request->social_links;

        // Coache's avatar upload
        if (!File::exists(User::getAvatarsFolder())) {
            File::makeDirectory(User::getAvatarsFolder(), 0755, true);
        }

        if ($request->hasFile('profile_photo')) {
            $fileProfile = $request->profile_photo;
            $fileProfileName = str_random(5) . "_" . $fileProfile->getClientOriginalName();
            $fileProfile->move(User::getAvatarsFolder(), $fileProfileName);

            self::storeAvatar($fileProfileName);
            File::delete(User::getAvatarsFolder() . '/' . $user->avatar);

            $avatar_path = User::getAvatarsFolder() . '/' . $fileProfileName;
            $user->avatar = $avatar_path;
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
        $user->about = $overview;

        $user->save();

        // Coache's activities & genres
        if ($request->has('activity_type')) {
            //$user->activity_types()->sync(array_flatten($request->get('activity_type')));
            // Crunh
            $user->activity_types()->sync(['0' => $request->get('activity_type')]);
        }
        if ($request->has('genres')) {
            $user->activity_genres()->sync(array_flatten($request->get('genres')));
        }

        // users Perfomance Levels
        if ($request->has('performance_levels')) {
            $user->performance_levels()->sync(array_flatten($request->get('performance_levels')));
        }

        return back()->with(['success' => 'User credentials successfully updated!']);
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
        $user = User::find($id);

        if(count($user->videos())) {
            $user->videos()->delete();
            foreach ($user->videos as $video) {
                File::delete(User::getVideosFolder(). '/' . $video->name);
            }
        }

        if(count($user->gallery())) {
            $user->gallery()->delete();
            foreach ($user->gallery as $gallery) {
                File::delete(User::getGalleryFolder(). '/' . $gallery->path);
            }
        }
        
        UserGenre::where('user_id', $id)->delete();
        UserActivityType::where('user_id', $id)->delete();
        UserPerformanceLevel::where('user_id', $id)->delete();

        return response()->json(['success' => $user->delete()]);
    }
}
