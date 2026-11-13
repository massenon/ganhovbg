<?php

namespace App\Http\Controllers\User\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Providers\RouteServiceProvider;

use Auth;
use Hash;
use App\Models\Apptitle;
use App\Traits\SocialAuthSettings;
use GuzzleHttp\Client;
use Laravel\Socialite\Facades\Socialite;
use App\Models\SocialAuthSetting;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Session;
use App\Models\Customer;
use App\Models\Seosetting;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Response;
use GeoIP;
use App\Models\VerifyUser;
use Mail;
use App\Mail\mailmailablesend;
use App\Models\Announcement;



class LoginController extends Controller

{
  use SocialAuthSettings, ThrottlesLogins, AuthenticatesUsers;

    public function showLoginForm()
    {

        $title = Apptitle::first();
        $data['title'] = $title;

        $socialAuthSettings = SocialAuthSetting::first();
        $data['socialAuthSettings'] = $socialAuthSettings;

        $now = now();
        $announcement = announcement::whereDate('enddate', '>=', $now->toDateString())->whereDate('startdate', '<=', $now->toDateString())->get();
        $data['announcement'] = $announcement;

        $announcements = Announcement::whereNotNull('announcementday')->get();
        $data['announcements'] = $announcements;

        $seopage = Seosetting::first();
        $data['seopage'] = $seopage;

        if(setting('only_social_logins') == 'on'){
            return view('user.auth.onlysociallogin')->with($data);
        }

        return view('user.auth.login')->with($data);
    }


    public function emailverification(Request $request)
    {
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

        return view('user.auth.emailverify')->with($data);
        // $unverifiedCustomer = Customer::where('email', $request->email)->first();
    }


    public function emailverificationstore(Request $request)
    {
        $user = Customer::where('email', '=', $request->email)->first();

        $existVerifyUser = VerifyUser::where('cust_id',$user->id)->get();
        if($existVerifyUser != null){
            foreach($existVerifyUser as $existVerifyUsers){
                $existVerifyUsers->delete();
            }
        }

        $verifyUser = VerifyUser::create([
            'cust_id' => $user->id,
            'token' => sha1(time())
        ]);

        $verifyData = [
            'username' => $user->username,
            'email' => $user->email,
            'email_verify_url' => route('verify.email',$verifyUser->token),
        ];

        try{

            Mail::to($user->email)
            ->send( new mailmailablesend( 'customer_sendmail_verification', $verifyData ) );


        }catch(\Exception $e){

            return redirect()->route('auth.login');
        }

        return redirect()->route('auth.login');
    }


    public function login(Request $request)
    {
        if(setting('login_disable') == 'off')
        {


            if(setting('CAPTCHATYPE') == 'off'){
                $request->validate([
                    'email'     => 'required|exists:customers|max:255',
                    'password'  => 'required|min:6|max:255',

                ]);
            }else{
                if(setting('CAPTCHATYPE') == 'manual'){
                    if(setting('RECAPTCH_ENABLE_LOGIN')=='yes'){
                        $this->validate($request, [
                            'email'     => 'required|exists:customers|max:255',
                            'password'  => 'required|min:6|max:255',
                            'captcha' => ['required', 'captcha'],

                        ]);
                    }else{
                        $this->validate($request, [
                            'email'     => 'required|exists:customers|max:255',
                            'password'  => 'required|min:6|max:255',
                        ]);
                    }

                }
                if(setting('CAPTCHATYPE') == 'google'){
                    if(setting('RECAPTCH_ENABLE_LOGIN')=='yes'){
                        $this->validate($request, [
                            'email'     => 'required|exists:customers|max:255',
                            'password'  => 'required|min:6|max:255',
                            'g-recaptcha-response' => 'required|captcha',


                        ]);
                    }else{
                        $this->validate($request, [
                            'email'     => 'required|exists:customers|max:255',
                            'password'  => 'required|min:6|max:255',
                        ]);
                    }
                }
            }

            $credentials  = $request->only('email', 'password');
            $customerExist = Customer::where(['email' => $request->email, 'status' => 0])->exists();

            if ($customerExist) {
                return redirect()->back()->with('error',lang('The account has been deactivated.', 'alerts'));
            }

            $unverifiedCustomer = Customer::where('email', $request->email)->first();

            if (!empty($unverifiedCustomer) && $unverifiedCustomer->verified == 0) {
                return redirect()->back()->with('error',lang('Your email has not been verified. Please verify your email.', 'alerts'));
            }

            if (empty($unverifiedCustomer)) {
                return redirect()->back()->with('error',lang('This email is not registered.', 'alerts'));
            }

            if (Auth::guard('customer')->attempt($credentials)) {

                $cust = Customer::find(Auth::guard('customer')->id());
                $geolocation = GeoIP::getLocation(request()->getClientIp());
                $cust->update([
                    'last_login_at' => Carbon::now()->toDateTimeString(),
                    'last_login_ip' => $geolocation->ip,
                    'last_logins_at' => Carbon::now()->toDateTimeString(),
                ]);
                $request->session()->put('customerticket', Auth::id());
                return redirect()->route('client.dashboard');
            }

            return back()->withInput()->withErrors(['email' => lang('Invalid email or password', 'alerts')]);
        }
        else
        {
            return back()->withInput()->withErrors(['email' => lang('Techincal Issue', 'alerts')]);
        }

    }

