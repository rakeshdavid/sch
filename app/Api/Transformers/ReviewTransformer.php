<?php

namespace App\Api\Transformers;

use App\Models\Review;
use League\Fractal\TransformerAbstract;

class ReviewTransformer extends TransformerAbstract
{
    /**
     * Turn User object into a generic array.
     *
     * @param Review $review
     * @return array
     */
    public function transform(Review $review)
    {
        $review->load('performance_level_placement', 'video', 'video.questions', 'video.coach', 'video.performance_level');
        if($review->package_id == 1){
            return [
            'id' => (int) $review->id,
            'level' => $review->video->performance_level->name,
            'level_placement' => $review->performance_level_placement->name,
            'summary' => strip_tags($review->message),
            'additional_tips' => strip_tags($review->additional_tips),
            'feedback_comment' => strip_tags($review->message),
            'scores' => [
                'performance_quality' => ['rating' => $review->performance_quality_rating, 'comment' => strip_tags($review->performance_quality)],
                'technical_ability' => ['rating' => $review->technical_ability_rating, 'comment' => strip_tags($review->technical_ability)],
                'energy_style' => ['rating' => $review->energy_style_rating, 'comment' => strip_tags($review->energy_style)],
                'storytelling' => ['rating' => $review->storytelling_rating, 'comment' => strip_tags($review->storytelling)],
                'look_appearance' => ['rating' => $review->look_appearance_rating, 'comment' => strip_tags($review->look_appearance)],
                
            ],
            'overall_rating'  => round((
                    $review->performance_quality_rating +
                    $review->technical_ability_rating +
                    $review->energy_style_rating +
                    $review->storytelling_rating +
                    $review->look_appearance_rating
                ) / 5, 1),
            
            'url' => url('/') . config('video.completed_review_path') . $review->review_url,
            'video_thumbnail' => url('/') . config('video.thumbnail_path') . $review->video->thumbnail,
            'coach_avatar'=> $review->coach_avatar,
            'package_id' =>$review->package_id,
        ];
        }else{
            return [
            'id' => (int) $review->id,
            'level' => $review->video->performance_level->name,
            'level_placement' => $review->performance_level_placement->name,
            'summary' => strip_tags($review->message),
            'additional_tips' => strip_tags($review->additional_tips),
            'feedback_comment' => strip_tags($review->message),
            'scores' => [
                'timing' => ['rating' => $review->timing, 'comment' => strip_tags($review->timing_comment)],
                'footwork' => ['rating' => $review->footwork, 'comment' => strip_tags($review->footwork_comment)],
                'alignment' => ['rating' => $review->alingment, 'comment' => strip_tags($review->alingment_comment)],
                'balance' => ['rating' => $review->balance, 'comment' => strip_tags($review->balance_comment)],
                'focus' => ['rating' => $review->focus, 'comment' => strip_tags($review->focus_comment)],
                'precision' => ['rating' => $review->precision, 'comment' => strip_tags($review->precision_comment)],
                'energy' => ['rating' => $review->energy, 'comment' => strip_tags($review->energy_comment)],
                'style' => ['rating' => $review->style, 'comment' => strip_tags($review->style_comment)],
                'creativity' => ['rating' => $review->creativity, 'comment' => strip_tags($review->creativity_comment)],
                'interpretation' => ['rating' => $review->interpretation, 'comment' => strip_tags($review->interpretation_comment)],
                'formation' => ['rating' => $review->formation, 'comment' => strip_tags($review->formation_comment)],
                'artistry' => ['rating' => $review->artisty, 'comment' => strip_tags($review->artisty_comment)],
            ],
            'overall_rating'  => round((
                    $review->artisty +
                    $review->formation +
                    $review->interpretation +
                    $review->creativity +
                    $review->style +
                    $review->energy +
                    $review->precision +
                    $review->timing +
                    $review->footwork +
                    $review->alingment +
                    $review->balance +
                    $review->focus
                ) / 12, 1),
            'questions' => $review->video->questions->reduce(function ($carry, $question) {
                $carry[] = [
                    'question_number' => $question->question_number,
                    'question' => $question->question,
                    'answer' => $question->answer,
                ];
                return array_slice($carry,0,3);
            }, []),
            'url' => url('/') . config('video.completed_review_path') . $review->review_url,
            'video_thumbnail' => url('/') . config('video.thumbnail_path') . $review->video->thumbnail,
            'coach_avatar'=> $review->coach_avatar,
            'package_id' =>$review->package_id,
        ];
        }
        
    }

    public function covtime($youtube_time) {
        preg_match_all('/(\d+)/',$youtube_time,$parts);

        // Put in zeros if we have less than 3 numbers.
        if (count($parts[0]) == 1) {
            array_unshift($parts[0], "0", "0");
        } elseif (count($parts[0]) == 2) {
            array_unshift($parts[0], "0");
        }

        $sec_init = $parts[0][2];
        $seconds = $sec_init%60;
        $seconds_overflow = floor($sec_init/60);

        $min_init = $parts[0][1] + $seconds_overflow;
        $minutes = ($min_init)%60;
        $minutes_overflow = floor(($min_init)/60);

        $hours = $parts[0][0] + $minutes_overflow;

        if($hours != 0)
            return intval(($hours * 60 + $minutes) * 60 + $seconds);
        else
            return $minutes * 60 + $seconds;
    }
}
