<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DataTables;
use App\Models\Apptitle;
use App\Models\Footertext;
use App\Models\Seosetting;
use App\Models\Pages;
use App\Models\SocialAuthSetting;
use Auth;
use Str;

class GeneralPageController extends Controller
{
	public function index(){
		$this->authorize('Pages Access');

		$title = Apptitle::first();
		$data['title'] = $title;

		$footertext = Footertext::first();
		$data['footertext'] = $footertext;

		$seopage = Seosetting::first();
		$data['seopage'] = $seopage;

		$post = Pages::all();
		$data['page'] = $post;



		return view('admin.generalpage.index')->with($data);
	}

    public function createpage()
    {
        $this->authorize('Pages Access');

        $title = Apptitle::first();
        $data['title'] = $title;

        $footertext = Footertext::first();
        $data['footertext'] = $footertext;

        $seopage = Seosetting::first();
        $data['seopage'] = $seopage;

        return view('admin.generalpage.createpage')->with($data);
    }


	public function store(Request $request)
    {
        $testiId = $request->pages_id;
        $pagesfind = Pages::find($testiId);

        if(!$pagesfind){
            $request->validate([
                'pagename'=> 'required|max:255|unique:pages',
                'pagedescription' => 'required',
            ]);
        }
        if($pagesfind){
            if($pagesfind->pagename == $request->pagename){
                $request->validate([
                    'pagename'=> 'required|max:255',
                    'pagedescription' => 'required',
                ]);
            }
            else{
                $request->validate([
                    'pagename'=> 'required|max:255|unique:pages',
                    'pagedescription' => 'required',
                ]);
            }

        }

        if($request->display == null){
            return back()->with('error', lang('Please select the view on header/footer/both.', 'alerts'));
        }


        if(!$pagesfind){
            $testi =  [
                'pagename' => $request->pagename,
                'pagedescription' => $request->pagedescription,
                'pageslug' => Str::slug($request->pagename, '-'),
                'viewonpages' => $request->display,
                'status' => $request->status ? 1 : 0,
            ];
        }

        if($pagesfind){

            if($pagesfind->pageslug != null){
                $testi =  [
                    'pagename' => $request->pagename,
                    'pagedescription' => $request->pagedescription,
                    'viewonpages' => $request->display,
                    'status' => $request->status ? 1 : 0,
                ];
            }
            if($pagesfind->pageslug == null){
                $testi =  [
                    'pagename' => $request->pagename,
                    'pagedescription' => $request->pagedescription,
                    'pageslug' => Str::slug($request->pagename, '-'),
                    'viewonpages' => $request->display,
                    'status' => $request->status ? 1 : 0,
                ];
            }
        }
        $pages = Pages::updateOrCreate(['id' => $testiId], $testi);

        return redirect()->route('pages.index')->with('success', lang('The Page has been successfully updated.', 'alerts'));

    }

    public function show($id)
    {
        $this->authorize('Pages Edit');

        $title = Apptitle::first();
        $data['title'] = $title;

        $footertext = Footertext::first();
        $data['footertext'] = $footertext;

        $seopage = Seosetting::first();
        $data['seopage'] = $seopage;

        $page = Pages::find($id);
        $data['page'] = $page;

        return view('admin.generalpage.editpage')->with($data);
    }

	/**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('Category Delete');
        $pages = Pages::findOrFail($id);
        $pages->delete();


        return response()->json(['success'=> lang('Deleted Successfully', 'alerts')]);
    }
	public function destroyall(Request $request)
    {
        $this->authorize('pages Delete');

        $id_array = $request->input('id');

        $pages = pages::whereIn('id', $id_array)->get();

        foreach($pages as $page)
        {
            $page->delete();

        }
        return response()->json(['success'=> lang('Deleted successfully', 'alerts')]);
    }
}
