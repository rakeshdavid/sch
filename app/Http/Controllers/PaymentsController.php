<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Video;
use App\Models\ChallengesParticipant;
use App\Models\AuditionList;
use App\Models\StripeConnection;
use App\Models\PaymentsList;
use App\Models\Transaction;
use App\Models\TransactionLog;
use App\Models\Notification;
use App\Models\Setting;
use Illuminate\Http\Request;
use Stripe\Charge;
/*use Stripe\Customer;*/
use Stripe\Stripe;
use Stripe\Refund;
use Stripe\Transfer;
/*use Stripe\Balance;
use Stripe\Account;
use Stripe\Token;*/
use App\Http\Helpers\Mailer;
use App\Jobs\SendReminderEmail;
use Illuminate\Support\Facades\DB;
use Session;
class PaymentsController extends Controller
{

    public function payVideo (Request $request) {

        Stripe::setApiKey( config( 'services.stripe.secret' ) ); //auth

        //$balance = Balance::retrieve();
        //$account = Account::retrieve();
        //$token = Token::create();
        //dump($balance/*->available*/ );

        $requestData = $request->only('stripeToken', 'v_id'); // get request param
        $video = Video::select('id','user_id', 'coach_id', 'video_price', 'pay_status')
            ->where('id', (int)$requestData['v_id'])->first();

        //try charge
        try{
            $charge = Charge::create(array(
                'currency' => 'USD',
                'source' => $requestData['stripeToken'],
                'amount' => $video->video_price*100,
                'description' => "Charge main balance",
            ));
        } catch (\Exception $e) {
            return redirect()->back()->with('error_payment',true);
        }

       // dump( $charge['source']['brand']);
      //  exit;

        if($charge['status'] == 'succeeded'){ // success
            //create payment in database
            $new_pay = new PaymentsList();
            $new_pay->user_id = $video->user_id;
            $new_pay->coach_id = $video->coach_id;
            $new_pay->video_id = $video->id;
            $new_pay->amount = (int)$charge['amount']/100;
            $new_pay->stripe_id = (string)$charge['id'];
            $new_pay->save();

            //change video status
            $video->pay_status = 1;
            $video->update();

                $user = User::select('first_name', 'last_name', 'email')->where('id', $video->user_id)->first();
                //not
                $nt_data = [];
                $nt_data["user_id"] =  $video->coach_id;
                $nt_data["sender_id"] = $video->user_id;
                $nt_data["video_id"] = $video->id;
                $nt_data["status"] = 1;
                $nt_data["message"] = '<a href="/profile/'. $video->user_id . '">' . $user->first_name . ' ' . $user->last_name
                    . '</a> paid video!';
                $nt_data["created_at"] = mysql_date();
                $nt_data["updated_at"] = mysql_date();
                //Notification::saveNotification($nt_data);
                //not

            $coach = User::select('first_name','email')->where('id', $video->coach_id)->first();
            $mail_to_performer = new Mailer();
            $mail_to_performer->subject = 'Thank you for your payment';
            $mail_to_performer->to_email = ($request->stripeEmail == $user->email) ? $user->email : $request->stripeEmail;
            $mail_to_performer->sendMail('auth.emails.paymentReceived',
                [
                    'first_name'=>$user->first_name,
                    'date'=>date('F d, Y h:i a'),
                    'amount'=>(int)$charge['amount']/100,
                    'coach_name'=>$coach->first_name,
                    'card_ending'=>$charge['source']['exp_month'] . '/' . $charge['source']['exp_year'],
                    'card_type'=>$charge['source']['brand'],
                    'card_last4'=>$charge['source']['last4']
                ]);

            $mail_to_coach = new Mailer();
            $mail_to_coach->subject = 'Payment from the user';
            $mail_to_coach->to_email = $coach->email;
            $mail_to_coach->sendMail('auth.emails.paymentFromUser',
                [
                    'user_name'=>$user->first_name,
                    'coach_name'=>$coach->first_name
                ]);

            /* reminder functionality */
            $params = [
                'video_id'=>$video->id,
                'performer_name'=>$user->first_name,
                'coach_name'=>$coach->first_name,
                'coach_email'=>$coach->email,
                'date'=>mysql_date(),
                'days_left'=>3
            ];

            $job_3_days = (new SendReminderEmail($params))->delay(4*24*60*60);

            $params['days_left'] = 2;
            $job_2_days = (new SendReminderEmail($params))->delay(5*24*60*60);

            $params['days_left'] = 1;
            $job_1_days = (new SendReminderEmail($params))->delay(6*24*60*60);

            dispatch($job_3_days);
            dispatch($job_2_days);
            dispatch($job_1_days);

            //return redirect()->back()->with('success_payment',true);
            Session::flash('success', 'Payment successful!');
            return back();
        } else {
            //return redirect()->back()->with('error_payment',true);
            Session::flash('error', 'Failed please try again');
            return back();
        }
    }

