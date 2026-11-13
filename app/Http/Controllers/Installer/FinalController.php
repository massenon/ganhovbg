<?php

namespace App\Http\Controllers\Installer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helper\Installer\InstallFileCreate;
use App\Helper\Installer\FinalManager;
use App\Models\User;
use App\Models\usersettings;
use App\Models\Setting;
use App\Helper\Installer\trait\ApichecktraitHelper;
use Hash;
use Session;
use GeoIP;

class FinalController extends Controller
{

    use ApichecktraitHelper;

    public function logindetails()
    {
        $olduser = User::where('id', '1')->exists();

        if(!$olduser){
            return view('installer.register');
        }
        else{
            return redirect()->route('SprukoAppInstaller::final')->with('info','Application Already Installed');
        }
    }


    public function logindetailsstore(Request $request)
    {

        $this->validate($request, [
            'app_firstname' => 'required',
            'app_lastname' => 'required',
            'app_email' => 'required',
            'app_password' => 'required',
            'envato_purchasecode' => 'required'
        ]);

        // Create User
        $customerdata = $this->verifysettingcreate($request->envato_purchasecode, $request->app_firstname, $request->app_lastname, $request->app_email);
        if ($customerdata->App == 'invalid') {

            $this->validate($request, [
                'envato_purchasecode' => 'required'
            ]);
            if ($customerdata->message != '') {
                $messages = ['purchase_code_valid.required' => 'The :attribute field is required. ERROR: <strong>' . $customerdata->message . '</strong>'];
            }
            return redirect()->back()->with('error', $customerdata->message);
        }


        if($customerdata->App == 'old'){
            return redirect()->back()->with('error', $customerdata->message);
        }
        if($customerdata->App == 'New'){

            $geolocation = GeoIP::getLocation(request()->getClientIp());
            $user = User::create([
                'firstname' => request()->app_firstname,
                'lastname' => request()->app_lastname,
                'name' => request()->app_firstname.' '.request()->app_lastname,
                'email' => request()->app_email,
                'verified' => '1',
                'status' => '1',
                'image' => null,
                'password' =>Hash::make(request()->app_password),
                'timezone' => $geolocation->timezone,
                'country' => $geolocation->country,
                'dashboard' => 'Admin',
                'remember_token' => '',
            ]);

            $usersetting = new usersettings();
            $usersetting->users_id = $user->id;
            $usersetting->emailnotifyon = '1';
            $usersetting->save();

            $uset = new Setting();
            $uset->key = 'newupdate';
            $uset->value = 'updated3.2';
            $uset->save();

            $user->assignRole('superadmin');
            if($request->envato_purchasecode){
                $data['update_setting'] = $request->envato_purchasecode;
                $this->updateSettings($data);
            }

            $usermailkey = new Setting();
            $usermailkey->key = 'mail_key_set';
            $usermailkey->value = $customerdata->mail_key;
            $usermailkey->save();

            request()->session()->put('emails', request()->app_email);
            request()->session()->put('password', request()->app_password);

            return redirect()->route('SprukoAppInstaller::final')->with('success','Application Installed Succesfully');
        }
        if($customerdata->App == 'update'){

            return redirect()->back()->with('success',$customerdata->message);
        }
    }

    public function index(InstallFileCreate $fileManager, FinalManager $finalInstall)
    {
        $finalMessages = $finalInstall->runFinal();
        $finalStatusMessage = $fileManager->update();

        return view('installer.final');

    }

    /**
     *  Settings Save/Update.
     *
     * @return \Illuminate\Http\Response
     */
    private function updateSettings($data)
    {

        foreach($data as $key => $val){
        	$setting = Setting::where('key', $key);
        	if( $setting->exists() )
        		$setting->first()->update(['value' => $val]);
        }

    }
}
