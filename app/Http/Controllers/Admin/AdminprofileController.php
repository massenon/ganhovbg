<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Auth;
use App\Models\User;
use App\Models\usersettings;
use App\Models\Employeerating;
use App\Models\Customer;
use App\Models\Countries;
use App\Models\Timezone;
use App\Models\Apptitle;
use App\Models\Footertext;
use App\Models\Seosetting;
use App\Models\Pages;
use Illuminate\Support\Facades\Validator;
use Hash;
use File;
use Image;
use Illuminate\Support\Str;
use Mail;
use App\Mail\mailmailablesend;
use App\Imports\CustomerImport;
use Maatwebsite\Excel\Facades\Excel;
use DB;
use DataTables;
use Session;
use App\Models\VerifyUser;
use App\Models\TicketCustomfield;


class AdminprofileController extends Controller
{
    public function index()
    {

        $user = User::get();
        $data['users'] = $user;

        $country = Countries::all();
        $data['countries'] = $country;

        $timezones = Timezone::get();
        $data['timezones'] = $timezones;

        $title = Apptitle::first();
        $data['title'] = $title;

        $footertext = Footertext::first();
        $data['footertext'] = $footertext;

        $seopage = Seosetting::first();
        $data['seopage'] = $seopage;

        $post = Pages::all();
        $data['page'] = $post;

        if(Auth::check() && Auth::user()->id){
            $avgrating1 = Employeerating::where('user_id', Auth::id())->where('rating', '1')->count();
            $avgrating2 = Employeerating::where('user_id', Auth::id())->where('rating', '2')->count();
            $avgrating3 = Employeerating::where('user_id', Auth::id())->where('rating', '3')->count();
            $avgrating4 = Employeerating::where('user_id', Auth::id())->where('rating', '4')->count();
            $avgrating5 = Employeerating::where('user_id', Auth::id())->where('rating', '5')->count();

            $avgr = ((5*$avgrating5) + (4*$avgrating4) + (3*$avgrating3) + (2*$avgrating2) + (1*$avgrating1));
            $avggr = ($avgrating1 + $avgrating2 + $avgrating3 + $avgrating4 + $avgrating5);

            if($avggr == 0){
                $avggr = 1;
                $avg = $avgr/$avggr;
            }else{
                $avg = $avgr/$avggr;
            }

        }

        return view('admin.profile.adminprofile' ,compact('avg'))-> with($data);


    }


    public function profileedit()
    {
        $this->authorize('Profile Edit');
        $user = User::get();
        $data['users'] = $user;

        $title = Apptitle::first();
        $data['title'] = $title;

        $footertext = Footertext::first();
        $data['footertext'] = $footertext;

        $seopage = Seosetting::first();
        $data['seopage'] = $seopage;

        $post = Pages::all();
        $data['page'] = $post;

        $country = Countries::all();
        $data['countries'] = $country;

        $timezones = Timezone::get();
        $data['timezones'] = $timezones;

        return view('admin.profile.adminprofileupdate')-> with($data);


    }

    public function profilesetup(Request $request)
    {
        $this->authorize('Profile Edit');

        $this->validate($request, [
            'firstname' => 'max:255',
            'lastname' => 'max:255',
        ]);



        $user_id = Auth::user()->id;

        $user = User::findOrFail($user_id);

        $user->firstname = ucfirst($request->input('firstname'));
        $user->lastname = ucfirst($request->input('lastname'));
        $user->name = ucfirst($request->input('firstname')).' '.ucfirst($request->input('lastname'));
        $user->gender = $request->input('gender');
        $user->languagues = implode(', ', $request->input('languages'));
        $user->skills = implode(', ', $request->input('skills'));
        $user->phone = $request->input('phone');
        $user->country = $request->input('country');
        $user->timezone = $request->input('timezone');

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileArray = array('image' => $file);
            $rules = array(
                'image' => 'mimes:jpeg,jpg,png|required|max:5120' // max 10000kb
              );

              // Now pass the input and rules into the validator
              $validator = Validator::make($fileArray, $rules);

              if ($validator->fails())
                {
                    return redirect()->back()->with('error', lang('Please check the format and size of the file.', 'alerts'));
                }else
                {

                    $destination = public_path() . "" . '/uploads/profile';
                    $image_name = time() . '.' . $file->getClientOriginalExtension();
                    $resize_image = Image::make($file->getRealPath());

                    $resize_image->resize(80, 80, function($constraint){
                    $constraint->aspectRatio();
                    })->save($destination . '/' . $image_name);

                    $destinations = public_path() . "" . '/uploads/profile/'.$user->image;
                    if(File::exists($destinations)){
                        File::delete($destinations);
                    }
                    $file = $request->file('image');
                    $user->update(['image'=>$image_name]);
                }


        }


