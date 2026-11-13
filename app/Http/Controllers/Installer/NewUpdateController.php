<?php

namespace App\Http\Controllers\Installer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Apptitle;
use App\Models\Footertext;
use App\Models\Seosetting;
use App\Models\Setting;
use App\Models\Pages;
use App\Helper\Installer\trait\ApichecktraitHelper;

class NewUpdateController extends Controller
{
    use ApichecktraitHelper;

    public function newupdatelink(Request $request)
    {
        if(regularData()){
            return redirect()->route('home')->with('success',lang('You are up to date', 'alerts'));
        }else{
            $title = Apptitle::first();
            $data['title'] = $title;

            $footertext = Footertext::first();
            $data['footertext'] = $footertext;

            $seopage = Seosetting::first();
            $data['seopage'] = $seopage;

            if(setting('newupdate') == 'updated3.1'){
                $version = '3.1.2';
                $data['version'] = $version;
            }elseif (represent()){
                $version = '3.1';
                $data['version'] = $version;
            }else{
                $version = '3.0';
                $data['version'] = $version;
            }

            return view('installer.newupdate.newupdate')->with($data);
        }
    }

    public function checking(Request $request)
    {
        if(regularData()){
            return redirect()->route('home')->with('success',lang('You are up to date', 'alerts'));
        }else{
            $this->validate($request, [
                'app_firstname' => 'required',
                'app_lastname' => 'required',
                'app_email' => 'required',
                'envato_purchasecode' => 'required'
            ]);

            $Name = $request->app_firstname . ' ' . $request->app_lastname;
            $verifyedData = $this->verifysettingupdate($request->envato_purchasecode, $Name, $request->app_email);
            if ($verifyedData->valid == false) {
                return redirect()->back()->with('error', $verifyedData->message);
            }

            if ($verifyedData->valid == true) {
                if ($verifyedData->App == 'update') {
                    if(setting('newupdate') == 'updated3.1'){
                        $userset = Setting::where('key','newupdate')->first();
                        $userset->value = 'updated3.1.2';
                        $userset->update();
                    }elseif (represent()){
                        $userset = Setting::where('key','newupdate')->first();
                        $userset->value = 'version3.1';
                        $userset->update();
                    }else{
                        $user = new Setting;
                        $user->key = 'newupdate';
                        $user->value = 'version3.0';
                        $user->save();
                    }

                    return redirect()->route('data.seeding');
                }
            }
        }
    }

    public function thirdupdate($token)
    {
        if(regularData()){
            return redirect()->route('home')->with('success',lang('You are up to date', 'alerts'));
        }else{
            $title = Apptitle::first();
            $data['title'] = $title;

            $footertext = Footertext::first();
            $data['footertext'] = $footertext;

            $seopage = Seosetting::first();
            $data['seopage'] = $seopage;

            $post = Pages::all();
            $data['page'] = $post;

            $data['version'] = $token;

            return view('installer.newupdate.thirdupdate')->with($data);
        }
    }

    public function seeding()
    {
        if(regularData()){
            return redirect()->route('home')->with('success',lang('You are up to date', 'alerts'));
        }else{
            $title = Apptitle::first();
            $data['title'] = $title;

            $footertext = Footertext::first();
            $data['footertext'] = $footertext;

            $seopage = Seosetting::first();
            $data['seopage'] = $seopage;

            if(setting('newupdate') == 'updated3.1'){
                $version = '3.1.2';
                $data['version'] = $version;
            }elseif (represent()){
                $version = '3.1';
                $data['version'] = $version;
            }else{
                $version = '3.0';
                $data['version'] = $version;
            }

            return view('installer.newupdate.seeding')->with($data);
        }
    }
}
