<?php

namespace Modules\Uhelpupdate\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
// use Illuminate\Routing\Controller;
use Nwidart\Modules\Routing\Controller;

use App\Models\Apptitle;
use App\Models\Footertext;
use App\Models\Seosetting;
use App\Models\Pages;
use Modules\Uhelpupdate\Entities\Cannedmessages;
use DataTables;
use Auth;


class CannedmessagesController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $this->authorize('Canned Response Access');
        $title = Apptitle::first();
        $data['title'] = $title;

        $footertext = Footertext::first();
        $data['footertext'] = $footertext;

        $seopage = Seosetting::first();
        $data['seopage'] = $seopage;

        $post = Pages::all();
        $data['page'] = $post;

        $cannedmessages = Cannedmessages::latest()->get();
        $data['cannedmessages'] = $cannedmessages;

        return view('uhelpupdate::cannedmessages.index')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $this->authorize('Canned Response Create');
        $title = Apptitle::first();
        $data['title'] = $title;

        $footertext = Footertext::first();
        $data['footertext'] = $footertext;

        $seopage = Seosetting::first();
        $data['seopage'] = $seopage;

        $post = Pages::all();
        $data['page'] = $post;

        return view('uhelpupdate::cannedmessages.create')->with($data);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $request->validate([

            'title'=> 'required|max:255',
            'message' => 'required',

        ]);

        Cannedmessages::create([
            'title' => $request->title,
            'messages' => $request->message,
            'status' => $request->statuscanned ? 1 : 0,
        ]);
        return redirect()->route('admin.cannedmessages')->with('success', lang('Update Successfully'));
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
        $this->authorize('Canned Response Edit');
        $title = Apptitle::first();
        $data['title'] = $title;

        $footertext = Footertext::first();
        $data['footertext'] = $footertext;

        $seopage = Seosetting::first();
        $data['seopage'] = $seopage;

        $post = Pages::all();
        $data['page'] = $post;

        $cannedmessages = Cannedmessages::findOrFail($id);
        $data['cannedmessage'] = $cannedmessages;

        return view('uhelpupdate::cannedmessages.edit')->with($data);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $cannedmessages = Cannedmessages::findOrFail($id);
        $cannedmessages->title = $request->title;
        $cannedmessages->messages = $request->message;
        $cannedmessages->status = $request->statuscanned ? 1 : 0;
        $cannedmessages->update();

       return redirect()->route('admin.cannedmessages')->with('success', lang('Update Successfully'));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $this->authorize('Canned Response Delete');
        $cannedmessages = Cannedmessages::findOrFail($id);
        $cannedmessages->delete();
        return response()->json(['success'=> lang('Canned Response Deleted Successfully')]);
    }

    /// Status changing method
    public function status(Request $request)
    {
        $cannedmessages = Cannedmessages::findOrFail($request->id);
        $cannedmessages->status = $request->status;
        $cannedmessages->update();
        return response()->json(['code'=>200, 'success'=>lang('Update Successfully')], 200);
    }

    // Delete Selected Canned Messages
    public function destroyall(Request $request)
    {
        $id_array = $request->input('id');
        $cannedmessages = Cannedmessages::findOrFail($id_array);
        foreach($cannedmessages as $cannedmessage){

            $cannedmessage->delete();
        }
        return response()->json(['success'=> lang('Canned Response Deleted Successfully')]);
    }
}
