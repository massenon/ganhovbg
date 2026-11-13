<?php

namespace App\Http\Controllers\User\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Hash;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Models\Profile\UserProfile;
use App\Models\Apptitle;
use App\Models\VerifyUser;
use App\Mail\VerifyMail;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use Response;
use Illuminate\Support\Facades\Input;
use App\Notifications\NewUserNotification;
use App\Models\Seosetting;
use App\Models\CustomerSetting;
use GeoIP;
use App\Traits\SocialAuthSettings;
use App\Models\SocialAuthSetting;

use Mail;
use App\Mail\mailmailablesend;
use App\Models\Customfield;
use App\Models\TicketCustomfield;
use App\Models\Announcement;

class RegisterController extends Controller
{
    use RegistersUsers, SocialAuthSettings;

    public function showRegistrationForm(){

        $title = Apptitle::first();
        $data['title'] = $title;

        $seopage = Seosetting::first();
        $data['seopage'] = $seopage;

        $socialAuthSettings = SocialAuthSetting::first();
        $data['socialAuthSettings'] = $socialAuthSettings;

        $customfields = Customfield::whereIn('displaytypes', ['both', 'registerform'])->where('status','1')->get();
        $data['customfields'] = $customfields;

        $now = now();
        $announcement = announcement::whereDate('enddate', '>=', $now->toDateString())->whereDate('startdate', '<=', $now->toDateString())->get();
        $data['announcement'] = $announcement;

        $announcements = Announcement::whereNotNull('announcementday')->get();
        $data['announcements'] = $announcements;


        return view('user.auth.register')->with($data);
    }

