<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DataTables;
use App\Models\Apptitle;
use App\Models\Footertext;
use App\Models\Seosetting;
use App\Models\Pages;
use App\Models\Announcement;
use Auth;
use Str;

class AdminAnnouncementController extends Controller
{

    public function index()
    {
        $this->authorize('Announcements Access');

        $title = Apptitle::first();
        $data['title'] = $title;

        $footertext = Footertext::first();
        $data['footertext'] = $footertext;

        $seopage = Seosetting::first();
        $data['seopage'] = $seopage;

        $post = Pages::all();
        $data['page'] = $post;

        $announcements = Announcement::latest()->get();
        $data['announcements'] = $announcements;

        // announcement date exceeds today date, the status of the announcement will inactive
        $now = now();
        $announcementsupdate = Announcement::whereDate('enddate', '<', $now->toDateString())->get();
        if($announcementsupdate != null){
            foreach($announcementsupdate as $announcementsupdates){
                $announcementsupdates->status = 0;
                $announcementsupdates->update();
            }
        }

        return view('admin.announcement.index')->with($data);
    }

    public function create(Request $request)
    {
        $title = Apptitle::first();
        $data['title'] = $title;

        $footertext = Footertext::first();
        $data['footertext'] = $footertext;

        $seopage = Seosetting::first();
        $data['seopage'] = $seopage;

        $normalDay = ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'];
        $data['normalDay'] = $normalDay;

        return view('admin.announcement.create')->with($data);
    }

    public function store(Request $request)
    {
        if($request->announcementday[0] == null && $request->startdate == null){
            return response()->json(['code'=>500, 'error'=> lang('Please select Date or Day for Announcement.', 'alerts')], 500);
        }

        if($request->announcementday[0]){
            $request->validate([
                'title'=> 'required|max:255',
                'notice' => 'required',
                'announcementday' => 'required'
            ]);

            $announcementdayData = $request->announcementday;
            $ancData = [];
            foreach($announcementdayData as $announcementdayDatas){
                $ancData[] = $announcementdayDatas;
            }
            $ancDataFinal = implode(',',  $ancData);
        }else{

            $request->validate([
                'title'=> 'required|max:255',
                'notice' => 'required',
                'startdate' => 'required',
                'enddate' => 'required',
            ]);
            $ancDataFinal = null;
        }



        $testi =  [
            'title' => $request->title,
            'notice' => $request->notice,
            'startdate' => $request->startdate,
            'enddate' => $request->enddate,
            'primary_color' => $request->primary_color,
            'secondary_color' => $request->secondary_color,
            'status' => $request->status,
            'announcementday' => $ancDataFinal
        ];

        $testimonial = Announcement::Create($testi);

        return response()->json(['code'=>200, 'success'=> lang('An announcement has been created successfully.', 'alerts'),'data' => $testimonial], 200);
    }

    public function edit($id)
    {
        $title = Apptitle::first();
        $data['title'] = $title;

        $footertext = Footertext::first();
        $data['footertext'] = $footertext;

        $seopage = Seosetting::first();
        $data['seopage'] = $seopage;

        $announcementData = Announcement::findOrFail($id);
        $data['announcementData'] = $announcementData;

        $normalDay = ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'];
        $data['normalDay'] = $normalDay;

        $announceDay = explode(',', $announcementData->announcementday);
        $data['announceDay'] = $announceDay;

        return view('admin.announcement.edit')->with($data);
    }

    public function update(Request $request)
    {
        if($request->announcementday == null && $request->startdate == null){
            return response()->json(['code'=>500, 'error'=> lang('Please select Date or Day for Announcement.', 'alerts')], 500);
        }

        if($request->announcementday){
            $request->validate([
                'title'=> 'required|max:255',
                'notice' => 'required',
                'announcementday' => 'required'
            ]);

            $announcementdayData = $request->announcementday;
            $ancData = [];
            foreach($announcementdayData as $announcementdayDatas){
                $ancData[] = $announcementdayDatas;
            }
            $ancDataFinal = implode(',',  $ancData);
        }else{
            $request->validate([
                'title'=> 'required|max:255',
                'notice' => 'required',
                'startdate' => 'required',
                'enddate' => 'required',
            ]);
            $ancDataFinal = null;
        }

        if($request->startdate != null && $request->enddate != null){
            $ancDataFinal = null;
        }

        $testimonial = Announcement::findOrFail($request->testimonial_id);
        $testimonial->title = $request->title;
        $testimonial->notice = $request->notice;
        $testimonial->startdate = $request->startdate;
        $testimonial->enddate = $request->enddate;
        $testimonial->primary_color = $request->primary_color;
        $testimonial->secondary_color = $request->secondary_color;
        $testimonial->status = $request->status;
        $testimonial->announcementday = $ancDataFinal;
        $testimonial->save();

        return response()->json(['code'=>200, 'success'=> lang('An announcement has been successfully updated.', 'alerts'),'data' => $testimonial], 200);
    }

    public function show($id){

        $post = Announcement::find($id);

        return response()->json($post);
    }


    public function destroy($id){

        $announcement = Announcement::find($id);
        $announcement->delete();
        return response()->json(['success'=> lang('The announcement was successfully deleted.', 'alerts')]);

    }

    public function allannouncementdelete(Request $request)
    {

        $id_array = $request->input('id');

        $sendmails = Announcement::whereIn('id', $id_array)->get();

        foreach($sendmails as $sendmail){
            $sendmail->delete();

        }
        return response()->json(['success'=> lang('The announcement was successfully deleted.', 'alerts')]);

    }

    public function status(Request $request, $id)
    {
        $calID = Announcement::find($id);
        $calID ->status = $request->status;
        $calID ->save();

        return response()->json(['code'=>200, 'success'=> lang('Updated successfully', 'alerts')], 200);

    }
}
