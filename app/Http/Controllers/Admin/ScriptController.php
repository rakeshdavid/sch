<?php

namespace App\Http\Controllers\Admin;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Routing\Redirector;
use App\Http\Controllers\PaymentsController;
use App\Models\User;
use App\Models\ChallengeReviewNew;
use App\Models\ChallengesParticipant;
use App\Models\Notification;
use App\Models\Transaction;
use App\Http\Helpers\Mailer;

class ScriptController extends Controller
{
   public function __construct(Request $request, Redirector $redirect)
   {
      //  $this->_user = auth()->user();
      //  $this->middleware(['auth']);
      //  if (empty($this->_user)) {
      //      $redirect->to('login')->send();
      //  }
   }
   public function index(){
      // echo 'dfd';exit;
      $challengeReviews = ChallengeReviewNew::with('participant')->where('created_at','>=','2021-03-01')
      ->where('created_at','<=','2021-03-01 23:59:59')
         // ->whereDoesntHave('participant', function($q){
         //    $q->where('status', challengesParticipant::STATUS_REVIEWED);
         // })
         ->get();
      dd($challengeReviews->toArray());
      foreach($challengeReviews as $challengeReview){
         // $transactions = Transaction::where('participation_type','C')->where('video_id',$challengeReview->challenge_participant_id)->where('coach_id',$challengeReview->review_by_user_id)
         // ->where('id','!=','193')->exists();
         // if(!$transactions){
            
         // }
         echo 'Payment report: Participant id = '.$challengeReview->participant->id.' , ChallengeReviewID = '.$challengeReview->id.' , Status = ';
         $status = 'Failed';
         if(self::checkIfApproved($challengeReview->participant, 'C')){
            $status = 'Successfull';
         }
         echo $status;
// echo 'dfd';exit;
      }
    }
   public function checkIfApproved($participant, $participation_type)
   {
      // dd($participant->toArray());exit;
      // if($participant->status == challengesParticipant::STATUS_REVIEWED){
      //    return true;
      // } 
// echo $participant->review_by_user_id;exit;
      if($participation_type == 'C'){
         $reviewedBYUserId = $participant->coach_id;
         $reviewedByUser = User::find($reviewedBYUserId);
         $reviewedByUserFirstName = $reviewedByUser->first_name;
         $participantModel = 'App\\Models\\ChallengesParticipant';
      }else{
         $reviewedBYUserId = $participant->agency_id;
         $participantModel = 'App\\Models\\AuditionList';
      }
      if( !Transaction::where('video_id', $participant->id)->where('participation_type', $participation_type)->exists() ) { // !Transaction
         $transfer = PaymentsController::transfer( $participant->id, $participation_type, $reviewedBYUserId );
         if($transfer===false){
               return response()->json(['error'=>true, 'msg'=>'Transfer error. Try later.']);
         }
      }
      // echo 'done';exit;
      $user_id = $participant->user_id;
      $notification["user_id"] = $user_id;
      $notification["sender_id"] = $reviewedBYUserId;
      $notification["video_id"] = $participant->id;
      $notification["participation_type"] = $participation_type;
      $notification["status"] = 1;
      $notification["message"] = '<a href="/profile/' . $reviewedBYUserId . '">' . $reviewedByUser->first_name
         . ' ' . $reviewedByUser->last_name . '</a> added a new review.';
      $notification["created_at"] = mysql_date();
      $notification["updated_at"] = mysql_date();
      Notification::saveNotification($notification);

      app($participantModel)->changeStatus($participant->id, app($participantModel)->STATUS_REVIEWED);
      /* mail to performer and coach */

      $performer = User::select('first_name', 'email')->where('id', $user_id)->first();
      $performer_mail = new Mailer();
      $performer_mail->subject = 'Coach completed a challenge participant review ';
      $performer_mail->to_email = $performer->email;
      $performer_mail->sendMail('auth.emails.coachCompletedReview',
         [
            'reviewer' => 'coach',
            'user_name' => $performer->first_name,
            'coach_name' => $reviewedByUser->first_name
         ]);

      $coach_mail = new Mailer();
      $coach_mail->subject = 'Payment sent by Showcase';
      $coach_mail->to_email = $reviewedByUser->email;
      $coach_mail->sendMail('auth.emails.paymentSentByShowcase',
         [
               'user_name' => $performer->first_name,
               'coach_name' => $reviewedByUser->first_name
         ]);


      return true;
   }
}
