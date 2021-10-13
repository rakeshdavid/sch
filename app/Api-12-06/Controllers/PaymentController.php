<?php

namespace App\Api\Controllers;

use App\Api\Requests\PayVideoRequest;
use Stripe\Stripe;
use App\Jobs\SendReminderEmail;
use App\Models\Notification;
use App\Models\Video;
use Stripe\Charge;
use App\Http\Helpers\Mailer;
use App\Models\User;
use App\Models\PaymentsList;
use Dingo\Api\Http\Request;
use Dingo\Api\Exception\StoreResourceFailedException;
use Stripe\EphemeralKey;
use Stripe\Customer;

/**
 * Payment data representation. Requires Authorization header.
 *
 * @Resource("Payment")
 */
class PaymentController extends BaseController
{
    /**
     * Get Stripe Public Key
     *
     * @Get("/get-stripe-key")
     * @Transaction({
     *     @Request(headers={"Authorization": "Bearer <JWT>"}),
     *     @Response(200, body={"data":{"stripe_key" : "pk_test_59qE2pNVJ9MlBAfu1733L3Bu"}, "status_code": 200}),
     *     @Response(401, body={"message": "422 Unprocessable Entity.",
     *         "status_code": 401}),
     *     @Response(422, body={"message": "422 Unprocessable Entity", "status_code": 422, "errors":{"stripe public key":
     *         {".env STRIPE_PUBLISHABLE_KEY not found"}}}),
     *     @Response(500, body={"error": "Server side error message", "status_code": 500})
     * })
     * @Versions({"v1"})
     */
    public function getStripePublicKey()
    {
        $stripe_key = env('STRIPE_PUBLISHABLE_KEY', false);
        if (!$stripe_key) {
            return $this->response->array([
                'message' => "422 Unprocessable Entity",
                'errors' => ['stripe public key' => ['.env STRIPE_PUBLISHABLE_KEY not found']],
                'status_code' => 422,
            ])->setStatusCode(422);
        } else {

            return response()->json(['data' => compact('stripe_key'), 'status_code' => 200]);
        }
    }

    /**
     * Store payment
     *
     * Update video status.
     *
     * @param PayVideoRequest $request
     *
     * @Post("/pay-video")
     * @Transaction({
     *     @Request(body={"source": "tok_amex", "video_id" : 72, "customer":"cus_DEvPAHtPC2PRMR", "need_customer_id":false}
     *     , headers={"Authorization": "Bearer <JWT>"}),
     *     @Response(200, body={"data": { "message": "Success pay!" }, "status_code": 200}),
     *     @Response(401, body={"message": "Failed to authenticate because of bad credentials or an invalid authorization header.",
     *         "status_code": 401}),
     *     @Response(422, body={"message": "Error pay", "status_code": 422}),
     *     @Response(500, body={"error":"Server side error message", "status_code": 500})
     * })
     * @Versions({"v1"})
     */
    public function payVideo(PayVideoRequest $request)
    {
        Stripe::setApiKey(config('services.stripe.secret')); //auth
        $video = Video::select('id', 'user_id', 'coach_id', 'video_price', 'pay_status')
            ->where('id', (int)$request->video_id)->first();
        $charge_array = [
            'currency' => 'USD',
            'amount' => $video->video_price,
            'description' => "Charge main balance, paid video " . $video->id,
        ];
        if($request->source) $charge_array['source'] = $request->source;
        if($request->need_customer_id){
            if(auth()->user()->stripe_customer_id){
                $stripe_customer_id = self::checkStripeCustomer();
                $charge_array['customer'] = $stripe_customer_id;
            }else{
                $stripe_customer = Customer::create([
                    'email' => auth()->user()->email,
                    'description' => 'User for stripe ephemeral key',
                ]);
                $charge_array['customer'] = $stripe_customer->id;
                User::whereId(auth()->user()->id)->update(['stripe_customer_id' => $stripe_customer->id]);
            }
        }else{
            if($request->customer) $charge_array['customer'] = $request->customer;
        }
        //try charge
        try {
            $charge = Charge::create($charge_array);
        } catch (\Exception $e) {
            return response()->json(["message" => "Error pay", 'status_code' => 422]);
        }

        if ($charge['status'] == 'succeeded') { // success
            //create payment in database
            $new_pay = new PaymentsList();
            $new_pay->user_id = $video->user_id;
            $new_pay->coach_id = $video->coach_id;
            $new_pay->video_id = $video->id;
            $new_pay->amount = (int)$charge['amount'];
            $new_pay->stripe_id = (string)$charge['id'];
            $new_pay->save();

            //change video status
            $video->pay_status = 1;
            $video->update();

            $user = User::select('first_name', 'last_name', 'email')->where('id', $video->user_id)->first();
            //not
            $nt_data = [];
            $nt_data["user_id"] = $video->coach_id;
            $nt_data["sender_id"] = $video->user_id;
            $nt_data["video_id"] = $video->id;
            $nt_data["status"] = 1;
            $nt_data["message"] = '<a href="/profile/' . $video->user_id . '">' . $user->first_name . ' ' . $user->last_name
                . '</a> paid video!';
            $nt_data["created_at"] = mysql_date();
            $nt_data["updated_at"] = mysql_date();
            Notification::saveNotification($nt_data);
            //not

            $coach = User::select('first_name', 'email')->where('id', $video->coach_id)->first();
            $mail_to_performer = new Mailer();
            $mail_to_performer->subject = 'Thank you for your payment';
            $mail_to_performer->to_email = ($request->stripeEmail == $user->email) ? $user->email : $request->stripeEmail;
            $mail_to_performer->sendMail('auth.emails.paymentReceived',
                [
                    'first_name' => $user->first_name,
                    'date' => date('F d, Y h:i a'),
                    'amount' => (int)$charge['amount'] / 100,
                    'coach_name' => $coach->first_name,
                    'card_ending' => $charge['source']['exp_month'] . '/' . $charge['source']['exp_year'],
                    'card_type' => $charge['source']['brand'],
                    'card_last4' => $charge['source']['last4']
                ]);

            $mail_to_coach = new Mailer();
            $mail_to_coach->subject = 'Payment from the user';
            $mail_to_coach->to_email = $coach->email;
            $mail_to_coach->sendMail('auth.emails.paymentFromUser',
                [
                    'user_name' => $user->first_name,
                    'coach_name' => $coach->first_name
                ]);

            /* reminder functionality */
            $params = [
                'video_id' => $video->id,
                'performer_name' => $user->first_name,
                'coach_name' => $coach->first_name,
                'coach_email' => $coach->email,
                'date' => mysql_date(),
                'days_left' => 3
            ];

            $job_3_days = (new SendReminderEmail($params))->delay(4 * 24 * 60 * 60);

            $params['days_left'] = 2;
            $job_2_days = (new SendReminderEmail($params))->delay(5 * 24 * 60 * 60);

            $params['days_left'] = 1;
            $job_1_days = (new SendReminderEmail($params))->delay(6 * 24 * 60 * 60);

            dispatch($job_3_days);
            dispatch($job_2_days);
            dispatch($job_1_days);

            return response()->json(["message" => "Success pay!", 'status_code' => 200]);
        } else {
            return response()->json(["message" => "Error pay", 'status_code' => 422]);
        }
    }

