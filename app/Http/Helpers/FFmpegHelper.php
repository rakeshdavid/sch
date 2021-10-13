<?php

namespace App\Http\Helpers;

use Illuminate\Support\Facades\Log;

class FFmpegHelper
{

    public static function validateVideo($video_path)
    {
        if (!self::checkExtension($video_path)) return false; //check if extension is supported HTML player
        if (self::getVideoFps($video_path) > config('video.max_fps')) return false; //check video fps, normal < 30
        if (self::getVideoBitrate($video_path) > config('video.max_bit_rate')) return false; // check video bitrate, normal HD 1.2M-1.5M

        return true;
    }

    public static function getVideoFps($video_path)
    {
        $command = env('FFPROBE_ALIAS', 'ffprobe')
            . ' -v error -select_streams v -of default=noprint_wrappers=1:nokey=1 -show_entries stream=r_frame_rate '
            . $video_path;

        $res = shell_exec($command);
        $fps_args = explode("/", $res);
        $fps = intval($fps_args[0]) / intval($fps_args[1]);

        return round($fps);
    }

    public static function getVideoBitrate($video_path)
    {
        $command = env('FFPROBE_ALIAS', 'ffprobe')
            . ' -v error -show_entries format=bit_rate -of default=noprint_wrappers=1:nokey=1 '
            . $video_path;
        $bitrate = intval(shell_exec($command));

        return $bitrate;
    }

    public static function checkExtension($video_path)
    {
        $extension = pathinfo($video_path)['extension'];

        return in_array($extension, config('video.html_video_formats'));
    }

    public static function limitVideoBitrate($video_path)
    {
        $command = env('FFPROBE_ALIAS', 'ffprobe')
            . ' -v error -show_entries format=bit_rate -of default=noprint_wrappers=1:nokey=1 '
            . $video_path;
        $bitrate = intval(shell_exec($command));
        $max_bitrate = config('video.max_bit_rate');

        if($bitrate){
            return $bitrate > $max_bitrate ? $max_bitrate : $bitrate;
        }else{
            return $max_bitrate;
        }
    }
}
