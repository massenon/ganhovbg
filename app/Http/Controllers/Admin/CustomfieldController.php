<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Apptitle;
use App\Models\Footertext;
use App\Models\Seosetting;
use App\Models\Pages;
use App\Models\Customfield;

class CustomfieldController extends Controller
{
    public function index()
    {

        $this->authorize('CustomField Access');

        $title = Apptitle::first();
        $data['title'] = $title;

        $footertext = Footertext::first();
        $data['footertext'] = $footertext;

        $seopage = Seosetting::first();
        $data['seopage'] = $seopage;

        $post = Pages::all();
        $data['page'] = $post;

        $customfields = Customfield::all();
        $data['customfields'] = $customfields;

        return view('admin.customfield.index')->with($data);

    }

    public function storeupdate(Request $request)
    {
        $request->validate(
            [
            'sprukofieldname'=> 'required',
            'display'=> 'required',

            ],
            [

            'sprukofieldname.required'=> 'Your Label Field Name is Required', // custom message
            'display.required' => 'Your View On Field is Required'
            ]

        );


        $customfieldId = $request->customfieldopen_id;
        $customfield =  [
            'fieldtypes' => $request->sprukofieldtype,
            'fieldnames' => $request->sprukofieldname,
            'fieldprivacy' => $request->privacyfields,
            'fieldrequired' => $request->requiredfields,
            'status' => $request->status,
            'fieldoptions' => $request->optionsfields,
            'displaytypes' => $request->display,

        ];

        $custom = Customfield::updateOrCreate(['id' => $customfieldId], $customfield);

        return response()->json(['success' => lang('Updated successfully', 'alerts')], 200,);
    }

    public function edit($id)
    {
        $this->authorize('CustomField Edit');

        $customfieldfind = Customfield::findOrFail($id);

        return response()->json($customfieldfind, 200);
    }

    public function destroy($id)
    {
        $this->authorize('CustomField Delete');

        $customfielddelete = Customfield::findOrFail($id);
        $customfielddelete->delete();
        return response()->json(['success' => lang('Deleted successfully', 'alerts')], 200);
    }

    public function destroyall(Request $request)
    {
        $this->authorize('CustomField Delete');

        $id_array = $request->input('id');

        $customfields = Customfield::whereIn('id', $id_array)->get();

        foreach($customfields as $customfield)
        {
            $customfield->delete();

        }
        return response()->json(['success'=> lang('Deleted successfully', 'alerts')]);
    }

    public function status(Request $request, $id)
    {
        $this->authorize('CustomField Edit');

        $customfieldfind = Customfield::find($id);
        $customfieldfind->status = $request->status;
        $customfieldfind->save();

        return response()->json(['code'=>200, 'success'=>lang('Updated Successfully', 'alerts')], 200);

    }
}
