<?php

namespace App\Http\Controllers;

use App\Models\TemporaryReview;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use App\Models\Video;
use File;
use Storage;

class TemporaryReviewController extends Controller
{
    private $_user = null;

    public function __construct(Request $request, Redirector $redirect)
    {

        $this->_user = $request->user();

        if (empty($this->_user)) {
            $redirect->to('login')->send();
        }
    }

    public function rewriteReview($video_id)
    {
        $video = Video::where(['id' => $video_id, 'coach_id' => auth()->user()->id])
            ->with('temporary_review')
            ->firstOrFail();

        if ($video->temporary_review) {
            TemporaryReview::whereId($video->temporary_review->id)->delete();
            if ($video->temporary_review->review_url) {
                $temp_review_path = public_path(config('video.temp_review_path')) . $video->temporary_review->review_url;
                unlink_if_exist($temp_review_path);
            }
        }

        return redirect('review/create/' . $video_id);
    }

    public function checkConcatVideoProgress($video_id)
    {
        $video = Video::whereId($video_id)->with('temporary_review')->first();
        $video_path = public_path() . config('video.user_video_path');
        $progress_file = pathinfo($video_path . $video->url)['filename'] . '.txt';
        $progress_review_path = public_path() . config('video.progress_review_path') . $progress_file;

        if ($video->temporary_review) {
            if ($video->temporary_review->video_processed_status) {
                return response()->json([
                    'progress' => 100,
                    'ready' => true,
                    'url' => url('/') . config('video.temp_review_path') . $video->temporary_review->review_url
                ]);
            } else {
                $progress = self::checkProgressFromTempFile($progress_review_path, $video->temporary_review);
                return response()->json(['progress' => $progress, 'ready' => false, 'url' => '','test'=>$progress_review_path]);
            }
        } else {
            return response()->json(['progress' => 0, 'ready' => false, 'url' => '']);
        }

    }

    public function checkProgressFromTempFile($file_path, $review)
    {

        $content = @file_get_contents($file_path);

        if ($content) {
            //get duration of source
            preg_match("/Duration: (.*?), start:/", $content, $matches);
            $fromFileDuration = isset($matches[1]) ? $matches[1] : 0; //duration from file
            $fromJsonDuration = 0; //duration from review play_time

            $actions = (array)json_decode($review->play_time);
            foreach ($actions as $key => $action) {
                if ($action == 'stop') $fromJsonDuration = intval($key / 1000);
            }
            $rawDuration = ($fromFileDuration >= $fromJsonDuration) ? $fromFileDuration : $fromJsonDuration;

            //rawDuration is in 00:00:00.00 format. This converts it to seconds.
            $ar = array_reverse(explode(":", $rawDuration));
            $duration = floatval($ar[0]);
            if (!empty($ar[1])) $duration += intval($ar[1]) * 60;
            if (!empty($ar[2])) $duration += intval($ar[2]) * 60 * 60;

            //get the time in the file that is already encoded
            preg_match_all("/time=(.*?) bitrate/", $content, $matches);

            $rawTime = array_pop($matches);

            //this is needed if there is more than one match
            if (is_array($rawTime)) {
                $rawTime = array_pop($rawTime);
            }

            //rawTime is in 00:00:00.00 format. This converts it to seconds.
            $ar = array_reverse(explode(":", $rawTime));
            $time = floatval($ar[0]);
            if (!empty($ar[1])) $time += intval($ar[1]) * 60;
            if (!empty($ar[2])) $time += intval($ar[2]) * 60 * 60;

            //calculate the progress
            $progress = round(($time / $duration) * 100);

            return $progress > 2 ? $progress - 1 : $progress;

        }
    }

}
