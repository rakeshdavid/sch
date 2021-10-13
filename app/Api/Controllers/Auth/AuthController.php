<?php

namespace App\Api\Controllers\Auth;

use App\Api\Requests\RegisterUser;
use App\Http\Helpers\Mailer;
use App\Models\User;
use Dingo\Api\Http\Request;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Facades\Password;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Api\Controllers\BaseController;
use Socialite;
use Illuminate\Support\Facades\Log;
use Dingo\Api\Exception\StoreResourceFailedException;

/**
 * @Resource("Authentication")
 */
class AuthController extends BaseController
{
    use ResetsPasswords;

    /**
     * Login as user
     *
     * Returns JWT token on successful authentication.
     *
     * @param Request $request
     * @return array
     *
     * @Post("/login-user")
     * @Transaction({
     *     @Request({"email": "test@test.com", "password": "testpassword"}),
     *     @Response(200, body={"data": {"token": "<JWT>"}, "status_code": 200}),
     *     @Response(422, body={"error":"Credentials error message", "status_code": 422}),
     *     @Response(500, body={"error":"Server side error message", "status_code": 500})
     * })
     * @Versions({"v1"})
     */
    public function loginUser(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $credentials['role'] = User::USER_ROLE;
        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'User with this email or password does not exists.',
                    'status_code' => 422], 422);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'Could not create token!', 'status_code' => 500], 500);
        }
        return response()->json(['data' => compact('token'), "status_code" => 200]);
    }

    /**
     * Register as user
     *
     * Returns JWT token on successful registration.
     *
     * @param RegisterUser $request
     * @return \Dingo\Api\Http\Response
     * @throws \ErrorException
     *
     * @Post("/register-user")
     * @Transaction({
     *     @Request({"email": "test@test.com", "last_name": "last name", "first_name": "first name", "phone": "123456789",
     *         "password": "testpassword", "password_confirmation": "testpassword"}),
     *     @Response(200, body={"data": {"token": "<JWT>"}, "status_code": 200}),
     *     @Response(422, body={"message": "422 Unprocessable Entity", "status_code": 422,
     *         "errors":{"email": {"This email is already taken."}, "last_name": {"The last name field is required."},
     *         "password": {"The password confirmation does not match.", "The password field is required."}}}),
     *     @Response(500, body={"error":"Server side error message", "status_code": 500})
     * })
     * @Versions({"v1"})
     */
    public function registerUser(RegisterUser $request)
    {
        //send mail
        $mail = new Mailer();
        $mail->subject = 'Welcome to Showcase Hub';
        $mail->to_email = $request->get('email');
        $mail->sendMail('auth.emails.newUserRegister', ['first_name' => $request->get('first_name')]);
        $userData = $request->all();
        $userData['role'] = User::USER_ROLE;
        $userData['password'] = bcrypt($userData['password']);
        $user = User::create($userData);
        $token = JWTAuth::fromUser($user);
        return $this->response->array([
            'data' => compact('token'),
            'status_code' => 200
        ]);
    }

    /**
     * Reset password for user
     *
     * Returns status message or errors.
     *
     * @param Request $request
     * @return array
     *
     * @Post("/password-user")
     * @Transaction({
     *     @Request({"email": "test@test.com"}),
     *     @Response(200, body={"data": {"message":"We sent instructions to your mailbox."}, "status_code": 200}),
     *     @Response(422, body={"error": "422 Unprocessable Entity", "status_code": 422,
     *         "errors": {"email": {"The email field is required."}}}),
     *     @Response(500, body={"error":"Server side error message", "status_code": 500})
     * })
     * @Versions({"v1"})
     */
    public function resetPasswordUser(Request $request)
    {
        $this->validateSendResetLinkEmail($request);
        $broker = $this->getBroker();
        try {
            $user = Password::broker($broker)->getUser($this->getSendResetLinkEmailCredentials($request));
        } catch (\UnexpectedValueException $e) {
            $user = null;
        }
        $response = Password::broker($broker)->sendResetLink(
            $this->getSendResetLinkEmailCredentials($request),
            $this->resetEmailBuilder()
        );

        switch ($response) {
            case Password::RESET_LINK_SENT:
                return response()->json(['data' => ['message' => 'We sent instructions to your email.'], 'status_code' => 200]);
            case Password::INVALID_USER:
                return response()->json(['error' => '422 Unprocessable Entity', 'errors' => [
                    'email' => ['This credentials does not match our records.'],
                ], 'status_code' => 422]);
            default:
                return response()->json(['error' => 'Oops! Something went wrong.', 'status_code' => 500]);
        }
    }

    /**
     * Login through facebook
     *
     * Returns JWT token on successful authentication.
     *
     * @param Request $request
     * @return array
     *
     * @GET("/facebook-login")
     * * @Parameters({
     *      @Parameter("code", description="facebook OAuth 2.0 token, required")
     * })
     * @Transaction({
     *     @Response(200, body={"data": {"token": "<JWT>"}, "status_code": 200}),
     *     @Response(422, body={"message": "Request params error!","errors": {"code": {"Facebook token required!"}},"status_code": 422}),
     *     @Response(422, body={"message": "Facebook settings error!","errors": {"email": {"Open access to your email address in the facebook settings!"}},"status_code": 422}),
     *     @Response(500, body={"error":"Server side error message", "status_code": 500})
     * })
     * @Versions({"v1"})
     */
    public function facebookLogin(Request $request)
    {
        if(!$request->code){
            throw new StoreResourceFailedException(
                'Request params error!',
                ['code' => ['Facebook token required!']]
            );
        }
        if( !$request->user_id ){
            return response()->json(['error' => 'Oops! Something went wrong.', 'status_code' => 422]);
        }
        if( !$request->email ){
            return response()->json(['error' => 'Oops! Something went wrong.', 'status_code' => 422]);
        }
        if( !$request->full_name ){
            return response()->json(['error' => 'Oops! Something went wrong.', 'status_code' => 422]);
        }
        //$facebookUser = Socialite::driver('facebook')->userFromToken($request->code);
        $facebookUser['id'] = $request->user_id;
        $facebookUser['full_name'] = $request->full_name;
        $facebookUser['email'] = $request->email;
        //$facebookUser = Socialite::driver('facebook')->userFromToken($request->code);

        $authUser = $this->findOrCreateUser($facebookUser);
        if(!$authUser){
            throw new StoreResourceFailedException(
                'Facebook settings error!',
                ['email' => ['Open access to your email address in the facebook settings!']]
            );
        }
        $token = JWTAuth::fromUser($authUser);

        return response()->json(['data' => compact('token'),'status_code'=>200]);
    }

    private function findOrCreateUser($facebookUser)
    {
        $authUser = User::where('facebook_id', $facebookUser['id'])->first();

        if ($authUser){
            return $authUser;
        }

        //$user = $facebookUser->user;
        $user["email"] = $facebookUser['email'];
        $user["id"] = $facebookUser['id'];
        $user["email"] = $facebookUser['email'];
        $user["last_name"]='';
        $user["gender"]='';
        $user["location"]['name']='';
        $user["birthday"]='';

        if(!isset($user["email"])){
            return false;
        }

        if( User::where('email', $user["email"])->exists() ){
            $our_user = User::where('email', $user["email"])->first();
            $our_user->facebook_id = $user["id"];
            $our_user->save();
            return $our_user;
        }

        return User::create([
            'first_name'    => $facebookUser['full_name'],
            'last_name'     => !empty($user["last_name"]) ? $user["last_name"] : '',
            'email'         => !empty($user["email"]) ? $user["email"] : null,
            'facebook_id'   => !empty($user["id"]) ? $user["id"] : '',
            'avatar'        => '',
            'role'          => User::getUserRole(),
            'gender'        => !empty($user["gender"]) ? $user["gender"] : '',
            'location'      => !empty($user["location"]["name"]) ? $user["location"]["name"] : '',
            'birthday'      => !empty($user["birthday"]) ? $user["birthday"] : ''
        ]);
    }
}