    /**
     * Get Stripe Ephemeral Key
     *
     * @Get("/get-stripe-ephemeral-key")
     * * @Parameters({
     *      @Parameter("api_version", required=true, description="stripe api changelog date, example 2018-05-21")
     * })
     * @Transaction({
     *     @Request(headers={"Authorization": "Bearer <JWT>"}),
     *     @Response(200, body={"data": {"key": {"id": "ephkey_1CkSjoADFWrjvdJzots3UHTm","object": "ephemeral_key",
     * "associated_objects": {{"id": "cus_AsWX5ajtkcsbPB","type": "customer"}},"created": 1530778972,"expires": 1530782572,
     *     "livemode": false,"secret": "ek_test_YWNjdF8xQVdsZXcmp2ZEp6LFRZbVlNWU1NY1RrQnJkV3RWcmNGZDFyUEZ"}}, "status_code": 200}),
     *     @Response(401, body={"message": "422 Unprocessable Entity.",
     *         "status_code": 401}),
     *     @Response(422, body={"message": "422 Unprocessable Entity", "status_code": 422, "errors":{"api_version":
     *         {"Api version required!"}}}),
     *     @Response(500, body={"error": "Server side error message", "status_code": 500})
     * })
     * @Versions({"v1"})
     */
    public function getStripeEphemeralKey(Request $request)
    {
        if(!$request->api_version){
            throw new StoreResourceFailedException(
                'Request params error!',
                ['api_version' => ['Api version required!']]
            );
        }
        Stripe::setApiKey(config('services.stripe.secret'));
        if(auth()->user()->stripe_customer_id){
            $stripe_customer_id = self::checkStripeCustomer();
        }else{
            $stripe_customer = Customer::create([
                'email' => auth()->user()->email,
                'description' => 'User for stripe ephemeral key',
            ]);
            $stripe_customer_id = $stripe_customer->id;
            User::whereId(auth()->user()->id)->update(['stripe_customer_id' => $stripe_customer_id]);
        }

        $key = EphemeralKey::create(
            ["customer" => $stripe_customer_id],
            ["stripe_version" => $request->api_version] //'2018-05-21'
        );

            return response()->json(['data' => compact('key'), 'status_code' => 200]);
    }

    public function checkStripeCustomer()
    {
        try {
            $stripe_customer = Customer::retrieve(auth()->user()->stripe_customer_id);
        } catch (\Exception $e) {
            $stripe_customer = Customer::create([
                'email' => auth()->user()->email,
                'description' => 'User for stripe ephemeral key',
            ]);
            $stripe_customer_id = $stripe_customer->id;
            User::whereId(auth()->user()->id)->update(['stripe_customer_id' => $stripe_customer_id]);
        }

        if($stripe_customer->deleted){
            $stripe_customer = Customer::create([
                'email' => auth()->user()->email,
                'description' => 'User for stripe ephemeral key',
            ]);
            $stripe_customer_id = $stripe_customer->id;
            User::whereId(auth()->user()->id)->update(['stripe_customer_id' => $stripe_customer_id]);
        }else{
            $stripe_customer_id = $stripe_customer->id;
        }

        return $stripe_customer_id;
    }
}
