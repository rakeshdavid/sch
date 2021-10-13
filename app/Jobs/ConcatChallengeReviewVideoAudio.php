<?php

namespace App\Jobs;

use App\Models\ChallengeTempReview;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Video;
use App\Http\Helpers\FFmpegHelper;
use Illuminate\Support\Facades\Log;
use App\Models\ChallengesParticipant;
class ConcatChallengeReviewVideoAudio extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $job_info;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($job_info)
    {
        $this->job_info = $job_info;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $video = ChallengesParticipant::whereId($this->job_info['participant_id'])->first();
        $temp_review = ChallengeTempReview::where('participant_id', $video->id)->first();

        if (!$temp_review->video_processed_status && !file_exists(self::getProgressFilePath($video->video_link))) {
            $review_filename = uniqid() . rand(111, 999) . '.mp4';
            //add pauses to video
            $result = self::insertPauses($video, $temp_review, $review_filename);
            //add audio to video
            if ($result) {
                self::addAudioToVideo($temp_review, $review_filename);
                ChallengeTempReview::whereId($temp_review->id)->update([
                    'video_processed_status' => 1,
                    'review_url' => $review_filename
                ]);
            }
        }


    }

    public function insertPauses($video, $review, $review_filename)
    {
        $video_path = public_path(config('video.challenge_video_path'));
        $temp_review_path = public_path(config('video.temp_review_path'));
        $video_file = $video_path . $video->video_link;
        $intervals = self::rewriteActions($review->play_time);
        $filter = self::makeFilter($intervals, $video_file);

        $insert_pauses_command = env('FFMPEG_ALIAS', 'ffmpeg')
            . ' -i ' . $video_file
            . ' -filter_complex "' . $filter . '" -map "[v]" '
            . $temp_review_path . 'noaudio_' . $review_filename
            . ' 1> ' . self::getProgressFilePath($video->video_link)
            . ' 2>&1';
        shell_exec($insert_pauses_command);
        unlink_if_exist(self::getProgressFilePath($video->video_link)); //delete progress after finish

        return file_exists($temp_review_path . 'noaudio_' . $review_filename);
    }

    public function addAudioToVideo($review, $review_filename)
    {
        $temp_review_path = public_path(config('video.temp_review_path'));
        $audio_path = self::convertWavToAcc($review->url);

        $add_audio_command = env('FFMPEG_ALIAS', 'ffmpeg')
            . ' -i ' . $temp_review_path . 'noaudio_' . $review_filename
            . ' -i ' . $audio_path . ' '
            . ' -c copy -map 0:0 -map 1:0 '
            . $temp_review_path . $review_filename;

        shell_exec($add_audio_command);
        unlink_if_exist($temp_review_path . 'noaudio_' . $review_filename); //delete noaudio review
        unlink_if_exist($audio_path); //delete audiofile
    }

    public function convertWavToAcc($audio_url)
    {
        $input_audio_path = public_path(config('video.review_audio_path')) . $audio_url;
        $output_audio_path = public_path(config('video.review_audio_path')) .  uniqid() . rand(111, 999) . '.m4a';
        $convert_audio_command = env('FFMPEG_ALIAS', 'ffmpeg')
            . ' -i ' . $input_audio_path
            . ' -codec:a aac -b:a 128k '
            . $output_audio_path;
        shell_exec($convert_audio_command);
        unlink_if_exist($input_audio_path);

        return $output_audio_path;
    }

    public function getProgressFilePath($video_url)
    {
        $progress_review_path = public_path(config('video.progress_review_path'));
        $video_path = public_path(config('video.challenge_video_path'));
        $progress_file = pathinfo($video_path . $video_url)['filename'] . '.txt';
        $path = $progress_review_path . $progress_file;

        return $path;
    }

    public function rewriteActions($play_time)
    {
        $actions = (array)json_decode($play_time);

        $intervals = [];
        $total_paused = 0;
        $pause_second = 0;
        $play_second = 0;
        foreach ($actions as $key => $action) {
            if ($action == 'pause') $pause_second = $key - $total_paused;
            if ($action == 'play') $play_second = $key - $total_paused;
            if ($pause_second && $play_second) {
                $pause = $play_second - $pause_second;
                $total_paused = $total_paused + $pause;
                $intervals[$pause_second] = $pause;
                $pause_second = 0;
                $play_second = 0;
            }

            if ($action == 'stop') {
                if ($pause_second) {
                    $intervals[$pause_second] = $key - $total_paused - $pause_second;
                } else {
                    $intervals[$key - $total_paused] = 1;
                }

            }
        }
        return $intervals;
    }

    public function makeFilter($intervals, $video_file)
    {
        $filter_part1 = '';
        $filter_part2 = '';
        $filter_part3 = '';
        $previous = 0;
        $count = 0;
        //$fps = config('video.fps');
        $fps = FFmpegHelper::getVideoFps($video_file);

        foreach ($intervals as $key => $interval) {
            if ($interval == 'stop') {

            } else {
                $filter_part1 = $filter_part1 . '[v' . $count . ']';
                // $filter_part2 = $filter_part2 . '[v' . $count . ']trim=start_frame=' . $previous * $fps . ':end_frame=' . $key * $fps . ',loop=' . $interval * $fps . ':1:' . ($key * $fps - $previous * $fps - 1) . ',setpts=N/FRAME_RATE/TB[' . $count . 'v]; ';
                $filter_part2 = $filter_part2 . '[v' . $count . ']trim=start_frame=' . intval(($previous * $fps) / 1000) . ':end_frame=' . intval(($key * $fps) / 1000) . ',loop=' . intval(($interval * $fps) / 1000) . ':1:' . (intval(($key * $fps) / 1000) - intval(($previous * $fps) / 1000) - 1) . ',setpts=N/FRAME_RATE/TB[' . $count . 'v]; ';
                $filter_part3 = $filter_part3 . '[' . $count . 'v]';
                $previous = $key;
                $count++;
            }
        }
        $filter_part1 = '[0:v]split=' . count($intervals) . $filter_part1 . ';';
        $filter_part3 = $filter_part3 . 'concat=n=' . count($intervals) . ':v=1[v]';
        $filter = $filter_part1 . $filter_part2 . $filter_part3;

        return $filter;
    }
}