    public function register(Request $request)
    {
        if(setting('REGISTER_DISABLE') == 'off' || setting('mail_host') == 'smtp.mailtrap.io')
        {
            return Response::json(['error' => lang('Techincal Issue', 'alerts')]);
            // return redirect()->route('auth.register')->with(['error' => lang('Techincal Issue', 'alerts')]);
        }

        $guest = Customer::where('email', '=', $request->email)->first();

        if(Customer::where('email', '=', $request->email)->exists() ){

            if($guest->password == null && $guest->provider_id == null && $guest->userType == 'Guest'){

                if(setting('CAPTCHATYPE') == 'off'){
                    $validator = Validator::make($request->all(), [
                        'firstname' => 'required|max:255',
                        'lastname' => 'required|max:255',
                        'email' => 'required|email|max:255|indisposable|unique:customers',
                        'password' => 'required|min:8|confirmed',
                    ]);
                }else{
                    if(setting('CAPTCHATYPE') == 'manual'){
                        if(setting('RECAPTCH_ENABLE_REGISTER')=='yes'){
                            $validator = Validator::make($request->all(), [
                                'firstname' => 'required|max:255',
                                'lastname' => 'required|max:255',
                                'email' => 'required|email|max:255|indisposable|unique:customers',
                                'password' => 'required|min:8|confirmed',
                                'captcha' => ['required', 'captcha'],
                            ]);
                        }else{
                            $validator = Validator::make($request->all(), [
                                'firstname' => 'required|max:255',
                                'lastname' => 'required|max:255',
                                'email' => 'required|email|max:255|indisposable|unique:customers',
                                'password' => 'required|min:8|confirmed',
                            ]);
                        }

                    }
                    if(setting('CAPTCHATYPE') == 'google'){
                        if(setting('RECAPTCH_ENABLE_REGISTER')=='yes'){
                            $validator = Validator::make($request->all(), [
                                'firstname' => 'required|max:255',
                                'lastname' => 'required|max:255',
                                'email' => 'required|email|max:255|indisposable|unique:customers',
                                'password' => 'required|min:8|confirmed',
                                'g-recaptcha-response'  =>  'required|recaptcha',
                            ]);
                        }else{
                            $validator = Validator::make($request->all(), [
                                'firstname' => 'required|max:255',
                                'lastname' => 'required|max:255',
                                'email' => 'required|email|max:255|indisposable|unique:customers',
                                'password' => 'required|min:8|confirmed',
                            ]);
                        }
                    }
                }

                $geolocation = GeoIP::getLocation(request()->getClientIp());

                $guest->firstname = $request->firstname;
                $guest->lastname = $request->lastname;
                $guest->username = $request->firstname .' '. $request->lastname;
                $guest->password = Hash::make($request->password);
                $guest->userType = 'Customer';
                $guest->country = $geolocation->country;
                $guest->timezone = $geolocation->timezone;
                $guest->update();

                $verifyUser = VerifyUser::create([
                    'cust_id' => $guest->id,
                    'token' => sha1(time())
                ]);

                $customfields = Customfield::whereIn('displaytypes', ['both', 'registerform'])->get();

                foreach($customfields as $customfield)
                {
                    $ticketcustomfield = new TicketCustomfield();
                    $ticketcustomfield->cust_id = $guest->id;
                    $ticketcustomfield->fieldnames = $customfield->fieldnames;
                    $ticketcustomfield->fieldtypes = $customfield->fieldtypes;
                    if($customfield->fieldtypes == 'checkbox'){
                        if($request->input('custom_'.$customfield->id) != null){

                            $string = implode(',', $request->input('custom_'.$customfield->id));
                            $ticketcustomfield->values = $string;
                        }

                    }
                    if($customfield->fieldtypes != 'checkbox'){
                        if($customfield->fieldprivacy == '1'){
                            $ticketcustomfield->privacymode  = $customfield->fieldprivacy;
                            $ticketcustomfield->values = encrypt($request->input('custom_'.$customfield->id));
                        }else{

                            $ticketcustomfield->values = $request->input('custom_'.$customfield->id);
                        }
                    }
                    $ticketcustomfield->save();

                }
                $verifyData = [

                    'username' => $guest->username,
                    'email' => $guest->email,
                    'email_verify_url' => route('verify.email',$verifyUser->token),

                ];

                try{

                    Mail::to($guest->email)
                    ->send( new mailmailablesend( 'customer_sendmail_verification', $verifyData ) );


                }catch(\Exception $e){


                    return redirect()->route('auth.login')->with('success', lang('The email verification link was successfully sent. Please check and verify your email.', 'alerts'));
                }
                return Response::json(['success' => lang('The email verification link was successfully sent. Please check and verify your email.', 'alerts')]);



            }

            else{


                if(setting('CAPTCHATYPE') == 'off'){
                    $request->validate([
                        'firstname' => 'required|max:255',
                        'lastname' => 'required|max:255',
                        'email' => 'required|string|email|max:255|indisposable|unique:customers',
                        'password' => 'required|string|min:8|confirmed',
                        'password_confirmation' => 'required',
                        'agree_terms' =>  'required|in:agreed',
                    ]);
                }else{
                    if(setting('CAPTCHATYPE') == 'manual'){
                        if(setting('RECAPTCH_ENABLE_REGISTER')=='yes'){
                            $request->validate([
                                'firstname' => 'required|max:255',
                                'lastname' => 'required|max:255',
                                'email' => 'required|string|email|max:255|indisposable|unique:customers',
                                'password' => 'required|string|min:8|confirmed',
                                'password_confirmation' => 'required',
                                'agree_terms' =>  'required|in:agreed',
                                'captcha' => ['required', 'captcha'],
                            ]);

                        }else{
                            $request->validate([
                                'firstname' => 'required|max:255',
                                'lastname' => 'required|max:255',
                                'email' => 'required|string|email|max:255|indisposable|unique:customers',
                                'password' => 'required|string|min:8|confirmed',
                                'password_confirmation' => 'required',
                                'agree_terms' =>  'required|in:agreed',

                            ]);

                        }

                    }
                    if(setting('CAPTCHATYPE') == 'google'){
                        if(setting('RECAPTCH_ENABLE_REGISTER')=='yes'){
                            $request->validate([
                                'firstname' => 'required|max:255',
                                'lastname' => 'required|max:255',
                                'email' => 'required|string|email|max:255|indisposable|unique:customers',
                                'password' => 'required|string|min:8|confirmed',
                                'password_confirmation' => 'required',
                                'agree_terms' =>  'required|in:agreed',
                                'g-recaptcha-response'  =>  'required|recaptcha',

                            ]);

                        }else{
                            $request->validate([
                                'firstname' => 'required|max:255',
                                'lastname' => 'required|max:255',
                                'email' => 'required|string|email|max:255|indisposable|unique:customers',
                                'password' => 'required|string|min:8|confirmed',
                                'password_confirmation' => 'required',
                                'agree_terms' =>  'required|in:agreed',

                            ]);
                        }
                    }
                }



                $geolocation = GeoIP::getLocation(request()->getClientIp());
                $user =  Customer::create([
                    'firstname' => $request->input('firstname'),
                    'lastname' => $request->input('lastname'),
                    'username' => $request->input('firstname') .' '. $request->input('lastname'),
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'userType' => 'Customer',
                    'country' => $geolocation->country,
                    'timezone' => $geolocation->timezone,
                    'status' => '1',
                    'image' => null,

                ]);

                $customersetting = new CustomerSetting();
                $customersetting->custs_id = $user->id;
                $customersetting->darkmode = setting('DARK_MODE');
                $customersetting->save();

                $verifyUser = VerifyUser::create([
                    'cust_id' => $user->id,
                    'token' => sha1(time())
                ]);

                $customfields = Customfield::whereIn('displaytypes', ['both', 'registerform'])->get();

                foreach($customfields as $customfield)
                {
                    $ticketcustomfield = new TicketCustomfield();
                    $ticketcustomfield->cust_id = $user->id;
                    $ticketcustomfield->fieldnames = $customfield->fieldnames;
                    $ticketcustomfield->fieldtypes = $customfield->fieldtypes;
                    if($customfield->fieldtypes == 'checkbox'){
                        if($request->input('custom_'.$customfield->id) != null){

                            $string = implode(',', $request->input('custom_'.$customfield->id));
                            $ticketcustomfield->values = $string;
                        }

                    }
                    if($customfield->fieldtypes != 'checkbox'){
                        if($customfield->fieldprivacy == '1'){
                            $ticketcustomfield->privacymode  = $customfield->fieldprivacy;
                            $ticketcustomfield->values = encrypt($request->input('custom_'.$customfield->id));
                        }else{

                            $ticketcustomfield->values = $request->input('custom_'.$customfield->id);
                        }
                    }
                    $ticketcustomfield->save();

                }

                $verifyData = [

                    'username' => $user->username,
                    'email' => $user->email,
                    'email_verify_url' => route('verify.email',$verifyUser->token),

                ];
                try{

                    Mail::to($user->email)
                    ->send( new mailmailablesend( 'customer_sendmail_verification', $verifyData ) );


                }catch(\Exception $e){

                    return redirect()->route('auth.login')->with('success', lang('The email verification link was successfully sent. Please check and verify your email.', 'alerts'));
                }
                return Response::json(['success' => lang('The email verification link was successfully sent. Please check and verify your email.', 'alerts')]);
                // return redirect()->route('auth.login')->with('success', lang('The email verification link was successfully sent. Please check and verify your email.', 'alerts'));
            }
        }
        else{

            if(setting('CAPTCHATYPE') == 'off'){
                $request->validate([
                    'firstname' => 'required|max:255',
                    'lastname' => 'required|max:255',
                    'email' => 'required|string|email|max:255|indisposable|unique:customers',
                    'password' => 'required|string|min:8|confirmed',
                    'password_confirmation' => 'required',
                    'agree_terms' =>  'required|in:agreed',
                ]);
            }else{
                if(setting('CAPTCHATYPE') == 'manual'){
                    if(setting('RECAPTCH_ENABLE_REGISTER')=='yes'){
                        $request->validate([
                            'firstname' => 'required|max:255',
                            'lastname' => 'required|max:255',
                            'email' => 'required|string|email|max:255|indisposable|unique:customers',
                            'password' => 'required|string|min:8|confirmed',
                            'password_confirmation' => 'required',
                            'agree_terms' =>  'required|in:agreed',
                            'captcha' => ['required', 'captcha'],
                        ]);

                    }else{
                        $request->validate([
                            'firstname' => 'required|max:255',
                            'lastname' => 'required|max:255',
                            'email' => 'required|string|email|max:255|indisposable|unique:customers',
                            'password' => 'required|string|min:8|confirmed',
                            'password_confirmation' => 'required',
                            'agree_terms' =>  'required|in:agreed',

                        ]);

                    }

                }
                if(setting('CAPTCHATYPE') == 'google'){
                    if(setting('RECAPTCH_ENABLE_REGISTER')=='yes'){
                        $request->validate([
                            'firstname' => 'required|max:255',
                            'lastname' => 'required|max:255',
                            'email' => 'required|string|email|max:255|indisposable|unique:customers',
                            'password' => 'required|string|min:8|confirmed',
                            'password_confirmation' => 'required',
                            'agree_terms' =>  'required|in:agreed',
                            'g-recaptcha-response'  =>  'required|recaptcha',

                        ]);

                    }else{
                        $request->validate([
                            'firstname' => 'required|max:255',
                            'lastname' => 'required|max:255',
                            'email' => 'required|string|email|max:255|indisposable|unique:customers',
                            'password' => 'required|string|min:8|confirmed',
                            'password_confirmation' => 'required',
                            'agree_terms' =>  'required|in:agreed',

                        ]);
                    }
                }
            }
                $geolocation = GeoIP::getLocation(request()->getClientIp());
                $user =  Customer::create([
                    'firstname' => $request->input('firstname'),
                    'lastname' => $request->input('lastname'),
                    'username' => $request->input('firstname') .' '. $request->input('lastname'),
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'userType' => 'Customer',
                    'country' => $geolocation->country,
                    'timezone' => $geolocation->timezone,
                    'status' => '1',
                    'image' => null,

                ]);

                $customersetting = new CustomerSetting();
                $customersetting->custs_id = $user->id;
                $customersetting->save();

                $customfields = Customfield::whereIn('displaytypes', ['both', 'registerform'])->get();

                foreach($customfields as $customfield)
                {
                    $ticketcustomfield = new TicketCustomfield();
                    $ticketcustomfield->cust_id = $user->id;
                    $ticketcustomfield->fieldnames = $customfield->fieldnames;
                    $ticketcustomfield->fieldtypes = $customfield->fieldtypes;
                    if($customfield->fieldtypes == 'checkbox'){
                        if($request->input('custom_'.$customfield->id) != null){

                            $string = implode(',', $request->input('custom_'.$customfield->id));
                            $ticketcustomfield->values = $string;
                        }

                    }
                    if($customfield->fieldtypes != 'checkbox'){
                        if($customfield->fieldprivacy == '1'){
                            $ticketcustomfield->privacymode  = $customfield->fieldprivacy;
                            $ticketcustomfield->values = encrypt($request->input('custom_'.$customfield->id));
                        }else{

                            $ticketcustomfield->values = $request->input('custom_'.$customfield->id);
                        }
                    }
                    $ticketcustomfield->save();

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

                    return Response::json(['success' => lang('The email verification link was successfully sent. Please check and verify your email.', 'alerts')]);
                }
                return Response::json(['success' => lang('The email verification link was successfully sent. Please check and verify your email.', 'alerts')]);
                // return redirect()->route('auth.login')->with('success', lang('The email verification link was successfully sent. Please check and verify your email.', 'alerts'));

        }
    }

