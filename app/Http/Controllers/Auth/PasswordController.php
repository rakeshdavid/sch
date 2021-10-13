<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Facades\Password;
use App\Models\User;
use App\Http\Helpers\Mailer;

class PasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    protected $redirectTo = '/';

    /**
     * Create a new password controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
        $this->subject = 'Forgot your Password?';
    }

    /**
     * Send a reset link to the given user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function sendResetLinkEmail(Request $request)
    {
        $this->validateSendResetLinkEmail($request);
        $broker = $this->getBroker();
        try {
            $user = Password::broker($broker)->getUser($this->getSendResetLinkEmailCredentials($request));
        } catch (\UnexpectedValueException $e) {
            $user = null;
        }
//        if(!is_null($user)) {
//           if(!empty($user->facebook_id)) {
//               $response = Password::INVALID_USER;
//               return $this->getSendResetLinkEmailFailureResponse($response);
//           }
//        }
        $response = Password::broker($broker)->sendResetLink(
            $this->getSendResetLinkEmailCredentials($request),
            $this->resetEmailBuilder()
        );

        switch ($response) {
            case Password::RESET_LINK_SENT:
                return $this->getSendResetLinkEmailSuccessResponse($response);
            case Password::INVALID_USER:
            default:
                return $this->getSendResetLinkEmailFailureResponse($response);
        }
    }

    public function reset(Request $request)
    {
        $this->validate(
            $request,
            $this->getResetValidationRules(),
            $this->getResetValidationMessages(),
            $this->getResetValidationCustomAttributes()
        );

        $credentials = $this->getResetCredentials($request);

        $broker = $this->getBroker();

        $response = Password::broker($broker)->reset($credentials, function ($user, $password) {
            $this->resetPassword($user, $password);
        });

        $user_mail = $request->get('email');
        $user = User::select('first_name')->where('email', $user_mail)->first();
        $mail = new Mailer();
        $mail->subject = 'Successfully Reset Your Password!';
        $mail->to_email = $user_mail;
        $mail->sendMail('auth.emails.resetSuccessfully',
            [
                'user_name'=>$user->first_name,
                'id'=>$user_mail
            ]);

        switch ($response) {
            case Password::PASSWORD_RESET:
                return $this->getResetSuccessResponse($response);
            default:
                return $this->getResetFailureResponse($request, $response);
        }
    }
}
