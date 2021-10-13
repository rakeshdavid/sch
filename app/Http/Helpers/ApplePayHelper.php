<?php

namespace App\Http\Helpers;

class ApplePayHelper
{
    static function priceID($price)
    {
    	if(!$price){
    		return 'priceisnotset';
    	}
        $newPrice = array(
            '60'=>59.99,
            '65'=>64.99,
            '95'=>94.99,
            '75'=>74.99,
            '125'=>124.99,
            '100'=>99.99,
            '30'=>29.99,
            '40'=>39.99,
            '25'=>24.99,
            '50'=>49.99,
            '45'=>44.99,
            '55'=>54.99,
            '80'=>79.99,
            '120'=>119.99,
            '85'=>84.99,
            '130'=>129.99,
            '90'=>89.99,
            '160'=>159.99,
            '150'=>149.99,
            '200'=>199.99,
            '175'=>174.55,
            '225'=>224.99,
            '250'=>249.99,
            '249'=>249.99,
            '349'=>349.99,
            '1'=>0.99,
            '0'=>0,
            '2'=>1.99
        );
        $priceName = array(
            '60'=>'com.showcasehubdance.fiftynine',
            '65'=>'com.showcasehubdance.sixtyfour',
            '95'=>'com.showcasehubdance.ninetyfour',
            '75'=>'com.showcasehubdance.seventyfour',
            '125'=>'com.showcasehubdance.onetwentyfour',
            '100'=>'com.showcasehubdance.ninetynine',
            '30'=>'com.showcasehubdance.twentynine',
            '40'=>'com.showcasehubdance.thirtynine',
            '25'=>'com.showcasehubdance.twentyfour',
            '50'=>'com.showcasehubdance.fourtynine',
            '45'=>'com.showcasehubdance.fourtyfour',
            '55'=>'com.showcasehubdance.fiftyfour',
            '80'=>'com.showcasehubdance.seventynine',
            '120'=>'com.showcasehubdance.onenineteen',
            '85'=>'com.showcasehubdance.eightyfour',
            '130'=>'com.showcasehubdance.onetwentynine',
            '90'=>'com.showcasehubdance.eightynine',
            '160'=>'com.showcasehubdance.onefiftynine',
            '150'=>'com.showcasehubdance.onefourtynine',
            '200'=>'com.showcasehubdance.oneninetynine',
            '175'=>'com.showcasehubdance.oneseventyfour',
            '225'=>'com.showcasehubdance.twotwentyfour',
            '250'=>'com.showcasehubdance.twofourtynine',
            '249'=>'com.showcasehubdance.twofourtynine',
            '349'=>'com.showcasehubdance.threefourtynine',
            '1'=>'com.showcasehubdance.one',
            '0'=>'com.showcasehubdance.zero',
            '2'=>'com.showcasehubdance.two'
        );
        if(array_key_exists($price,$newPrice)){
        	return $priceName[$price];
        }else{
        	return 'pricenotsetinlist';
        }
    }
    static function newPrice($price)
    {
    	if(!$price){
    		return 0;
    	}
        $newPrice = array(
            '60'=>59.99,
            '65'=>64.99,
            '95'=>94.99,
            '75'=>74.99,
            '125'=>124.99,
            '100'=>99.99,
            '30'=>29.99,
            '40'=>39.99,
            '25'=>24.99,
            '50'=>49.99,
            '45'=>44.99,
            '55'=>54.99,
            '80'=>79.99,
            '120'=>119.99,
            '85'=>84.99,
            '130'=>129.99,
            '90'=>89.99,
            '160'=>159.99,
            '150'=>149.99,
            '200'=>199.99,
            '175'=>174.55,
            '225'=>224.99,
            '250'=>249.99,
            '249'=>249.99,
            '349'=>349.99,
            '1'=>0.99,
            '0'=>0,
            '2'=>1.99
        );
       
        if(array_key_exists($price,$newPrice)){
        	return $newPrice[$price];
        }else{
        	return $price;
        }
    }
}