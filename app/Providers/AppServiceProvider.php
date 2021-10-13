<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \Validator::extend('phone', function($attribute, $value, $parameters, $validator) {
            return preg_match('%^(?:(?:\(?(?:00|\+)([1-4]\d\d|[1-9]\d?)\)?)?[\-\.\ \\\/]?)?((?:\(?\d{1,}\)?[\-\.\
             \\\/]?){0,})(?:[\-\.\ \\\/]?(?:#|ext\.?|extension|x)[\-\.\ \\\/]?(\d+))?$%i', $value) && strlen($value) >= 10;
        });
        \Validator::replacer('phone', function($message, $attribute, $rule, $parameters) {
            return str_replace(':attribute',$attribute, $message);
        });

        \Validator::extend('old_password', function($attribute, $value, $parameters, $validator) {
            $user = auth()->user();
            if(is_null($user)) {
                return false;
            }
            return \Hash::check($value, $user->password);
        });
        \Validator::replacer('old_password', function($message, $attribute, $rule, $parameters) {
            if($message) {
                return str_replace(':attribute',$attribute, $message);
            }
            return str_replace(':attribute',$attribute, ':attribute is invalid');
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