    public function registers(Request $request)
    {
        if(setting('REGISTER_DISABLE') == 'off'){
            return Response::json(['success' => lang('Techincal Issue', 'alerts')]);
        }

        $guest = Customer::where('email', '=', $request->email)->first();

        if(Customer::where('email', '=', $request->email)->exists()){
            if($guest->password == null && $guest->provider_id == null && $guest->userType == 'Guest'){


                if(setting('CAPTCHATYPE') == 'off'){
                    $validator = Validator::make($request->all(), [
                        'firstname' => 'required|max:255',
                        'lastname' => 'required|max:255',
                        'email' => 'required|email|max:255|indisposable|unique:customers',
                        'password' => 'required|min:8|confirmed',
                        'agree_terms' =>  'required|in:agreed',

                    ]);
                }else{
                    if(setting('CAPTCHATYPE') == 'manual'){
                        if(setting('RECAPTCH_ENABLE_REGISTER')=='yes'){
                            $validator = Validator::make($request->all(), [
                                'firstname' => 'required|max:255',
                                'lastname' => 'required|max:255',
                                'email' => 'required|email|max:255|indisposable|unique:customers',
                                'password' => 'required|min:8|confirmed',
                                'agree_terms' =>  'required|in:agreed',
                                'captcha' => ['required', 'captcha'],
                            ]);


                        }else{
                            $validator = Validator::make($request->all(), [
                                'firstname' => 'required|max:255',
                                'lastname' => 'required|max:255',
                                'email' => 'required|email|max:255|indisposable|unique:customers',
                                'password' => 'required|min:8|confirmed',
                                'agree_terms' =>  'required|in:agreed',

                            ]);

                        }

                    }
                    if(setting('CAPTCHATYPE') == 'google'){
                        if(setting('RECAPTCH_ENABLE_REGISTER')=='yes'){
                            $validator = Validator::make($request->all(), [
                                'firstname' => 'required|max:255',
                                'lastname' => 'required|max:255',
                                'email' => 'required|email|max:255|indisposable|unique:customers',
                                'password' => 'required|min:8|confirmed',
                                'agree_terms' =>  'required|in:agreed',
                                'g-recaptcha-response'  =>  'recaptcha',
                            ]);

                        }else{
                            $validator = Validator::make($request->all(), [
                                'firstname' => 'required|max:255',
                                'lastname' => 'required|max:255',
                                'email' => 'required|email|max:255|indisposable|unique:customers',
                                'password' => 'required|min:8|confirmed',
                                'agree_terms' =>  'required|in:agreed',

                            ]);
                        }
                    }
                }

                $geolocation = GeoIP::getLocation(request()->getClientIp());
                $guest->firstname = $request->firstname;
                $guest->lastname = $request->lastname;
                $guest->username = $request->firstname .' '.$request->lastname;
                $guest->password = Hash::make($request->password);
                $guest->userType = 'Customer';
                $guest->country = $geolocation->country;
                $guest->timezone = $geolocation->timezone;
                $guest->update();

                $verifyUser = VerifyUser::create([
                    'cust_id' => $guest->id,
                    'token' => sha1(time())
                ]);

                $customfields = Customfield::whereIn('displaytypes', ['both', 'registerform'])->get();

                foreach($customfields as $customfield)
                {
                    $ticketcustomfield = new TicketCustomfield();
                    $ticketcustomfield->cust_id = $guest->id;
                    $ticketcustomfield->fieldnames = $customfield->fieldnames;
                    $ticketcustomfield->fieldtypes = $customfield->fieldtypes;
                    if($customfield->fieldtypes == 'checkbox'){
                        if($request->input('custom_'.$customfield->id) != null){

                            $string = implode(',', $request->input('custom_'.$customfield->id));
                            $ticketcustomfield->values = $string;
                        }

                    }
                    if($customfield->fieldtypes != 'checkbox'){
                        if($customfield->fieldprivacy == '1'){
                            $ticketcustomfield->privacymode  = $customfield->fieldprivacy;
                            $ticketcustomfield->values = encrypt($request->input('custom_'.$customfield->id));
                        }else{

                            $ticketcustomfield->values = $request->input('custom_'.$customfield->id);
                        }
                    }
                    $ticketcustomfield->save();

                }
                $verifyData = [

                    'username' => $guest->username,
                    'email' => $guest->email,
                    'email_verify_url' => route('verify.email',$verifyUser->token),

                ];

                try{

                    Mail::to($guest->email)
                    ->send( new mailmailablesend( 'customer_sendmail_verification', $verifyData ) );


                }catch(\Exception $e){

                    return Response::json(['success' => '1']);
                }


                return Response::json(['success' => '1']);

            }
            else{
                if(setting('CAPTCHATYPE') == 'off'){
                    $validator = Validator::make($request->all(), [
                        'firstname' => 'required|max:255',
                        'lastname' => 'required|max:255',
                        'email' => 'required|email|max:255|indisposable|unique:customers',
                        'password' => 'required|min:8|confirmed',
                        'agree_terms' =>  'required|in:agreed',

                    ]);
                }else{
                    if(setting('CAPTCHATYPE') == 'manual'){
                        if(setting('RECAPTCH_ENABLE_REGISTER')=='yes'){
                            $validator = Validator::make($request->all(), [
                                'firstname' => 'required|max:255',
                                'lastname' => 'required|max:255',
                                'email' => 'required|email|max:255|indisposable|unique:customers',
                                'password' => 'required|min:8|confirmed',
                                'agree_terms' =>  'required|in:agreed',
                                'captcha' => ['required', 'captcha'],
                            ]);


                        }else{
                            $validator = Validator::make($request->all(), [
                                'firstname' => 'required|max:255',
                                'lastname' => 'required|max:255',
                                'email' => 'required|email|max:255|indisposable|unique:customers',
                                'password' => 'required|min:8|confirmed',
                                'agree_terms' =>  'required|in:agreed',

                            ]);

                        }

                    }
                    if(setting('CAPTCHATYPE') == 'google'){
                        if(setting('RECAPTCH_ENABLE_REGISTER')=='yes'){
                            $validator = Validator::make($request->all(), [
                                'firstname' => 'required|max:255',
                                'lastname' => 'required|max:255',
                                'email' => 'required|email|max:255|indisposable|unique:customers',
                                'password' => 'required|min:8|confirmed',
                                'agree_terms' =>  'required|in:agreed',
                                'g-recaptcha-response'  =>  'recaptcha',
                            ]);

                        }else{
                            $validator = Validator::make($request->all(), [
                                'firstname' => 'required|max:255',
                                'lastname' => 'required|max:255',
                                'email' => 'required|email|max:255|indisposable|unique:customers',
                                'password' => 'required|min:8|confirmed',
                                'agree_terms' =>  'required|in:agreed',

                            ]);
                        }
                    }
                }


                if ($validator->passes()) {

                    // Store your user in database
                    $geolocation = GeoIP::getLocation(request()->getClientIp());
                        $user =  Customer::create([
                            'firstname' => $request->input('firstname'),
                            'lastname' => $request->input('lastname'),
                            'username' => $request->input('firstname') .' '. $request->input('lastname'),
                            'email' => $request->email,
                            'password' => Hash::make($request->password),
                            'userType' => 'Customer',
                            'country' => $geolocation->country,
                            'timezone' => $geolocation->timezone,
                            'status' => '1',
                            'image' => null,

                        ]);

                        $customersetting = new CustomerSetting();
                        $customersetting->custs_id = $user->id;
                        $customersetting->darkmode = setting('DARK_MODE');
                        $customersetting->save();

                $verifyUser = VerifyUser::create([
                    'cust_id' => $user->id,
                    'token' => sha1(time())
                ]);

                $customfields = Customfield::whereIn('displaytypes', ['both', 'registerform'])->get();

                foreach($customfields as $customfield)
                {
                    $ticketcustomfield = new TicketCustomfield();
                    $ticketcustomfield->cust_id = $user->id;
                    $ticketcustomfield->fieldnames = $customfield->fieldnames;
                    $ticketcustomfield->fieldtypes = $customfield->fieldtypes;
                    if($customfield->fieldtypes == 'checkbox'){
                        if($request->input('custom_'.$customfield->id) != null){

                            $string = implode(',', $request->input('custom_'.$customfield->id));
                            $ticketcustomfield->values = $string;
                        }

                    }
                    if($customfield->fieldtypes != 'checkbox'){
                        if($customfield->fieldprivacy == '1'){
                            $ticketcustomfield->privacymode  = $customfield->fieldprivacy;
                            $ticketcustomfield->values = encrypt($request->input('custom_'.$customfield->id));
                        }else{

                            $ticketcustomfield->values = $request->input('custom_'.$customfield->id);
                        }
                    }
                    $ticketcustomfield->save();

                }

                $verifyData = [

                    'username' => $user->username,
                    'email' => $user->email,
                    'email_verify_url' => route('verify.email',$verifyUser->token),

                ];

                try{

                    Mail::to($user->email)
                    ->send( new mailmailablesend( 'customer_sendmail_verification', $verifyData ) );


                }catch(\Exception $e){

                    return Response::json(['success' => '1']);
                }

                    return Response::json(['success' => '1']);

                }

                return Response::json(['errors' => $validator->errors()]);
            }
        }
        else{

            if(setting('CAPTCHATYPE') == 'off'){
                $validator = Validator::make($request->all(), [
                    'firstname' => 'required|',
                    'lastname' => 'required|',
                    'email' => 'required|email|max:255|indisposable|unique:customers',
                    'password' => 'required|min:8|confirmed',
                    'agree_terms' =>  'required|in:agreed',

                ]);
            }else{
                if(setting('CAPTCHATYPE') == 'manual'){
                    if(setting('RECAPTCH_ENABLE_REGISTER')=='yes'){
                        $validator = Validator::make($request->all(), [
                            'firstname' => 'required|max:255',
                            'lastname' => 'required|max:255',
                            'email' => 'required|email|max:255|indisposable|unique:customers',
                            'password' => 'required|min:8|confirmed',
                            'agree_terms' =>  'required|in:agreed',
                            'captcha' => ['required', 'captcha'],
                        ]);


                    }else{
                        $validator = Validator::make($request->all(), [
                            'firstname' => 'required|max:255',
                            'lastname' => 'required|max:255',
                            'email' => 'required|email|max:255|indisposable|unique:customers',
                            'password' => 'required|min:8|confirmed',
                            'agree_terms' =>  'required|in:agreed',

                        ]);

                    }

                }
                if(setting('CAPTCHATYPE') == 'google'){
                    if(setting('RECAPTCH_ENABLE_REGISTER')=='yes'){
                        $validator = Validator::make($request->all(), [
                            'firstname' => 'required|max:255',
                            'lastname' => 'required|max:255',
                            'email' => 'required|email|max:255|indisposable|unique:customers',
                            'password' => 'required|min:8|confirmed',
                            'agree_terms' =>  'required|in:agreed',
                            'g-recaptcha-response'  =>  'required|recaptcha',
                        ]);

                    }else{
                        $validator = Validator::make($request->all(), [
                            'firstname' => 'required|max:255',
                            'lastname' => 'required|max:255',
                            'email' => 'required|email|max:255|indisposable|unique:customers',
                            'password' => 'required|min:8|confirmed',
                            'agree_terms' =>  'required|in:agreed',

                        ]);
                    }
                }
            }


            if ($validator->passes()) {

                // Store your user in database
                $geolocation = GeoIP::getLocation(request()->getClientIp());
                $user =  Customer::create([
                    'firstname' => $request->input('firstname'),
                    'lastname' => $request->input('lastname'),
                    'username' => $request->input('firstname') .' '. $request->input('lastname'),
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'userType' => 'Customer',
                    'country' => $geolocation->country,
                    'timezone' => $geolocation->timezone,
                    'status' => '1',
                    'image' => null,

                ]);

                $customersetting = new CustomerSetting();
                $customersetting->custs_id = $user->id;
                $customersetting->darkmode = setting('DARK_MODE');
                $customersetting->save();

                $verifyUser = VerifyUser::create([
                    'cust_id' => $user->id,
                    'token' => sha1(time())
                ]);

                $customfields = Customfield::whereIn('displaytypes', ['both', 'registerform'])->get();

                foreach($customfields as $customfield)
                {
                    $ticketcustomfield = new TicketCustomfield();
                    $ticketcustomfield->cust_id = $user->id;
                    $ticketcustomfield->fieldnames = $customfield->fieldnames;
                    $ticketcustomfield->fieldtypes = $customfield->fieldtypes;
                    if($customfield->fieldtypes == 'checkbox'){
                        if($request->input('custom_'.$customfield->id) != null){

                            $string = implode(',', $request->input('custom_'.$customfield->id));
                            $ticketcustomfield->values = $string;
                        }

                    }
                    if($customfield->fieldtypes != 'checkbox'){
                        if($customfield->fieldprivacy == '1'){
                            $ticketcustomfield->privacymode  = $customfield->fieldprivacy;
                            $ticketcustomfield->values = encrypt($request->input('custom_'.$customfield->id));
                        }else{

                            $ticketcustomfield->values = $request->input('custom_'.$customfield->id);
                        }
                    }
                    $ticketcustomfield->save();

                }

                $verifyData = [

                    'username' => $user->username,
                    'email' => $user->email,
                    'email_verify_url' => route('verify.email',$verifyUser->token),

                ];

                try{

                    Mail::to($user->email)
                    ->send( new mailmailablesend( 'customer_sendmail_verification', $verifyData ) );


                }catch(\Exception $e){

                    return Response::json(['success' => '1']);
                }

                    return Response::json(['success' => '1']);

                }

                return Response::json(['errors' => $validator->errors()]);
            }
    }

    public function verifyUser($token)
    {
        $verifyUser = VerifyUser::where('token', $token)->first();
        if($verifyUser != null && $verifyUser->user != null){
            if(isset($verifyUser) ){
                $user = $verifyUser->user;
                if(!$user->verified) {
                    $verifyUser->user->verified = 1;
                    $verifyUser->user->save();
                    $status = lang('Your e-mail has been verified. You can now login.', 'alerts');
                } else {
                    $status = lang('Your e-mail has already been verified. You can now login.', 'alerts');
                }
            } else {
                return redirect()->route('auth.login')->with('warning', lang('Sorry, your email could not be determined.', 'alerts'));
            }
            if(setting('REGISTER_POPUP') == 'yes'){
                return redirect('/')->with('success', $status);
            }else{
                return redirect()->route('auth.login')->with('success', $status);
            }
        }else {
            return redirect()->route('auth.login')->with('warning', lang('Sorry, your email could not be determined.', 'alerts'));
        }

    }
}