    public function ajaxlogin(Request $request)
    {
        if(setting('login_disable') == 'off')
        {
            if(setting('CAPTCHATYPE') == 'off'){
                $validator = Validator::make($request->all(), [
                    'email'     => 'required|exists:customers|max:255',
                    'password'  => 'required|min:6|max:255',
                ]);

            }else{
                if(setting('CAPTCHATYPE') == 'manual'){
                    if(setting('RECAPTCH_ENABLE_LOGIN')=='yes'){
                        $validator = Validator::make($request->all(), [
                            'email'     => 'required|exists:customers|max:255',
                            'password'  => 'required|min:6|max:255',
                            'captcha' => ['required', 'captcha'],
                        ]);

                    }else{
                        $validator = Validator::make($request->all(), [
                            'email'     => 'required|exists:customers|max:255',
                            'password'  => 'required|min:6|max:255',
                        ]);

                    }

                }
                if(setting('CAPTCHATYPE') == 'google'){
                    if(setting('RECAPTCH_ENABLE_LOGIN')=='yes'){
                        $validator = Validator::make($request->all(), [
                            'email'     => 'required|exists:customers|max:255',
                            'password'  => 'required|min:6|max:255',
                            'g-recaptcha-response'  =>  'required',

                        ]);

                    }else{
                        $validator = Validator::make($request->all(), [
                            'email'     => 'required|exists:customers|max:255',
                            'password'  => 'required|min:6|max:255',
                        ]);

                    }
                }
            }

            if ($validator->passes()) {
                $user = $request->email;
                $pass  = $request->password;
                $customerExist = Customer::where(['email' => $request->email, 'status' => 0])->exists();

            if ($customerExist) {
                return response()->json([ [5] ]);
            }
            $unverifiedCustomer = Customer::where('email', $request->email)->first();

            if (!empty($unverifiedCustomer) && $unverifiedCustomer->verified == 0) {
                return response()->json(['email' => $request->email, 'error' => [4] ]);
            }
            if (Auth::guard('customer')->attempt(array('email' => $user, 'password' => $pass)))
            {
                $cust = Customer::find(Auth::guard('customer')->id());
                $cust->update([
                    'last_login_at' => Carbon::now()->toDateTimeString(),
                    'last_login_ip' => $request->getClientIp(),
                    'last_logins_at' => Carbon::now()->toDateTimeString(),
                ]);
                session()->put('customerticket', Auth::guard('customer')->id());
                return response()->json([ [1] ]);

            }
            else
            {
                return response()->json([ [3] ]);
            }
            }
            else {
                return Response::json(['errors' => $validator->errors()]);
            }
            }
            else{
                return response()->json([ [30] ]);
            }

    }

    public function ajaxslogin(Request $request)
    {
        $user = $request->email;
        $pass  = $request->password;
        $pass  = $request->grecaptcha;

        $customerExist = Customer::where(['email' => $request->email, 'status' => 0])->exists();

        if ($customerExist) {
            return response()->json([ [5] ]);
        }
        $unverifiedCustomer = Customer::where('email', $request->email)->first();

        if (!empty($unverifiedCustomer) && $unverifiedCustomer->verified == 0) {
            return response()->json(['email' => $request->email, 'error' => [4] ]);
        }
        if (Auth::guard('customer')->attempt(array('email' => $user, 'password' => $pass)))
        {
            $cust = Customer::find(Auth::guard('customer')->id());
            $geolocation = GeoIP::getLocation(request()->getClientIp());
            $cust->update([
                'last_login_at' => Carbon::now()->toDateTimeString(),
                'last_login_ip' => $geolocation->ip,
                'last_logins_at' => Carbon::now()->toDateTimeString(),
            ]);
            session()->put('customerticket', Auth::guard('customer')->id());
            return response()->json([ [1] ]);

        }
        else
         {
            return response()->json([ [3] ]);
         }
    }


    public function logout()
    {

        request()->session()->forget('customerticket');
        Auth::guard('customer')->logout();
        if(setting('REGISTER_POPUP') == 'yes'){

            return redirect()->route('home')->with('success',lang('Logout Successfull', 'alerts'));
        }else{
            return back()->with('success',lang('Logout Successfull', 'alerts'));
        }

    }

    // Social Login

    public function socialLogin($social)
    {
            $this->setSocailAuthConfigs();

            return Socialite::driver($social)->redirect();
    }
   /**
    * Obtain the user information from Social Logged in.
    * @param $social
    * @return Response
    */
    public function handleProviderCallback($social)
    {

        $this->setSocailAuthConfigs();
        $user = Socialite::driver($social)->user();
        $this->registerOrLogin($user);
        return redirect('espace-client');
      }

    protected function registerOrLogin($data)
    {

        $user = Customer::where('email', '=', $data->email)->first();
        if(!$user){
            $user = new Customer();

            //
            if(array_key_exists('family_name', $data->user)){
                $user->firstname = $data->user['given_name'];
                $user->lastname = $data->user['family_name'];
                $user->username = $data->name;
            }

            if(array_key_exists('surname', $data->user)){
                $user->firstname = $data->user['firstname'];
                $user->lastname = $data->user['surname'];
                $user->username = $data->nickname;
                $user->logintype = 'envatosociallogin';
            }

            if(!array_key_exists('firstname', $data->user)){
                $user->username = $data->user['name'];
            }

            $user->email = $data->email;
            $user->provider_id = $data->id;
            $user->status = '1';
            $user->verified = '1';
            $user->userType = 'Customer';
            $user->save();

        }

        if($user->logintype == null){
            $user->logintype = 'sociallogin';
            $user->save();
        }
        Auth::guard('customer')->login($user);

    }

}
