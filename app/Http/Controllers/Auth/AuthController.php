<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\RegisterToken;
use App\Http\Requests\RegisterCoachRequest;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Support\Facades\Auth;
use Socialite;
use Facebook\Facebook as Facebook;
use App\Http\Helpers\Mailer;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/';
    protected $redirectPath = '/';

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => ['logout', 'getLogout']]);
//        $this->middleware($this->guestMiddleware(), ['except' => 'logout']);
    }

    /**
     * Show the application login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        return redirect('/');
        $hostParts = explode('.', request()->getHost());
        if($hostParts[0] == env('USER_PLATFORM')) {
            $roleNameBySubdomain = User::getUserRoleName();
        } elseif($hostParts[0] == env('COACH_PLATFORM')) {
            $roleNameBySubdomain = User::getCoachRoleName();
        } else {
            $roleNameBySubdomain = User::getAdminRoleName();
        }

        return view('auth.'.$roleNameBySubdomain.'_login');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            //'last_name' => 'required|max:255',
            'first_name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            //'phone' => 'required|phone|max:255',
            'password' => 'required|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'first_name' => $data['first_name'],
            //'last_name' => $data['last_name'],
            'email' => $data['email'],
            //'phone' => $data['phone'],
            'avatar' => '/images/default_avatar_sk.png',
            'role' => User::getUserRole(),
            'password' => bcrypt($data['password']),
        ]);
    }

    protected function createCoach(array $data)
    {
        return User::create([
            'first_name' => $data['first_name'],
            'last_name'  => $data['last_name'],
            'email'      => $data['email'],
            'phone'      => $data['phone'],
            'avatar'     => '/images/default_avatar_sk.png',
            'role'       => User::getCoachRole(),
            'password'   => bcrypt($data['password']),
        ]);
    }

    /**
     * Mail after successfully register
     *
     * @param $data
     */
    protected function mailAfterRegister($data)
    {
        $mail = new Mailer();
        $mail->subject = 'Welcome to Showcase Hub';
        $mail->to_email = $data['email'];
        $mail->sendMail('auth.emails.newUserRegister', [
            'first_name' => $data['first_name']
        ]);
    }
 
    /**
     * Redirect the user to the Facebook authentication page.
     * 
     * @return Response
     */
    public function redirectToProvider()
    {
        return Socialite::driver('facebook')->fields([
            'first_name', 'last_name', 'gender', 'email', 'birthday', 'location', 'religion', 'hometown', 'bio', 'languages', 'work'
        ])->scopes([
            'email'
        ])->redirect();
    }
 
    /**
     * Obtain the user information from Facebook.
     *
     * @return Response
     */
    public function handleProviderCallback()
    {
        try {
            $user = Socialite::driver('facebook')->fields([
            'first_name', 'last_name', 'email', 'gender', 'birthday', 'location', 'religion', 'hometown', 'about', 'languages', 'work'
        ])->user();
        } catch (Exception $e) {
            return redirect('auth/facebook');
        }

        $authUser = $this->findOrCreateUser($user);
 
        Auth::login($authUser, true);
        return redirect()->route('index');
    }
 
    /**
     * Return user if exists; create and return if doesn't
     *
     * @param $facebookUser
     * @return User
     */
    private function findOrCreateUser($facebookUser)
    {
        
        $authUser = User::where('facebook_id', $facebookUser->id)->first();
 
        if ($authUser){
            return $authUser;
        }
 
        $user = $facebookUser->user;

        if(!isset($user["email"])){
            echo '<h2>Authentication failed.</h2>';
            echo '<p>Open access to your email address in the facebook settings!</p>';
            exit;
        }

        if( User::where('email', $user["email"])->exists() ){
            if( User::where('email', $user["email"])->exists() ){
                $our_user = User::where('email', $user["email"])->first();
                $our_user->facebook_id = $user["id"];
                $our_user->save();
                return $our_user;
            }
        }

        return User::create([
            'first_name'    => !empty($user["first_name"]) ? $user["first_name"] : '',
            'last_name'     => !empty($user["last_name"]) ? $user["last_name"] : '',
            'email'         => !empty($user["email"]) ? $user["email"] : null,
            'facebook_id'   => !empty($user["id"]) ? $user["id"] : '',
            'avatar'        => $facebookUser->avatar,
            'role'          => User::getUserRole(),
            'gender'        => !empty($user["gender"]) ? $user["gender"] : '',
            'location'      => !empty($user["location"]["name"]) ? $user["location"]["name"] : '',
            'birthday'      => !empty($user["birthday"]) ? $user["birthday"] : ''
            ]);
    }
    
    public function getLogout(){
        $this->logout();
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = $this->validator($request->except('_token'));
        if ($validator->fails()) {
            $this->throwValidationException($request, $validator);
        }

        //send mail
        $mail = new Mailer();
        $mail->subject = 'Welcome to Showcase Hub';
        $mail->to_email = $request->get('email');
        $mail->sendMail('auth.emails.newUserRegister', ['first_name'=>$request->get('first_name')]);

        Auth::guard($this->getGuard())->login($this->create($request->all()));
        return redirect($this->redirectPath());
    }

    public function registerCoach(RegisterCoachRequest $request)
    {
        $token = $request->get('token');
        if( ! RegisterToken::exists($token)) {
            abort_unless(false, 404);
        }

        Auth::guard($this->getGuard())->login($this->createCoach($request->only([
            'first_name', 'last_name', 'email', 'phone', 'password'
        ])));

        RegisterToken::whereToken($token)->delete();

        $this->mailAfterRegister([
            'email' => $request->get('email'),
            'first_name' => $request->get('first_name')
        ]);

        return redirect($this->redirectPath());
    }

    public function showCoachRegistrationForm(Request $request, $token)
    {
        $hostParts = explode('.', request()->getHost());
        if($hostParts[0] == env('COACH_PLATFORM')) {
            if(RegisterToken::exists($token)) {
                return view('auth.coach_register', ['token' => $token]);
            }
        } else {
            return redirect('/');
        }

        abort_unless(false, 404);
    }


    /**
     * Get the post register / login redirect path.
     *
     * @return string
     */
    public function redirectPath()
    {
        return $this->redirectPath;
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        $throttles = $this->isUsingThrottlesLoginsTrait();
        if ($throttles && $lockedOut = $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }

        $credentials = $this->getCredentials($request);
        // Check user role.
        $hostParts = explode('.', $request->getHost());
        if($hostParts[0] == env('USER_PLATFORM')) {
            $credentials['role'] = User::getUserRole();
        } elseif($hostParts[0] == env('COACH_PLATFORM')) {
            $credentials['role'] = User::getCoachRole();
        } elseif($hostParts[0] == env('AGENCY_PLATFORM')) {
            $credentials['role'] = User::getAgencyRole();
        } else {
            $credentials['role'] = User::getAdminRole();
        }

        if (Auth::guard($this->getGuard())->attempt($credentials, $request->has('remember'))) {
            return $this->handleUserWasAuthenticated($request, $throttles);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        if ($throttles && ! $lockedOut) {
            $this->incrementLoginAttempts($request);
        }

        return $this->sendFailedLoginResponse($request);
    }

}
