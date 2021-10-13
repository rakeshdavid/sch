<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Session;
use Stripe;

class StripePaymentController extends Controller
{
     public function stripe()
    {
        return view('textpayment');
    }
  
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function stripePost(Request $request)
    {
        Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
        $charge = Stripe\Charge::create ([
                "amount" => 100,
                "currency" => "usd",
                "source" => $request->stripeToken,
                "description" => "Test payment from showcase.local" 
        ]);
        echo "<pre>";
  		print_r($charge);
  		echo "</pre>";
  		// echo "<pre>";
  		// print_r(json_decode($charge));
  		// echo "</pre>";
  		echo "===================";
  		echo $charge->status;
  		echo "===================";
  		echo $charge->id;
        //Session::flash('success', 'Payment successful!');
          
        //return back();
    }
}
