<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Notification extends Model
{
    public static function saveNotification($data)
    {
        DB::table('notifications')->insert($data);
    }

    public static function getById($id)
    {
        $result = DB::table('notifications')->where('id', '=', $id)->first();
        return $result;
    }

    public static function getByUserId($id)
    {
        $NEW_STATUS = 1;
        $VIEWED_STATUS = 2;
        $REMEDY_STATUS = 3;

        $result = DB::table('notifications')
            ->join('videos', 'notifications.video_id', '=', 'videos.id')
            ->where('notifications.user_id', '=', $id)
            ->whereIn('notifications.status', [$NEW_STATUS, $REMEDY_STATUS])
            ->when(auth()->user()->isCoach(), function ($query) {
                return $query->where('videos.is_reformatted', 1);
            })
            ->orderBy('notifications.created_at', 'desc')
            ->get(['notifications.*']);

        return $result;
    }

    public static function changeStatus(array $ids, $status)
    {
        $res = DB::table('notifications')
            ->whereIn('id', $ids)
            ->update(['status' => $status]);
        return $res;
    }

}
