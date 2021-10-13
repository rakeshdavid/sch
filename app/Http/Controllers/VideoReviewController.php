<?php

namespace App\Http\Controllers;

use App\Models\PerformanceLevel;
use App\Models\ReviewQuestion;
use App\Models\TemporaryReview;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use App\Http\Requests;
use App\Models\Video;
use App\Models\User;
use App\Models\Review;
use App\Models\Notification;
use App\Models\Transaction;
use File;
use Storage;
use Carbon\Carbon;
use App\Http\Helpers\Mailer;
use Illuminate\Support\Facades\DB;
use App\Jobs\ConcatReviewVideoAudio;
use Illuminate\Support\Facades\Log;

class VideoReviewController extends Controller
{
    private $_user = null;

    public function __construct(Request $request, Redirector $redirect)
    {

        $this->_user = $request->user();

        if (empty($this->_user)) {
            $redirect->to('login')->send();
        }
    }

    public function getAllReviewedVideo(){
    	$result = Review::getByUserIdList($this->_user->id);
        $allresult = Review::getByUserIdListAll($this->_user->id);
    	$model = Review::find(2);
    	foreach ($result as &$review){
                if (!empty($review)) {
                    $review->package_id = Video::getVideoPackage($review->video_id);
                    if($review->package_id == 1){
                        $review->overall_rating = round((
                            $review->performance_quality_rating +
                            $review->technical_ability_rating +
                            $review->energy_style_rating +
                            $review->storytelling_rating +
                            $review->look_appearance_rating
                            
                            ) / 5, 1);
                    }else{
                        $review->overall_rating = round((
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
                        ) / 12, 1);
                    }
                    $review->overall_rating_backup = $review->overall_rating;
                    $review->days_ago = floor((time() - strtotime($review->created_at)) / 3600 / 24);
                }
            }
        foreach ($allresult as &$reviewtemp){
                if (!empty($reviewtemp)) {
                    $reviewtemp->package_id = Video::getVideoPackage($reviewtemp->video_id);
                    if($reviewtemp->package_id == 1){
                        $reviewtemp->overall_rating = round((
                            $reviewtemp->performance_quality_rating +
                            $reviewtemp->technical_ability_rating +
                            $reviewtemp->energy_style_rating +
                            $reviewtemp->storytelling_rating +
                            $reviewtemp->look_appearance_rating
                            
                            ) / 5, 1);
                    }else{
                        $reviewtemp->overall_rating = round((
                            $reviewtemp->artisty +
                            $reviewtemp->formation +
                            $reviewtemp->interpretation +
                            $reviewtemp->creativity +
                            $reviewtemp->style +
                            $reviewtemp->energy +
                            $reviewtemp->precision +
                            $reviewtemp->timing +
                            $reviewtemp->footwork +
                            $reviewtemp->alingment +
                            $reviewtemp->balance +
                            $reviewtemp->focus
                        ) / 12, 1);
                    }
                    $reviewtemp->overall_rating_backup = $reviewtemp->overall_rating;
                    $reviewtemp->days_ago = floor((time() - strtotime($reviewtemp->created_at)) / 3600 / 24);
                }
            }
         //Beginner level
         $beginners = Review::getAllReviewedByLevel($this->_user->id,1);
    	
    	foreach ($beginners as &$review1){
                if (!empty($review1)) {
                    $review1->package_id = Video::getVideoPackage($review1->video_id);
                    if($review1->package_id == 1){
                        $review1->overall_rating = round((
                            $review1->performance_quality_rating +
                            $review1->technical_ability_rating +
                            $review1->energy_style_rating +
                            $review1->storytelling_rating +
                            $review1->look_appearance_rating
                            
                            ) / 5, 1);
                    }else{
                        $review1->overall_rating = round((
                            $review1->artisty +
                            $review1->formation +
                            $review1->interpretation +
                            $review1->creativity +
                            $review1->style +
                            $review1->energy +
                            $review1->precision +
                            $review1->timing +
                            $review1->footwork +
                            $review1->alingment +
                            $review1->balance +
                            $review1->focus
                        ) / 12, 1);
                    }
                    $review1->overall_rating_backup = $review->overall_rating;
                    $review1->days_ago = floor((time() - strtotime($review1->created_at)) / 3600 / 24);
                }
            }
        //Intermediate level
        $intermediate = Review::getAllReviewedByLevel($this->_user->id,2);
    	
    	foreach ($intermediate as &$review2){
                if (!empty($review2)) {
                    $review2->package_id = Video::getVideoPackage($review2->video_id);
                    if($review2->package_id == 1){
                        $review2->overall_rating = round((
                            $review2->performance_quality_rating +
                            $review2->technical_ability_rating +
                            $review2->energy_style_rating +
                            $review2->storytelling_rating +
                            $review2->look_appearance_rating
                            
                            ) / 5, 1);
                    }else{
                    $review2->overall_rating = round((
                            $review2->artisty +
                            $review2->formation +
                            $review2->interpretation +
                            $review2->creativity +
                            $review2->style +
                            $review2->energy +
                            $review2->precision +
                            $review2->timing +
                            $review2->footwork +
                            $review2->alingment +
                            $review2->balance +
                            $review2->focus
                        ) / 12, 1);
                    }
                    $review2->overall_rating_backup = $review->overall_rating;
                    $review2->days_ago = floor((time() - strtotime($review2->created_at)) / 3600 / 24);
                }
            }
        $advance = Review::getAllReviewedByLevel($this->_user->id,3);
    	
    	foreach ($advance as &$review3){
                if (!empty($review3)) {
                    $review3->package_id = Video::getVideoPackage($review3->video_id);
                    if($review3->package_id == 1){
                        $review3->overall_rating = round((
                            $review3->performance_quality_rating +
                            $review3->technical_ability_rating +
                            $review3->energy_style_rating +
                            $review3->storytelling_rating +
                            $review3->look_appearance_rating
                            
                            ) / 5, 1);
                    }else{
                        $review3->overall_rating = round((
                            $review3->artisty +
                            $review3->formation +
                            $review3->interpretation +
                            $review3->creativity +
                            $review3->style +
                            $review3->energy +
                            $review3->precision +
                            $review3->timing +
                            $review3->footwork +
                            $review3->alingment +
                            $review3->balance +
                            $review3->focus
                        ) / 12, 1);
                    }
                    $review3->overall_rating_backup = $review->overall_rating;
                    $review3->days_ago = floor((time() - strtotime($review3->created_at)) / 3600 / 24);
                } 
            }
        // echo "<pre>";      
        // print_r($result);
        // echo "</pre>";
    	return view('review/my-reviews', ["allreview"=>$allresult,"reviews" => $result,'beginners'=>$beginners,'intermediate'=>$intermediate,'advance'=>$advance])->withModel($model);
    }
}
