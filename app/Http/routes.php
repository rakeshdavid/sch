<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

require app_path('Http/api.php');

//all rout force https redirect
// Route::group(['middlewareGroups' => 'web', 'middleware' => 'ForceSSL'], function () {

    Route::group(['middleware' => 'system'], function () {

        // All routes for user
        Route::group(['middleware' => ['auth', 'user']], function () {

        });

        // Coach subdomain
        Route::group(['domain' => env('COACH_PLATFORM_DOMAIN')], function () {
            Route::get('/coach_register/{token}', 'Auth\AuthController@showCoachRegistrationForm')->name('coach.register.form');
            Route::post('/coach_register', 'Auth\AuthController@registerCoach')->name('coach.register');
        });
        //
        // All routes for admin
        Route::group(['middleware' => ['auth', 'admin']], function () {
            Route::post('change-hidden-user', 'Admin\ActionController@changeHiddenUser')->name('admin.changeHiddenUser');
            Route::get('login-as/{id}', 'Admin\ActionController@loginAs')->name('admin.loginAs');
            Route::post('change-test-user', 'Admin\ActionController@changeTestUser')->name('admin.changeTestUser');
            Route::group(['prefix' => '/coaches'], function () {
                Route::get('/list', 'Admin\CoachController@index')->name('admin.coaches.index');
                Route::any('/data', 'Admin\CoachController@coachesData')->name('admin.coaches.data');
                Route::get('/create', 'Admin\CoachController@createCoach')->name('admin.coaches.create');
                Route::get('/invites', 'Admin\CoachController@invites')->name('admin.coaches.invites');
                Route::any('/invites-data', 'Admin\CoachController@invitesData')->name('admin.coaches.invitesData');
                Route::post('/create-invite', 'Admin\CoachController@createInvite')->name('admin.coaches.createInvite');
                Route::post('/delete-invite', 'Admin\CoachController@deleteInvite')->name('admin.coaches.deleteInvite');
                Route::post('/store', 'Admin\CoachController@storeCoach')->name('admin.coaches.store');
                Route::get('/edit/{id}', 'Admin\CoachController@editCoach')->name('admin.coaches.edit');
                Route::post('/update/{id}', 'Admin\CoachController@updateCoach')->name('admin.coaches.update');
                Route::post('/delete/{id}', 'Admin\CoachController@delete')->name('admin.coaches.delete');

            });
            Route::group(['prefix' => '/users'], function () {
                Route::get('/list', 'Admin\UsersController@index')->name('admin.users.index');
                Route::any('/data', 'Admin\UsersController@usersData')->name('admin.users.data');
                Route::get('/create', 'Admin\UsersController@createUser')->name('admin.users.create');
                Route::get('/invites', 'Admin\UsersController@invites')->name('admin.users.invites');
                Route::any('/invites-data', 'Admin\UsersController@invitesData')->name('admin.users.invitesData');
                Route::post('/create-invite', 'Admin\UsersController@createInvite')->name('admin.users.createInvite');
                Route::post('/delete-invite', 'Admin\UsersController@deleteInvite')->name('admin.users.deleteInvite');
                Route::post('/store', 'Admin\UsersController@storeUser')->name('admin.users.store');
                Route::get('/edit/{id}', 'Admin\UsersController@editUser')->name('admin.users.edit');
                Route::post('/update/{id}', 'Admin\UsersController@updateUser')->name('admin.users.update');
                Route::delete('/delete/{id}', 'Admin\UsersController@delete')->name('admin.users.delete');
                Route::post('/delete/{id}', 'Admin\UsersController@delete')->name('admin.users.delete');
            });
            Route::group(['prefix' => '/analytics'], function () {
                Route::get('/', 'Admin\AnalyticsController@index')->name('admin.analytics.index');
                Route::get('/active-coaches', 'Admin\AnalyticsController@showActiveCoaches')->name('admin.analytics.active-coaches');
                Route::get('/active-coaches-table', 'Admin\AnalyticsController@showActiveCoachesTable')->name('admin.analytics.active-coaches-table');
                Route::get('/active-users', 'Admin\AnalyticsController@showActiveUsers')->name('admin.analytics.active-users');
                Route::get('/active-users-table', 'Admin\AnalyticsController@showActiveUsersTable')->name('admin.analytics.active-users-table');
                Route::get('/code-red', 'Admin\AnalyticsController@codeRed')->name('admin.analytics.code-red');
                Route::get('/code-red/stats', 'Admin\AnalyticsController@usersCodeRed')->name('admin.analytics.code-red-stats');
                Route::get('/code-red/remedy-coaches', 'Admin\AnalyticsController@showNotActiveCoaches')->name('admin.analytics.remedy  -coaches');
                Route::post('/code-red/refund', 'PaymentsController@refund')->name('admin.analytics.refund');
                Route::post('/code-red/remedy-coaches', 'Admin\AnalyticsController@remedyCoaches')->name('admin.analytics.remedy-coaches');
            });
            Route::group(['prefix' => '/settings'], function () {
                Route::get('/', 'Admin\SettingsController@index')->name('admin.settings.index');
                Route::post('/store', 'Admin\SettingsController@store')->name('admin.settings.store');
            });
            Route::get('tax-rate/','Admin\TaxRateController@index')->name('admin.taxrate.index');
            Route::post('tax-rate/','Admin\TaxRateController@store')->name('admin.taxrate.store');
            Route::get('scripts/process-missed-payments','Admin\ScriptController@index')->name('admin.scripts.process_missed_payments');

        });

        Route::get('/login', ['as' => 'login', 'uses' => 'IndexController@index']);

        Route::get('/', [
            'as' => 'index',
            'uses' => 'IndexController@index2',
        ]);

        Route::get('myreviews', [
            'as' => 'search',
            'middleware' => 'auth',
            'uses' => 'IndexController@search',
        ]);

        Route::get('auth/logout', 'Auth\AuthController@logout');

        Route::get('auth/facebook', 'Auth\AuthController@redirectToProvider');
        Route::get('auth/facebook/callback', 'Auth\AuthController@handleProviderCallback');

        Route::get('proposal/create/{id}', [
            'middleware' => 'auth',
            'uses' => 'ProposalController@create',
        ]);

        Route::get('review/create/{id}', ['middleware' => 'auth', 'uses' => 'ReviewController@create']);
        Route::post('review/audition-rating',
            ['middleware' => 'auth', 'uses' => 'ReviewController@saveAuditionRatings']
        );
        Route::get('review/create/second_step/{video_id}',
            ['middleware' => 'auth', 'uses' => 'ReviewController@createSecondStep']
        )->name('review.create-second-step');
        Route::get('review/rewrite/{video_id}',
            ['middleware' => 'auth', 'uses' => 'TemporaryReviewController@rewriteReview']
        )->name('review.rewrite');

        Route::post('/profile/{id}', [
            'middleware' => 'auth',
            'uses' => 'ProfileController@update',
        ]);
 
        Route::get('/profile/set-role/{role}', [
            'middleware' => 'auth',
            'uses' => 'ProfileController@setRole',
        ]);

        Route::post('/proposal/change-status', [
            'middleware' => 'auth',
            'uses' => 'ProposalController@changeStatus',
        ]);

        Route::post('/review/save-ratings', [
            'middleware' => 'auth',
            'uses' => 'ReviewController@saveRatings',
        ]);

        Route::post('/video/save-file', 'VideoController@saveFile');
        Route::post('/video/fallback', 'VideoController@fallbackStatus');
        Route::post('/video/fallback/video-file', 'VideoController@fallbackVideoFile')->name('review.fallback-videofile');

        Route::get('/review/show-my/{id}', [
            'middleware' => 'auth',
            'uses' => 'ReviewController@showMy',
        ]);
        Route::post('/review/store-audio', 'ReviewController@storeAudioFile');
        Route::get('/review/temp/check-progress/{video_id}', 'TemporaryReviewController@checkConcatVideoProgress')->name('temp_review.check-progress');

        Route::get('/profile/get-notifications', [
            'middleware' => 'auth',
            'uses' => 'ProfileController@getNotifications',
        ]);

        Route::get('/profile/set-notifications', [
            'middleware' => 'auth',
            'uses' => 'ProfileController@setNotifications',
        ]);
        Route::post('/profile/gallery/save-file', 'ProfileController@savePhoto')->name('profile.gallery.save-file');
        Route::get('/profile/account_settings', 'ProfileController@account_settings');
        Route::post('/profile/account_settings/new_password', 'ProfileController@new_password');

        Route::resource('video', 'VideoController');
        Route::group(['middleware' => 'auth'], function(){
            Route::resource('profile', 'ProfileController');
        });
        Route::resource('proposal', 'ProposalController');
        Route::resource('review', 'ReviewController');
        Route::get('tempupload','VideoController@create');
        Route::post('/payments/add/{video}/{user}', 'PaymentsController@userVideoPay');

        Route::group(['namespace' => 'Customer', 'prefix' => 'customer', 'as' => 'customerActions-', 'middleware' => 'auth',
            'before' => 'auth'],
            function () {
                Route::group(['prefix' => 'coach'], function () {
                    Route::any('search', 'ActionsController@searchCoach')->name('searchCoach');
                    //Route::post( 'search', 'ActionsController@doSearchCoach' )->name( 'postSearchCoach' );
                    Route::get('list', 'ActionsController@showSearchCoachResults')->name('getSearchCoachResults');
                });
            }
        );

        Route::auth();

        Route::group(['prefix' => 'ajax', 'namespace' => 'Customer'], function () {
            Route::post('get-genres', 'ActionsController@getGenres');
            Route::post('genres', 'ActionsController@getGenresNew')->name('get-genres');
        });


        Route::post('checkStripeConnect', 'PaymentsController@checkStripeConnect', function () {
        });
        Route::post('pay-video', 'PaymentsController@payVideo', function () {
        });
        Route::any('stripeConnectCallback', 'PaymentsController@stripeConnectCallback', function () {
        });

        //change video status
        Route::post('approved-video', 'ReviewController@approvedVideo')->name('review.approvedVideo');
//     });
 });
