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

        return [
            'id' => (int) $review->id,
            'level' => $review->video->performance_level->name,
            'level_placement' => $review->performance_level_placement->name,
            'summary' => $review->message,
            'additional_tips' => $review->additional_tips,
            'scores' => [
                'timing' => ['rating' => $review->timing, 'comment' => $review->timing_comment],
                'footwork' => ['rating' => $review->footwork, 'comment' => $review->footwork_comment],
                'alignment' => ['rating' => $review->alingment, 'comment' => $review->alingment_comment],
                'balance' => ['rating' => $review->balance, 'comment' => $review->balance_comment],
                'focus' => ['rating' => $review->focus, 'comment' => $review->focus_comment],
                'precision' => ['rating' => $review->precision, 'comment' => $review->precision_comment],
                'energy' => ['rating' => $review->energy, 'comment' => $review->energy_comment],
                'style' => ['rating' => $review->style, 'comment' => $review->style_comment],
                'creativity' => ['rating' => $review->creativity, 'comment' => $review->creativity_comment],
                'interpretation' => ['rating' => $review->interpretation, 'comment' => $review->interpretation_comment],
                'formation' => ['rating' => $review->formation, 'comment' => $review->formation_comment],
                'artistry' => ['rating' => $review->artisty, 'comment' => $review->artisty_comment],
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
                return $carry;
            }, []),
            'url' => url('/') . config('video.completed_review_path') . $review->review_url,
            'video_thumbnail' => url('/') . config('video.thumbnail_path') . $review->video->thumbnail,
        ];
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
