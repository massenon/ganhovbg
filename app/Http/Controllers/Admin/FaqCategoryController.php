<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Apptitle;
use App\Models\Footertext;
use App\Models\Seosetting;
use App\Models\Pages;
use App\Models\FaqCategory;

class FaqCategoryController extends Controller
{
    public function index()
    {


        $basic = Apptitle::first();
        $data['basic'] = $basic;

        $title = Apptitle::first();
        $data['title'] = $title;

        $footertext = Footertext::first();
        $data['footertext'] = $footertext;

        $seopage = Seosetting::first();
        $data['seopage'] = $seopage;

        $post = Pages::all();
        $data['page'] = $post;

        $faqcategory = FaqCategory::latest()->get();
        $data['faqcategorys'] = $faqcategory;

        return view('admin.faq.faqcategoryindex')->with($data);

    }

    public function storeupdate(Request $request)
    {
        $validate = FaqCategory::find($request->faqcat_id);
        if(!$validate){
            $request->validate([
                'faqcategoryname'=> 'required|max:255|unique:faq_categories',

            ]);
        }
        if($validate){
            if($request->faqcategoryname == $validate->faqcategoryname){
                $request->validate([
                    'faqcategoryname'=> 'required|max:255',

                ]);
            }else{
                $request->validate([
                    'faqcategoryname'=> 'required|max:255|unique:faq_categories',

                ]);
            }
        }
        $faqcategory_id = $request->faqcat_id;
                $faqcategory_data =  [
                    'faqcategoryname' => $request->faqcategoryname,
                    'status' => $request->status ? '1' : '0',
                ];
        $faqcategory = FaqCategory::updateOrCreate(['id' => $faqcategory_id], $faqcategory_data);

        return response()->json(['code'=>200, 'success'=> lang('Updated Successfully', 'alerts'),'data' => $faqcategory], 200);
    }

    public function faqcategorylist(Request $request)
    {
        $faqcategorylist = FaqCategory::get();

        $faqcatlist = '';

        $faqcatlist .= '<option label ="Select Faq Category"></option>';
        foreach($faqcategorylist as $faqcategorylists)
        {
            $faqcatlist .= '<option value='.$faqcategorylists->id.'>'.$faqcategorylists->faqcategoryname.'</option>';
        }

        return response()->json($faqcatlist, 200);

    }

    public function edit($id)
    {

        $faqsubcatlist = FaqCategory::find($id);
        return response()->json($faqsubcatlist);

    }

    public function destroy($id)
    {

        $faqsubcatdelete = FaqCategory::find($id);
        $faqsubcatdelete->delete();
        return response()->json(['success' => lang('Deleted Successfully', 'alerts')], 200);

    }

    public function allfaqcategorydelete(Request $request)
    {
        $id_array = $request->input('id');

        $allfaqcategorydeletes = FaqCategory::whereIn('id', $id_array)->get();

        foreach($allfaqcategorydeletes as $allfaqcategorydelete){
            $allfaqcategorydelete->delete();

        }
        return response()->json(['success'=> lang('Deleted Successfully', 'alerts')]);
    }


    public function status(Request $request, $id)
    {
        $calID = FaqCategory::find($id);
        $calID->status = $request->status;
        $calID->save();

        return response()->json(['code'=>200, 'success'=>lang('Updated Successfully', 'alerts')], 200);

    }
}