//stripe webhook

Route::group(['prefix'=>'stripe','middleware' => 'web'], function () {
    Route::any('accountDeauthorizedCallback', 'PaymentsController@deauthorizedCallback', function () {});
});


Route::auth();
Route::get('/download-video', 'VideoController@download');
Route::post('/update-video-source', 'VideoController@updateVideoSource');
Route::get('/home', 'HomeController@index');
Route::get('/upload-new-video', 'UploadNewVideo@index');
Route::get('/video-error','UploadNewVideo@videoError');
Route::post('/upload', 'UploadNewVideo@uploadvideo');
Route::get('/upload-successfull/{video_id}', 'UploadNewVideo@uploadVideoSuccessfull');
Route::post('/uplaod-youtube','UploadNewVideo@uploadYoutubeVideo');
Route::post('update-title', 'UploadNewVideo@uploadVideoTitle');
Route::get('/select-coache/{video_id}/{coach_id?}','AllCoachList@allCoachList');
Route::post('/select-coache/{video_id}/{coach_id?}','AllCoachList@filterCoach');
Route::get('/payment/{video_id}','PaymentsController@payment');
Route::post('/update-coachof-video','AllCoachList@updateVideoaCoach');
Route::post('/coachdata-in-session','AllCoachList@saveCoachInSession');
Route::post('/filter-coach','AllCoachList@filterCoach');
Route::get('/coaches/{coach_id?}','AllCoachList@coachesList');
Route::post('/coaches/{coach_id?}','AllCoachList@filterCoachesList');
Route::group(['middleware' => 'auth'], function () {
    Route::get('/my-reviews','VideoReviewController@getAllReviewedVideo');
});
Route::get('change-password', function () {
    return view('profile/change-password');
}); 
Route::get('/auditions/{audition_id?}','Auditions\AuditionsController@index');
Route::post('/filter-auditions/{audition_id?}','Auditions\AuditionsController@filterAuditions');
Route::get('/filter-auditions/{audition_id?}','Auditions\AuditionsController@filterAuditions');
Route::get('/auditions/participation/{audition_id}','Auditions\AuditionsController@auditionParticipation');
Route::post('/auditions/participation/{audition_id}','Auditions\AuditionsController@auditionParticipation');

