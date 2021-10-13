<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Video;
use App\Models\Notification;
use App\Models\PaymentsList;
use Illuminate\Http\Request;
use App\Http\Controllers\AdminController;

class AnalyticsController extends AdminController
{
    public function index()
    {
        $totalUsers = User::getUsersCount();
        $totalCoaches = User::getCoachesCount();
        $activeUsers = User::getActiveUsersCount();
        $activeCoaches = User::getActiveCoachesCount();

        //User::test();

        $in3Days = count(User::getCodeRedVideos(3));
        $in1Day = count(User::getCodeRedVideos(4));
        $timeIsUp = count(User::getCodeRedVideos(5));

        return view('admin.analytics.index',
            compact("totalUsers", "totalCoaches", "activeUsers", "activeCoaches", "in3Days", "in1Day", "timeIsUp")
        );
    }

    public function showActiveCoaches()
    {
        return view('admin.analytics.active-coaches-page');
    }

    public function showActiveCoachesTable(Request $request)
    {
        return User::getActiveCoaches($request);
    }

    public function showActiveUsers()
    {
        return view('admin.analytics.active-users-page');
    }

    public function showActiveUsersTable(Request $request)
    {
        return User::getActiveUsers($request);
    }

    public function codeRed()
    {
        $timeIsUp = User::getCodeRedVideos(5);

        $table = [];

        foreach ($timeIsUp as $key => $timing) {
            if ($timing->user()->exists()) {
                $table[$key]['user_data'] = User::where('id', $timing['user_id'])->get()->toArray();
                $table[$key]['coach_data'] = User::where('id', $timing['coach_id'])->get()->toArray();
            }
        }

        return view('admin.analytics.code-red',
            compact('table')
        );
    }

    public function usersCodeRed(Request $request)
    {
        return User::getCodeRedData($request);
    }

    public function showNotActiveCoaches()
    {
        return User::getNotActiveCoaches();
    }

    public function remedyCoaches(Request $request)
    {
        $requestData = $request->only('video_id', 'coach_id'); // get request param

        // Change coach_id in Videos
        $video = Video::find($requestData['video_id']);
        $video->coach_id = $requestData['coach_id'];
        $video->save();

        // Change coach_id in Payments_lists
        $payments_lists = PaymentsList::where('video_id', $requestData['video_id'])->firstOrFail();
        $payments_lists->coach_id = $requestData['coach_id'];
        $payments_lists->save();

        // Get User data (like in PaymentsController) and send notification to Coach
        $user = User::select('first_name', 'last_name', 'email')->where('id', $video->user_id)->first();
        $coach = User::select('first_name', 'last_name', 'email')->where('id', $video->coach_id)->first();

        $nt_data = [];
        $nt_data["user_id"] =  $video->coach_id;
        $nt_data["sender_id"] = $video->user_id;
        $nt_data["video_id"] = $video->id;
        $nt_data["status"] = 1;
        $nt_data["message"] = 'You\'ve been assigned to a <a href="/profile/'. $video->user_id . '">' . $user->first_name . ' ' . $user->last_name
            . '\'s</a> video!';
        $nt_data["created_at"] = mysql_date();
        $nt_data["updated_at"] = mysql_date();
        Notification::saveNotification($nt_data);

        // Get User data (like in PaymentsController) and send notification to User
        $notification = [];
        $notification["user_id"] = $video->user_id;
        $notification["sender_id"] = $video->coach_id;
        $notification["video_id"] = $video->id;
        $notification["status"] = 3;
        $notification["message"] = 'We\'ve reassigned a <a href="/profile/'. $coach->user_id . '">' . $coach->first_name
            . ' ' . $coach->last_name .' to your video! This new coach will make every 
            effort to complete the review as soon as possible!';
        $notification["created_at"] = mysql_date();
        $notification["updated_at"] = mysql_date();
        Notification::saveNotification($notification);

        if ($video->save() && $payments_lists->save())  {
            $message = 'The coach was successfully reassigned!';
        } else {
            $message = 'An error occured while request was running...';
        }

        return $message;
    }

}
