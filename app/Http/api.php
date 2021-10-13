<?php

$api = app(\Dingo\Api\Routing\Router::class);

$api->version('v1', function ($api) {
    $api->post('/login-user', 'App\Api\Controllers\Auth\AuthController@loginUser');
    $api->post('/register-user', 'App\Api\Controllers\Auth\AuthController@registerUser');
    $api->post('/password-user', 'App\Api\Controllers\Auth\AuthController@resetPasswordUser');
    $api->post('/facebook-login', 'App\Api\Controllers\Auth\AuthController@facebookLogin');

    $api->group(['prefix' => '/profile', 'middleware' => 'api.auth'], function ($api) {
        $api->get('/', 'App\Api\Controllers\ProfileController@index');
        $api->patch('/', 'App\Api\Controllers\ProfileController@update');
        $api->post('/password', 'App\Api\Controllers\ProfileController@password');
        $api->get('/activity-genres', 'App\Api\Controllers\ProfileController@activityGenres');
    });

    $api->group(['prefix' => '/videos', 'middleware' => 'api.auth'], function ($api) {
        $api->get('/', 'App\Api\Controllers\VideoController@index');
        $api->get('/sample-review', 'App\Api\Controllers\VideoController@sampleReview');
        $api->get('/{video}/review', 'App\Api\Controllers\VideoController@review');
        $api->post('/store', 'App\Api\Controllers\VideoController@store');
        $api->post('/store/video-file', 'App\Api\Controllers\VideoController@storeVideoFile');
        $api->get('/all-review', 'App\Api\Controllers\VideoController@myreview');
        $api->post('/update-video-coach', 'App\Api\Controllers\VideoController@updateVideoCoach');
        $api->post('/review-question', 'App\Api\Controllers\VideoController@postReviewQuestion');
        $api->post('/ask-question', 'App\Api\Controllers\VideoController@askQuestion');
    });
 
    $api->group(['prefix' => '/coaches', 'middleware' => 'api.auth'], function ($api) {
        $api->get('/', 'App\Api\Controllers\CoachesController@index');
        $api->get('/{coach}', 'App\Api\Controllers\CoachesController@coach');
    });

    $api->group(['middleware' => 'api.auth'], function ($api) {
        $api->get('/levels', 'App\Api\Controllers\DataController@levels');
        $api->get('/genres', 'App\Api\Controllers\DataController@genres');
        $api->get('/activity-types', 'App\Api\Controllers\DataController@activityTypes');
    });

    $api->group(['middleware' => 'api.auth'], function ($api) {
        $api->post('/pay-video', 'App\Api\Controllers\PaymentController@payVideo');
        $api->post('/apple-pay-video', 'App\Api\Controllers\PaymentController@applePayVideo');
        $api->get('/get-stripe-key', 'App\Api\Controllers\PaymentController@getStripePublicKey');
        $api->get('/get-stripe-ephemeral-key', 'App\Api\Controllers\PaymentController@getStripeEphemeralKey');
        
    });



    $api->group(['prefix' => '/auditions', 'middleware' => 'api.auth'], function ($api) {
        $api->get('/', 'App\Api\Controllers\AuditionController@index');
        $api->get('/{audition_id}/participants', 'App\Api\Controllers\AuditionController@review');
        $api->get('/{audition_id}', 'App\Api\Controllers\AuditionController@auditionDetail');
        $api->post('/participate', 'App\Api\Controllers\AuditionController@store');
        $api->post('/payforaudition', 'App\Api\Controllers\AuditionController@payAudition');
        $api->post('/apple-payforaudition', 'App\Api\Controllers\AuditionController@applePayAudition');
    });

    //For challenges

    $api->group(['prefix' => '/challenges', 'middleware' => 'api.auth'], function ($api) {
        $api->get('/', 'App\Api\Controllers\ChallengeController@index');
        $api->get('/{challenge_id}/review', 'App\Api\Controllers\ChallengeController@review');
        $api->get('/{challenge_id}', 'App\Api\Controllers\ChallengeController@challengeDetail');
        $api->post('/participate', 'App\Api\Controllers\ChallengeController@store');
        $api->post('/payforchallenge', 'App\Api\Controllers\ChallengeController@payChallenge');
        $api->post('/apple-payforchallenge', 'App\Api\Controllers\ChallengeController@applePayChallenge');
    });
});
 