Route::get('/auditions/payment/{participant_id}','Auditions\AuditionsController@auditionPayment');
Route::post('/auditions/payment/{participant_id}','Auditions\AuditionsController@auditionPayment');
Route::post('/auditions/pay','Auditions\AuditionsController@stripePost')->name('auditoinpay.post');

Route::get('/challenges/{challenge_id?}','Challenges\ChallengesController@index');
Route::post('/challenges/{challenge_id?}','Challenges\ChallengesController@index');

Route::get('/challenge/participation/{challenge_id}','Challenges\ChallengesController@participation');
Route::post('/challenge/participation/{challenge_id}','Challenges\ChallengesController@participation');
Route::get('/challenge/pay/{participant_id}','Challenges\ChallengesController@payForChallenge');
Route::post('/challenge/pay/{participant_id}','Challenges\ChallengesController@payForChallenge');
Route::post('/challenge/pay','Challenges\ChallengesController@stripePost')->name('challengepay.post');
Route::get('stripe', 'StripePaymentController@stripe');
Route::post('stripe', 'StripePaymentController@stripePost')->name('stripe.post');

Route::post('/user-profile/{id}', [
            'middleware' => 'auth',
            'uses' => 'ProfileController@updateUserProfile',
        ]);
Route::get("thumbvideo",'UploadNewVideo@aasda');
Route::post("thumbvideo",'UploadNewVideo@testThumbnail');
Route::post("ask-question",'UploadNewVideo@askQuestionForReview');
Route::post('video', 'VideoController@index');
Route::get('ask-question/{video_id?}','UploadNewVideo@coachQuestions');
Route::post('ask-question-coach/','UploadNewVideo@askcoachQuestions');
// Route::get('upload-successfull/{id}', function ($id) {
//     return 'User '.$id;
// });

