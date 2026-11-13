<?php

namespace Modules\Uhelpupdate\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Nwidart\Modules\Routing\Controller;

use App\Models\Apptitle;
use App\Models\User;
use App\Models\Footertext;
use App\Models\Seosetting;
use App\Models\Pages;
use Modules\Uhelpupdate\Entities\APIData;
use App\Helper\Installer\trait\ApichecktraitHelper;
use Illuminate\Support\Facades\Http;
use App\Models\Setting;

class EnvatoAppinfoController extends Controller
{
    use ApichecktraitHelper;
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {

        $this->authorize('App Purchase Code Access');
        $title = Apptitle::first();
        $data['title'] = $title;

        $footertext = Footertext::first();
        $data['footertext'] = $footertext;

        $seopage = Seosetting::first();
        $data['seopage'] = $seopage;

        $post = Pages::all();
        $data['page'] = $post;

        $apidata = APIData::first();
        $data['apidata'] = $apidata;

        // Check purchase code
        $output = '';
        $purchaseCodeData = $this->verifyupdatechecker(setting('update_setting'));
        if ($purchaseCodeData->valid == false) {
            $output .= '
                    <div class="card">
                        <div class="card-header border-0">
                            <h4 class="card-title">'.lang('App Purchase Code').'</h4>
                        </div>
                        <div class="card-body">
                            <form action="'.url('administration/licenseinfoenter/').'" method="POST"  enctype="multipart/form-data" >
                            '.csrf_field().'
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-xl-6 col-lg-6 col-md-6">
                                            <input type="text" class="form-control" name="envato_id" >
                                        </div>
                                        <div class="col-xl-2 col-lg-2 col-md-2">
                                            <button type="submit" class="btn btn-success">'.lang('Submit Purchase Code').'</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div
                    </div>
                ';
        }
        if ($purchaseCodeData->valid == true) {
            $checkapis = $this->updatesettingapi(setting('update_setting'));

            // Format object data
            $result = json_decode($checkapis);

            if($result != null){
                $output .= '
                    <div class="card">
                        <div class="card-header border-0">
                            <h4 class="card-title">'.lang('App Purchase Code').'</h4>
                            <div class="card-options">
                                <span class="badge badge-gray">Version: V.3.1.2</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="'.url('administration/licenseinfo/'.$result->id).'" method="POST"  enctype="multipart/form-data" >
                            '.csrf_field().'
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-xl-6 col-lg-6 col-md-6">
                                            <input type="text" class="form-control" name="envato_search" value="'.str_pad(substr($result->purchaseCode, -4), strlen($result->purchaseCode), '*', STR_PAD_LEFT).'" readonly>
                                            <input type="hidden" class="form-control" name="envato_id" value="'.encrypt($result->purchaseCode).'">
                                        </div>

                                    </div>
                                </div>
                            </form>
                            <table class="table table-striped table-bordered">
                                <tbody>
                                    <tr>
                                        <td class="w-30"><b>App License:</b></td>
                                        <td>'.$result->license.'</td>
                                    </tr>
                                    <tr>
                                        <td class="w-30"><b>Application Url:</b></td>
                                        <td>'.$result->url.'</td>
                                    </tr>
                                    <tr>
                                        <td class="w-30"><b>Author:</b></td>
                                        <td>'.$result->author.'</td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                ';
            }
            if($result == null){
                $output .= '
                    <div class="card">
                        <div class="card-header border-0">
                            <h4 class="card-title">'.lang('App Purchase Code').'</h4>
                        </div>
                        <div class="card-body">
                            <form action="'.url('administration/licenseinfoenter/').'" method="POST"  enctype="multipart/form-data" >
                            '.csrf_field().'
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-xl-6 col-lg-6 col-md-6">
                                            <input type="text" class="form-control" name="envato_id" >
                                        </div>
                                        <div class="col-xl-2 col-lg-2 col-md-2">
                                            <button type="submit" class="btn btn-success">'.lang('Submit Purchase Code').'</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div
                    </div>
                ';
            }



        }
        return view('uhelpupdate::appinfo.index', compact('output'))->with($data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('uhelpupdate::create');
    }

    public function testinginfo()
    {
        return view('installer.newupdate.testinginfo');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        // Check purchase code
        $this->validate($request, [
            'envato_id' => 'required'
        ]);
        $user = User::first();
        $purchaseCodeData = $this->verifysettingcreate($request->envato_id, $user->firstname, $user->lastname, $user->email);

        if ($purchaseCodeData->App == 'invalid') {
            return redirect()->back()->with('error', $purchaseCodeData->message);
        }
        if ($purchaseCodeData->App == 'old') {
            return redirect()->back()->with('error', $purchaseCodeData->message);
        }
        if ($purchaseCodeData->App == 'New') {

            if (Setting::where("key", "newupdate")->first()) {
                $uset = Setting::where("key", "newupdate")->first();
                $uset->value = 'updated3.1.2';
                $uset->save();
            } else {
                $uset = new Setting();
                $uset->key = 'newupdate';
                $uset->value = 'updated3.1.2';
                $uset->save();
            }

            if (Setting::where("key", "mail_key_set")->first()) {
                $usermailkey = Setting::where("key", "mail_key_set")->first();
                $usermailkey->value = $purchaseCodeData->mail_key;
                $usermailkey->save();
            } else {
                $uset = new Setting();
                $uset->key = 'mail_key_set';
                $uset->value = $purchaseCodeData->mail_key;
                $uset->save();
            }

            if ($request->envato_id) {
                $data['update_setting'] = $request->envato_id;
                $this->updateSettings($data);
            }

            return redirect()->back()->with('success', 'Updated Successfully');
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('uhelpupdate::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('uhelpupdate::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }

    public function envatogetdetails($id)
    {
		//
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