        $user->update();
        return redirect('administration/profile')->with('success', lang('Your profile has been successfully updated.', 'alerts'));

    }

    public function imageremove(Request $request, $id)
    {

        $user = User::findOrFail($id);

        $user->image = null;
        $user->update();

        return response()->json(['success'=> lang('The profile image was successfully removed.', 'alerts')]);

    }


    // Customer function

    public function customers()
    {
        $this->authorize('Customers Access');
        $customer = Customer::get();
        $data['customers'] = $customer;

        $title = Apptitle::first();
        $data['title'] = $title;

        $footertext = Footertext::first();
        $data['footertext'] = $footertext;

        $seopage = Seosetting::first();
        $data['seopage'] = $seopage;

        $post = Pages::all();
        $data['page'] = $post;



        return view('admin.customers.index')->with($data)->with('i', (request()->input('page', 1) - 1) * 5);


    }


    public function resendverification($email)
    {
        $user = Customer::where('email', '=', $email)->first();

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
            return response()->json(['success'=> lang('The email verification link was successfully sent. Please check and verify your email.', 'alerts')]);
        }

        return response()->json(['success'=> lang('The email verification link was successfully sent. Please check and verify your email.', 'alerts')]);
    }


    public function customerscreate()
    {
        $this->authorize('Customers Create');
        $user = Customer::get();
        $data['users'] = $user;

        $title = Apptitle::first();
        $data['title'] = $title;

        $footertext = Footertext::first();
        $data['footertext'] = $footertext;

        $seopage = Seosetting::first();
        $data['seopage'] = $seopage;

        $post = Pages::all();
        $data['page'] = $post;

        $country = Countries::all();
        $data['countries'] = $country;

        $timezones = Timezone::get();
        $data['timezones'] = $timezones;

        return view('admin.customers.create')->with($data)->with('i', (request()->input('page', 1) - 1) * 5);


    }

    public function customersstore(Request $request){
        $this->authorize('Customers Create');
        $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:customers',
            'password' => 'required|string|min:8',
        ]);

        if($request->phone){
            $request->validate([
                'phone' => 'numeric',
            ]);
        }

        $customer = Customer::create([
            'firstname' => Str::ucfirst($request->input('firstname')),
            'lastname' => Str::ucfirst($request->input('lastname')),
            'email' => $request->email,
            'status' => '1',
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'image' => null,
            'verified' => '1',
            'userType' => 'Customer',

        ]);

        $customers = Customer::find($customer->id);
        $customers->username = $customer->firstname.' '.$customer->lastname;
        $customers->update();

        $customerData = [
            'userpassword' => $request->password,
            'username' => $customer->firstname .' '. $customer->lastname,
            'useremail' => $customer->email,
            'url' => url('/'),
        ];

        try{

            Mail::to($customer->email)
            ->send( new mailmailablesend( 'customer_send_registration_details', $customerData ) );

        }catch(\Exception $e){
            return redirect('administration/customer')->with('success', lang('A new customer was successfully added.', 'alerts'));
        }
        return redirect('administration/customer')->with('success', lang('A new customer was successfully added.', 'alerts'));

    }

    public function customersshow($id){
        $this->authorize('Customers Edit');
        $user = Customer::where('id', $id)->first();
        $data['user'] = $user;

        $country = Countries::all();
        $data['countries'] = $country;

        $timezones = Timezone::get();
        $data['timezones'] = $timezones;

        $title = Apptitle::first();
        $data['title'] = $title;

        $footertext = Footertext::first();
        $data['footertext'] = $footertext;

        $seopage = Seosetting::first();
        $data['seopage'] = $seopage;

        $post = Pages::all();
        $data['page'] = $post;

        $customfield = TicketCustomfield::where('cust_id', $id)->get();
        $data['customfield'] = $customfield;

        return view('admin.customers.show')->with($data);

    }

    public function voilating(Request $request, $id)
    {
        $cust = Customer::find($id);
        $cust->voilated = 'on';
        $cust->update();

        return redirect()->back()->with('success', lang('Customer added as a voilated customer.', 'alerts'));
    }

    public function unvoilating(Request $request, $id)
    {
        $cust = Customer::find($id);
        $cust->voilated = null;
        $cust->update();

        return redirect()->back()->with('success', lang('Customer removed from voilated customer.', 'alerts'));
    }

    public function customersupdate(Request $request, $id)
    {
        $this->authorize('Customers Edit');
        $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
        ]);

        if($request->phone){
            $request->validate([
                'phone' => 'numeric',
            ]);
        }
        $user = Customer::where('id', $id)->findOrFail($id);
        $user->firstname = $request->input('firstname');
        $user->lastname = $request->input('lastname');
        $user->username = $request->input('firstname').' '.$request->input('lastname');
        $user->email = $request->input('email');
        $user->country = $request->input('country');
        $user->timezone = $request->input('timezone');
        $user->status = $request->input('status');
        $user->voilated = $request->input('voilated');
        $user->update();
        $request->session()->forget('email',$user->email);

        return redirect('/administration/customer')->with('success', lang('The customer profile was successfully updated.', 'alerts'));

    }

    public function customerimportindex(){

        $title = Apptitle::first();
        $data['title'] = $title;

        $footertext = Footertext::first();
        $data['footertext'] = $footertext;

        $seopage = Seosetting::first();
        $data['seopage'] = $seopage;

        $post = Pages::all();
        $data['page'] = $post;


        return view('admin.customers.customerimport')->with($data);
    }

     /**
    * @return \Illuminate\Support\Collection
    */
    public function customercsv(Request $req)
    {
        if ($req->hasFile('file')) {
            $file = $req->file('file')->store('import');


            $import = Excel::import(new CustomerImport, $file);

            return redirect()->route('admin.customer')->with('success', lang('The Customer list was imported successfully.', 'alerts'));
        }else{
            return redirect()->back()->with('error', 'Please select file to import data of Customer.');
        }
    }





    public function adminLogin(Request $request, $id)
    {
        if($request->session()->get('customerlogin')){
            request()->session()->forget('password_hash_customer');
            request()->session()->forget('customerlogin');
            Auth::guard('customer')->logout();
        }

        $customerExist = Customer::where(['id' => $id, 'status' => 0])->exists();
        if ($customerExist) {
            return redirect()->back()->with('success', lang('The account has been deactivated.', 'alerts'));
        }
        Auth::guard('customer')->loginUsingId($id, true);
        $request->session()->put('customerlogin', $id);
        return redirect()->intended('espace-client');
    }

    public function customersdelete($id){
        $this->authorize('Customers Delete');
        $user = Customer::findOrFail($id);
        $ticket = $user->tickets()->get();

            foreach ($ticket as $tickets) {
                foreach ($tickets->getMedia('ticket') as $media) {
                    $media->delete();
                }
                foreach($tickets->comments as $comment){
                    foreach($comment->getMedia('comments') as $media){
                        $media->delete();
                    }
                    $comment->delete();
                }
            $tickets->delete();
        }
        $user->custsetting()->delete();
        $user->customercustomsetting()->delete();
        $user->delete();

        return response()->json(['success'=> lang('The customer was deleted successfully.', 'alerts')]);
    }


    public function customermassdestroy(Request $request){
        $student_id_array = $request->input('id');

        $customers = Customer::whereIn('id', $student_id_array)->get();

        foreach($customers as $customer){

            foreach ($customer->tickets()->get() as $tickets) {
                foreach ($tickets->getMedia('ticket') as $media) {
                    $media->delete();
                }
                foreach($tickets->comments as $comment){
                    foreach($comment->getMedia('comments') as $media){
                        $media->delete();
                    }
                    $comment->delete();
                }
                $tickets->delete();
            }
            $customer->custsetting()->delete();
            $customer->customercustomsetting()->delete();
            $customer->delete();
        }
        return response()->json(['success'=> lang('The customer was deleted successfully.', 'alerts')]);

    }

    public function usersetting(Request $request)
    {
        $users = User::find($request->user_id);
        if (!$users) {
            return response()->json(['code' => 422, 'error' => 'Utilisateur introuvable.'], 422);
        }
        $users->darkmode = $request->dark;
        $users->update();
        return response()->json(['code'=>200, 'success'=> lang('Updated successfully', 'alerts')], 200);

    }

    public function emailonoff(Request $request)
    {
        $useting = usersettings::where('users_id', $request->userid)->first();

        if($useting == null)
        {
            $usettingcreate = new usersettings();
            $usettingcreate->users_id  = $request->userid;
            $usettingcreate->emailnotifyon = $request->emailvalue;
            $usettingcreate->save();
        }
        else
        {
            $useting->emailnotifyon = $request->emailvalue;
            $useting->update();
        }

        return response()->json(['code'=>200, 'success'=> lang('Updated successfully', 'alerts')], 200);


    }


}