//Challenges Routes for coach
Route::get('my-challenges','Challenges\CoachChallenges@allChallenges');
Route::get('challenge-participant','Challenges\CoachChallenges@challengeParticipant');
Route::get('new-challenge','Challenges\CoachChallenges@newChallenge');
Route::post('new-challenge','Challenges\CoachChallenges@newChallenge');
Route::get('challenge-review/{participant_id}','Challenges\CoachChallenges@challengeReview');
Route::post('challenge-review/{participant_id}','Challenges\CoachChallenges@challengeReview');
Route::get('challenge-review-new/{participant_id}','Challenges\CoachChallenges@challengeReviewNew');
Route::post('challenge-review-new/{participant_id}','Challenges\CoachChallenges@challengeReviewNew');
Route::get('challenge-review-edit/{participant_id}','Challenges\CoachChallenges@challengeReviewUpdate');
Route::post('challenge-review-edit/{participant_id}','Challenges\CoachChallenges@challengeReviewUpdate');
Route::get('edit-challenge/{challenge_id}','Challenges\CoachChallenges@editChallenge');
Route::post('edit-challenge/{challenge_id}','Challenges\CoachChallenges@editChallenge');

//Admin controller

Route::resource('agency', 'Admin\AgencyController');
Route::get('admin/auditions','Admin\AgencyController@agencyAudition');
Route::get('agency/auditions/{agency_id}','Admin\AdminAudition@agencyAuditions');
Route::get('audition/participants/{audition_id}','Admin\AdminAudition@auditionParticpants');
Route::get('coach/challenges','Admin\CoachController@coachChallenges');
Route::get('coach/add-challenges','Admin\CoachController@newCoachChallenges');
Route::get('challenge/participants/{challenge_id}','Admin\CoachController@challengeParticiapnts');
Route::get('admin/agency-audition','Admin\AgencyController@auditionForAgency')->name('admin.agency-audition');
Route::post('admin/add-agency-audition','Admin\AgencyController@addAudition');
Route::get('audition/{audition_id}/edit','Admin\AgencyController@editAuditionForAgency');
Route::post('admin/edit-agency-audition','Admin\AgencyController@updateAudition');