    public function stripeConnectCallback(Request $request)
    {
        if (isset($_GET['code'])) { // Redirect w/ code
            $code = $_GET['code'];

            $token_request_body = array(
                'grant_type' => 'authorization_code',
                'client_id' => env('STRIPE_CLIENT_ID'),
                'code' => $code,
                'client_secret' => env('STRIPE_SECRET_KEY')
            );

            $req = curl_init('https://connect.stripe.com/oauth/token');
            curl_setopt($req, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($req, CURLOPT_POST, true);
            curl_setopt($req, CURLOPT_POSTFIELDS, http_build_query($token_request_body));

            $respCode = curl_getinfo($req, CURLINFO_HTTP_CODE);
            $resp = json_decode(curl_exec($req), true);
            curl_close($req);
            //if user has connect
            if( StripeConnection::where('user_id',Auth::user()->id)->exists() ){ 
                if (Auth::user()->role == User::COACH_ROLE) {
                    return redirect( url('/').'/profile/account_settings' )->with('error_connection',true);
                }else{
                    return redirect( url('/').'/profile/agency/account_settings' )->with('error_connection',true);
                }
            }
            //create coach connection in base
            if(isset($resp['stripe_user_id'])){
                $stripe_connection = new StripeConnection();
                $stripe_connection->user_id = Auth::user()->id;
                $stripe_connection->access_token = $resp['access_token'];
                $stripe_connection->refresh_token = $resp['refresh_token'];
                $stripe_connection->token_type = $resp['token_type'];
                $stripe_connection->stripe_publishable_key = $resp['stripe_publishable_key'];
                $stripe_connection->stripe_user_id = $resp['stripe_user_id'];
                $stripe_connection->scope = $resp['scope'];
                $stripe_connection->save();
                if (Auth::user()->role == User::COACH_ROLE) {
                    return redirect( url('/').'/profile/account_settings' )->with('success_connection',true);
                }else{
                    return redirect( url('/').'/profile/agency/account_settings' )->with('success_connection',true);
                }
                //return redirect( url('/').'/profile/account_settings' )->with('success_connection',true);
            } else {
                if (Auth::user()->role == User::COACH_ROLE) {
                    return redirect( url('/').'/profile/account_settings' )->with('error_connection',true);
                }else{
                    return redirect( url('/').'/profile/agency/account_settings' )->with('error_connection',true);
                }
                //return redirect( url('/').'/profile/account_settings' )->with('error_connection',true);
            }
            
        }
    }

    public static function transfer($video_id, $participation_type = Video::PARTICIPATION_TYPE , $LoggedInUserId = null)
    {
       if(is_null($LoggedInUserId)){
         $LoggedInUserId = auth()->user()->id;
       }
       if($participation_type == ChallengesParticipant::PARTICIPATION_TYPE){
         $participant = ChallengesParticipant::with('challenge')->find($video_id);
         $reviewCommission = Setting::getReviewCommission(ChallengesParticipant::PARTICIPATION_TYPE);
         // \Log::info('reviewCommission: '.$reviewCommission);
         $amount = $participant->challenge->challenges_fee*(100 - $reviewCommission)/100;
         $user_id = $participant->user_id;
       }elseif($participation_type == AuditionList::PARTICIPATION_TYPE){
         $participant = AuditionList::with('audition')->find($video_id);
         $reviewCommission = Setting::getReviewCommission(AuditionList::PARTICIPATION_TYPE);
         $amount = $participant->audition->audition_fee*(100 - $reviewCommission)/100;
         // \Log::info('amount: '.$amount);
         $user_id = $participant->user_id;
       }else{
         $video= Video::find($video_id);
         $reviewCommission = Setting::getReviewCommission(Video::PARTICIPATION_TYPE);
         $amount = $video->video_price*(100 - $reviewCommission)/100;
         $user_id = $video->user_id;
      }
        $destination =  StripeConnection::where('user_id',$LoggedInUserId)->first()->stripe_user_id;

        //get CHARGE_ID
        $stripe_id = false;
        if( PaymentsList::select('stripe_id')->where('video_id', $video_id)->where('participation_type', $participation_type)->exists() ){
            $stripe_id = PaymentsList::select('stripe_id')->where('video_id', $video_id)
                ->first()->stripe_id;
        }
        
        Stripe::setApiKey( config( 'services.stripe.secret' ) );

        //Create trans
        $trasaction = new Transaction();
        $trasaction -> video_id = (int)$video_id;
        $trasaction -> coach_id = (int)$LoggedInUserId;
        $trasaction -> user_id = $user_id;
        $trasaction -> participation_type = $participation_type;
        $trasaction -> amount = $amount;

        $trasaction_log = new TransactionLog();

        try {
            if($stripe_id){
                $transfer = Transfer::create([
                    'amount' => (int)$amount*100,
                    'currency' => 'USD',
                    'destination' => $destination,
                    'source_transaction' => $stripe_id,
                    "description" => "Transfer"
                ]);
            } else {
                $transfer = Transfer::create([
                    'amount' => (int)$amount*100,
                    'currency' => 'USD',
                    'destination' => $destination,
                    "description" => "Transfer"
                ]);
            }

        } catch (\Exception $e) {

            // write error trasaction status
            $trasaction -> status = 'catch error';
            //save Transaction
            $trasaction -> save();

            $trasaction_log -> transaction_id = $trasaction -> id;
            $trasaction_log -> transaction_body =  $e->httpBody;
            //save Log
            $trasaction_log -> save();

            return false;

        }

        // write in Transaction
        $trasaction -> stripe_transaction_id = $transfer -> id;
        $trasaction -> stripe_balance_transaction = $transfer -> balance_transaction;
        $trasaction -> stripe_destination = $transfer -> destination;
        $trasaction -> stripe_destination_payment = $transfer -> destination_payment;
        $trasaction -> status = $transfer->status ? $transfer->status : 'paid';
        $trasaction -> save();
        // write in Transaction Log
        $trasaction_log -> transaction_id = $trasaction -> id;
        $trasaction_log -> transaction_body =  json_encode($transfer);
        $trasaction_log -> save();

        return true;

    }

    public function checkStripeConnect(){
        $user = User::where('id', Auth::user()->id)->first();
        $stripe_connect = (isset($user->stripe_connection->id))? 1 : 0 ;
        return response()->json(['stripe_connect'=>$stripe_connect]);
    }


    public function deauthorizedCallback(Request $request){

        //resp =
        /*"created": 1326853478,
          "livemode": false,
          "id": "evt_00000000000000",
          "type": "account.application.deauthorized",
          "object": "event",
          "request": null,
          "pending_webhooks": 1,
          "api_version": "2016-07-06",
          "user_id": "acct_00000000000000",
          "data": {
                    "object": {
                    "id": "ca_00000000000000",
              "object": "application",
              "name": "showcase-hub.dev"
            }
          }*/

        $data = $request->all();
        if( !empty($data['user_id']) and $data['type'] == 'account.application.deauthorized' ){
            if( StripeConnection::where('stripe_user_id', $data['user_id'])->exists() ){
                StripeConnection::where('stripe_user_id', $data['user_id'])->delete();
                $trasaction_log = new TransactionLog();
                $trasaction_log -> transaction_id = 0;
                $trasaction_log -> transaction_body =  json_encode($request->all()) .  json_encode( ['connection_status'=>'User find end deleted!']) ;
                $trasaction_log -> save();
                exit;
            }
        }
        $trasaction_log = new TransactionLog();
        $trasaction_log -> transaction_id = 0;
        $trasaction_log -> transaction_body =  json_encode($request->all()) .  json_encode( ['connection_status'=>'User not find, ERROR!']) ;
        $trasaction_log -> save();

        return 'ok';

    }

    public function refund(Request $request)
    {
        $requestData = $request->only('video_id'); // get request param
        $paymentsList = PaymentsList::where('video_id',$requestData['video_id'])->firstOrFail();

        Stripe::setApiKey( config( 'services.stripe.secret' ) ); //auth

        try {
            $refund = Refund::create([
                'charge' => $paymentsList->stripe_id,
                'amount' => $paymentsList->amount,
            ]);

            //Create trans
            $transaction = new Transaction();
            $transaction->video_id = $paymentsList->video_id;
            $transaction->coach_id = $paymentsList->coach_id;
            $transaction->user_id = $paymentsList->user_id;
            $transaction->amount = $paymentsList->amount;
            $transaction->stripe_transaction_id = $refund->id;
            $transaction->stripe_balance_transaction = $refund->balance_transaction;
            $transaction->stripe_destination = 'refund to user';//$refund->destination;
            $transaction->stripe_destination_payment = 'refund to user';//$refund->destination_payment;
            $transaction->status = $refund->status;
            $transaction->save();

            $transaction_log = new TransactionLog();
            $transaction_log->transaction_id = 0;
            $transaction_log->transaction_body = json_encode($refund);
            $transaction_log->save();

            $result = Video::setStatusRefund($paymentsList->video_id, $paymentsList->coach_id);

            if ($result == true) {
                return json_encode(['success' => $result]);
            } else {
                return json_encode(['error' => $result]);
            }
        } catch (\Exception $e) {
            return json_encode(['error' => $e->getMessage()]);
        }
    }

    public function payment(Request $request){
        $video_id = (int)request()->route('video_id');
        if($video_id ==''){
            return redirect('video');
        }
        $coach_id = DB::table('videos')->select('coach_id','video_price','package_id')->where('id','=',$video_id)->get();
        $package_id = 0;
        if(count($coach_id) > 0 && $coach_id[0]->coach_id !=0){
            $user_coach = User::find($coach_id[0]->coach_id);
            $package_id = $coach_id[0]->package_id;
        }else{
            return redirect( url('/').'/select-coache/'.$video_id );
        }
        $user = new User();
        
        $performance_level = $user->coach_performance_level($coach_id[0]->coach_id);
        $genras = $user->coach_genres($coach_id[0]->coach_id);

        return view('video.payment',['coach'=>$user_coach,'video_price'=>$coach_id[0]->video_price,'video_id'=>$video_id,'package_id'=>$package_id,'levels'=>$performance_level,'genras'=>$genras]);
    }
}
