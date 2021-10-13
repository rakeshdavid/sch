<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use App\Models\Video;
use App\Models\User;
use App\Models\ChallengesParticipant;
use Facebook\Facebook as Facebook;

class IndexController extends Controller
{
    public function index(Request $request, Redirector $redirect){
        $user = $request->user();
        if(!empty($user) && !empty($user->role)) {
            $redirect->to('profile')->send();
        }

        $hostParts = explode('.', request()->getHost());
        if($hostParts[0] == env('USER_PLATFORM')) {
            $roleNameBySubDomain = User::getUserRoleName();
        } else {
            $roleNameBySubDomain = User::getCoachRoleName();
        }

        return view('index', [
            'roleNameBySubDomain' => $roleNameBySubDomain
        ]);
    }

    public function index2(Request $request, Redirector $redirect)
    {
        $user = $request->user();
        if(!empty($user) && !empty($user->role) && ($user->role == 4)) {
            //$redirect->to('profile')->send();
            //die('asdasd');
            $redirect->to('audition-agency')->send();

        }

        if(!empty($user) && !empty($user->role) && ($user->role == 1)) {
            //$redirect->to('profile')->send();
            $redirect->to('upload-new-video')->send();
        }

        if(!empty($user) && !empty($user->role) ) {
            $redirect->to('profile')->send();
            //$redirect->to('upload-new-video')->send();
        }

        // $hostParts = explode('.', request()->getHost());
        // if($hostParts[0] == env('USER_PLATFORM')) {
        //     $roleNameBySubdomain = User::getUserRoleName();
        // } elseif($hostParts[0] == env('COACH_PLATFORM')) {
        //     $roleNameBySubdomain = User::getCoachRoleName();
        // } else {
        //     $roleNameBySubdomain = User::getAdminRoleName();
        // }
        $hostParts = explode('.', request()->getHost());
        //\Log::info(print_r($hostParts, true));
        //\Log::info(' user platform : '.env('USER_PLATFORM'));
        if($hostParts[0] == env('USER_PLATFORM')) {
            $roleNameBySubdomain = User::getUserRoleName();
        } elseif($hostParts[0] == env('COACH_PLATFORM')) {
            $roleNameBySubdomain = User::getCoachRoleName();
        } elseif($hostParts[0] == env('AGENCY_PLATFORM')) {
            $roleNameBySubdomain = User::getAgencyRoleName();
        } else {
            $roleNameBySubdomain = User::getAdminRoleName();
        }
        
        return view('auth.'.$roleNameBySubdomain.'_login');
    }
    
    public function search(Request $request){
        $user = $request->user();
        if(!empty($user) && $user->isCoach()){
            $params["search_text"] = $request->get('search');
            $params["user_id"] = $user->id;
            $result = Video::search($params, $request->get('show'));
            $challengeVideo = ChallengesParticipant::newSubmission($params,$request->get('show'));
            return view('search', ["videos" => $result,'challengeVideo'=>$challengeVideo, "search_text" => $params["search_text"],
                "show" => $request->get('show'), 'user'=>$user]);
        }
        abort(404);
    }
}
