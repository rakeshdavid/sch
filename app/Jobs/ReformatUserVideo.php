<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Video;
use App\Models\ChallengesParticipant;
use App\Models\AuditionList;
use App\Http\Helpers\FFmpegHelper;
use Illuminate\Support\Facades\Log;

class ReformatUserVideo extends Job implements ShouldQueue
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
        Log::debug("Job construter");
        Log::debug($job_info);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if(@$this->job_info['participationType'] == ChallengesParticipant::PARTICIPATION_TYPE){
            $video = ChallengesParticipant::whereId($this->job_info['video_id'])->where('is_reformatted', 0)->first();
            $video_path = public_path(config('video.challenge_video_path'));
            $thumbnail_path = public_path(config('video.thumbnail_path'));
            $progress_formatting_path = public_path(config('video.challenge_video_formatting_progress_path'));
            $video_url = $video->video_link;
        }elseif(@$this->job_info['participationType'] == AuditionList::PARTICIPATION_TYPE){
            $video = AuditionList::whereId($this->job_info['video_id'])->where('is_reformatted', 0)->first();
            $video_path = public_path(config('video.audition_video_path'));
            $thumbnail_path = public_path(config('video.thumbnail_path'));
            $progress_formatting_path = public_path(config('video.audition_video_formatting_progress_path'));
            $video_url = $video->video_link;
        }else{
            $video = Video::whereId($this->job_info['video_id'])->where('is_reformatted', 0)->first();
            $video_path = public_path(config('video.user_video_path'));
            $thumbnail_path = public_path(config('video.thumbnail_path'));
            $progress_formatting_path = public_path(config('video.video_formatting_progress_path'));
            $video_url = $video->url;
        }
        $progress_file = pathinfo($video_path . $video_url)['filename'] . '.txt';
        Log::debug($progress_file);
        Log::debug("handleing job");
        if($video && !file_exists($progress_formatting_path . $progress_file)) {
            $input_video = $video_path . $video_url;
            $output_filename = pathinfo($video_path . $video_url)['filename'] . '_formatted';
            $output_video = $video_path . $output_filename . '.' . config('video.format');
            $output_thumbnail = $thumbnail_path . pathinfo($video_path . $video_url)['filename'] . '.jpg';

            if(FFmpegHelper::validateVideo($input_video)){
                //store thumbnail, rename file
                $command = env('FFMPEG_ALIAS', 'ffmpeg')
                    . ' -i ' . $input_video
                    . ' -ss 00:00:02 -vframes 1 '
                    . $output_thumbnail; //out thumbnail
                shell_exec($command);
                rename($input_video, $output_video);
                if (file_exists($output_video)) {
                    if(@$this->job_info['participationType'] == ChallengesParticipant::PARTICIPATION_TYPE){
                        ChallengesParticipant::whereId($video->id)->update([
                            'video_link' => $output_filename . '.' . config('video.format'),
                            'is_reformatted' => 1
                        ]);
                    }elseif(@$this->job_info['participationType'] == AuditionList::PARTICIPATION_TYPE){
                        AuditionList::whereId($video->id)->update([
                            'video_link' => $output_filename . '.' . config('video.format'),
                            'is_reformatted' => 1
                        ]);
                    }else{
                        Video::whereId($video->id)->update([
                            'url' => $output_filename . '.' . config('video.format'),
                            //'thumbnail' => pathinfo($video_path . $video_url)['filename'] . '.jpg',
                            'is_reformatted' => 1
                        ]);
                    }
                }
            }else{
                $bitrate = FFmpegHelper::limitVideoBitrate($input_video);
                //reformat video and store thumbnail
                $command = env('FFMPEG_ALIAS', 'ffmpeg')
                    . ' -i ' . $input_video
                    . ' -r ' . config('video.fps')
                    . ' -c:v libx264 -b:v ' . $bitrate . ' -maxrate ' . $bitrate . ' -bufsize 1M ' //reduce video size
                    . ' ' . $output_video // out reformatted video
                    . ' -ss 00:00:02 -vframes 1 '
                    . $output_thumbnail //out thumbnail
                    . ' 1> ' . $progress_formatting_path . $progress_file
                    . ' 2>&1';
                shell_exec($command);

                if (file_exists($output_video)) {
                    unlink($input_video);
                    if(@$this->job_info['participationType'] == ChallengesParticipant::PARTICIPATION_TYPE){
                        ChallengesParticipant::whereId($video->id)->update([
                            'video_link' => $output_filename . '.' . config('video.format'),
                            'is_reformatted' => 1
                        ]);
                    }elseif(@$this->job_info['participationType'] == AuditionList::PARTICIPATION_TYPE){
                        AuditionList::whereId($video->id)->update([
                            'video_link' => $output_filename . '.' . config('video.format'),
                            'is_reformatted' => 1
                        ]);
                    }else{
                        Video::whereId($video->id)->update([
                        'url' => $output_filename . '.' . config('video.format'),
                        //'thumbnail' => pathinfo($video_path . $video_url)['filename'] . '.jpg',
                        'is_reformatted' => 1
                        ]);
                    }
                    unlink_if_exist($progress_formatting_path . $progress_file);
                }
            }
        }
    }
}
