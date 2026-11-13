<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\FAQ;
use App\Models\Apptitle;
use App\Models\Footertext;
use App\Models\Seosetting;
use App\Models\Pages;
use DataTables;
use Auth;
use Illuminate\Support\Str;
use App\Models\FaqCategory;

class FAQController extends Controller
{
    public function index()
	{

     	$this->authorize('FAQs Access');
        $faq = FAQ::get();
        $data['faqs'] = $faq;

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

        return view('admin.faq.index')->with($data)->with('i', (request()->input('page', 1) - 1) * 5);
    }


    public function faqcreate()
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

        return view('admin.faq.faqcat')->with($data);
    }

    public function store(Request $request)
  	{
        $request->validate([
            'question'=> 'required|max:255',
            'answer' => 'required',
            'faqcatsname' => 'required',
        ]);

        $faq = FAQ::updateOrCreate(
            ['id' => $request->faq_id],
            [	'question' => $request->question,
                'answer' => $request->answer,
                'faqcat_id' => $request->faqcatsname,
                'privatemode' => $request->privatemode == 'on' ? 1 : 0 ,
                'status' => $request->status == 'on' ? 1 : 0
            ]
        );

        return redirect()->route('faq.index')->with('success', lang('The FAQ has been successfully updated.', 'alerts'));

  	}

    public function show($id)
    {
        $this->authorize('FAQs Edit');

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

        $faq = FAQ::find($id);
        $data['faq'] = $faq;

        $faqcategory = FaqCategory::latest()->get();
        $data['faqcategorys'] = $faqcategory;

        return view('admin.faq.faqcatedit')->with($data);
    }

	public function destroy($id)
	{
		$this->authorize('FAQs Delete');
		$faq = FAQ::find($id);
		$faq->delete();

		return response()->json(['success'=>lang('The FAQ has been successfully deleted.', 'alerts')]);
	}


	public function allfaqdelete(Request $request)
	{
		$id_array = $request->input('id');

		$sendmails = FAQ::whereIn('id', $id_array)->get();

		foreach($sendmails as $sendmail){
			$sendmail->delete();

		}
		return response()->json(['success'=> lang('The FAQ has been successfully deleted.', 'alerts')]);

	}

	public function faq(Request $request)
	{
		$request->validate([
			'faqtitle'=> 'required|max:255',
		]);

		if($request->faqsub){
			$request->validate([
				'faqsub' => 'max:255'
			]);
		}
		$calID = ['id' => $request->id];
		$calldetails = [

			'faqtitle'  => $request->faqtitle,
			'faqsub'    => $request->faqsub,
			'faqcheck'  => $request->has('faqcheck') ? 'on' : 'off',

		];

		$callaction = Apptitle::updateOrCreate(
		['id' => $calID], $calldetails);


		return redirect()->back()->with('success', lang('Updated Successfully', 'alerts'));
	}

	public function status(Request $request, $id)
    {
        $calID = FAQ::find($id);
        $calID->status = $request->status;
        $calID->save();

        return response()->json(['code'=>200, 'success'=> lang('Updated Successfully', 'alerts')], 200);

    }

    public function privatestatus(Request $request, $id)
    {
        $calID = FAQ::find($id);
        $calID->privatemode = $request->privatemode;

        $calID->save();

        return response()->json(['code'=>200, 'success'=> lang('Updated Successfully', 'alerts')], 200);

    }

}