Route::get('admin/add-challenges','Admin\CoachController@newCoachChallenges');
Route::post('admin/add-coach-challenge','Admin\CoachController@addCoachChallenege');
Route::get('admin/challenge/{challenge_id}/edit','Admin\CoachController@adminEditChallenge');
Route::post('admin/edit-coach-challenge','Admin\CoachController@editCoachChallenege');

//Agency Controller

//Agency Routes
Route::get('audition-agency','Agency\AuditionAgency@index');
Route::get('audition-participant','Agency\AuditionAgency@auditionParticipant');
Route::get('new-audition','Agency\AuditionAgency@newAudition');
Route::post('new-audition','Agency\AuditionAgency@newAudition');
Route::get('audition-review/{participant_id}','Agency\AuditionAgency@auditionReview');
Route::post('audition-review/{participant_id}','Agency\AuditionAgency@auditionReview');
Route::get('update-review/{participant_id}','Agency\AuditionAgency@updateReview');
Route::post('update-review/{participant_id}','Agency\AuditionAgency@updateReview');
Route::get('edit-audition/{audition_id}','Agency\AuditionAgency@editAudition');
Route::post('edit-audition/{audition_id}','Agency\AuditionAgency@editAudition');
Route::get('agency-profile','Agency\AuditionAgency@agencyProfile');
Route::get('agency-change-password', function () {
    return view('agency/change-password');
});
Route::get('update-review-new/{participant_id}','Agency\AuditionAgency@updateReviewNew');
Route::post('update-review-new/{participant_id}','Agency\AuditionAgency@updateReviewNew');
Route::get('audition-review-new/{participant_id}','Agency\AuditionAgency@auditionReviewNew');
Route::post('audition-review-new/{participant_id}','Agency\AuditionAgency@auditionReviewNew');

Route::get('/profile/agency/account_settings', 'ProfileController@agency_account_settings');
Route::post('/profile/agency/account_settings/new_password', 'ProfileController@agency_new_password');

Route::get('/challenge/temp/check-progress/{participant_id}', 'TemporaryACReviewController@checkConcatVideoProgress')->name('temp_challenge_review.check-progress');
Route::get('challenge/rewrite/{participant_id}',
            ['middleware' => 'auth', 'uses' => 'TemporaryACReviewController@challengeRewriteReview']
        )->name('challengereview.rewrite');
Route::post('/save-challenge-review/','Challenges\CoachChallenges@storeChallengeAudioFile');
Route::post('/save-audition-review','Agency\AuditionAgency@storeAuditionAudioFile');
Route::get('/audition/temp/check-progress/{participant_id}', 'TemporaryAuditionReviewController@checkConcatVideoProgress')->name('temp_audition_review.check-progress');
Route::get('audition/rewrite/{participant_id}',
            ['middleware' => 'auth', 'uses' => 'TemporaryAuditionReviewController@auditionRewriteReview']
        )->name('auditionreview.rewrite');