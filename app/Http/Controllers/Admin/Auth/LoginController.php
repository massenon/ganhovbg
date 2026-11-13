<?php

namespace App\Http\Controllers\Admin\Auth;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use App\Models\Apptitle;
use App\Models\SocialAuthSetting;
use App\Models\User;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Validation\ValidationException;

use App\Models\Seosetting;
use Auth;
use Session;
use GeoIP;
use App\Models\Announcement;
use App\Helper\Installer\trait\ApichecktraitHelper;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */


    use ThrottlesLogins,AuthenticatesUsers {
        logout as performLogout;
    }

    use ApichecktraitHelper;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }


    public function showloginform(){

        $title = Apptitle::first();
        $data['title'] = $title;

        $socialAuthSettings = SocialAuthSetting::first();
        $data['socialAuthSettings'] = $socialAuthSettings;

        $seopage = Seosetting::first();
        $data['seopage'] = $seopage;

        $now = now();
        $announcement = announcement::whereDate('enddate', '>=', $now->toDateString())->whereDate('startdate', '<=', $now->toDateString())->get();
        $data['announcement'] = $announcement;

        $announcements = Announcement::whereNotNull('announcementday')->get();
        $data['announcements'] = $announcements;


        return view('admin.auth.login', ['url'=> 'administration/connexion'])->with($data);
    }

    protected function credentials(Request $request)
    {
        return [
            'email' => $request->{$this->username()},
            'password' => $request->password,
            'status' => '1'
        ];
    }

    public function redirectTo(){

            $user = auth()->user();
            if ($user) {

                return 'administration/';
            }



    }
    protected function validateLogin(Request $request)
    {

        if(setting('CAPTCHATYPE') == 'off'){
            $rules = [
                'email' => 'required|string|max:255',
                'password' => 'required|string|max:255'
            ];
        }else{
            if(setting('CAPTCHATYPE') == 'manual'){
                if(setting('RECAPTCH_ENABLE_ADMIN_LOGIN')=='yes'){

                    $rules = [
                        'email' => 'required|string|max:255',
                        'password' => 'required|string|max:255',
                        'captcha' => ['required', 'captcha'],
                    ];

                }else{

                    $rules = [
                        'email' => 'required|string|max:255',
                        'password' => 'required|string|max:255'
                    ];

                }

            }
            if(setting('CAPTCHATYPE') == 'google'){
                if(setting('RECAPTCH_ENABLE_ADMIN_LOGIN')=='yes'){

                    $rules = [
                        'email' => 'required|string|max:255',
                        'password' => 'required|string|max:255',
                        'g-recaptcha-response'  =>  'required|recaptcha',
                    ];

                }else{
                    $rules = [
                        'email' => 'required|string|max:255',
                        'password' => 'required|string|max:255'
                    ];
                }
            }
        }




        // User type from email/username
        $user = User::where($this->username(), $request->{$this->username()})->first();

        $this->validate($request, $rules);
    }
    public function userInactiveMessage()
    {
        throw ValidationException::withMessages([
            $this->username() => ['error'=> lang('Your Account is Inactive. Please Contact to Admin.', 'alerts')],
        ]);
    }
    public function login(Request $request)
    {
        $this->validateLogin($request);

        // User type from email/username
        $user = User::where($this->username(), $request->{$this->username()})->first();

        if ($user && $user->status == '0') {
            return $this->userInactiveMessage();
        }

        if ($user && $user->verified == '0') {
            return $this->userverifiedMessage();
        }

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if (
            method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)
        ) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    public function validatelog()
    {
        $sessionPath = storage_path('framework/sessions');
        $sessionFiles = glob($sessionPath . '/*');
        foreach ($sessionFiles as $sessionFile) {
            unlink($sessionFile);
        }
        Session::flush();

        return redirect()->route('login');
    }

    public function userverifiedMessage()
    {
        throw ValidationException::withMessages([
            $this->username() => ['error'=> lang('Your Account is Not Verified.', 'alerts')],
        ]);
    }

    public function logout(Request $request)
    {

        $this->performLogout($request);
        return redirect()->route('login');
    }

}
