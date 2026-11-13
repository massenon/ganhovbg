<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Apptitle;
use App\Models\Footertext;
use App\Models\Seosetting;
use App\Models\Pages;
use App\Models\User;
use App\Models\Customer;
use App\Models\Ticket\Ticket;
use App\Models\usersettings;
use DB;
use DataTables;
use Carbon\Carbon;
use App\Models\Userrating;
use App\Models\Employeerating;
use App\Models\Ticket\Comment;
use App\Models\Articles\Article;

class AdminReportController extends Controller
{
   public function index()
   {

      $this->authorize('Reports Access');

      $users = User::latest('updated_at')->paginate(6);
      $data['users'] = $users;

      $title = Apptitle::first();
      $data['title'] = $title;

      $footertext = Footertext::first();
      $data['footertext'] = $footertext;

      $seopage = Seosetting::first();
      $data['seopage'] = $seopage;

      $post = Pages::all();
      $data['page'] = $post;

      $agentactivec = User::where('status','1')->count();
      $data['agentactivec'] = $agentactivec;
      $agentinactive = User::where('status','0')->count();
      $data['agentinactive'] = $agentinactive;

      $customeractive = Customer::where('status','1')->count();
      $data['customeractive'] = $customeractive;
      $customerinactive = Customer::where('status','0')->count();
      $data['customerinactive'] = $customerinactive;

      $newticket = Ticket::where('status', 'New')->count();
      $data['newticket'] = $newticket;

      $closedticket = Ticket::where('status', 'Closed')->count();
      $data['closedticket'] = $closedticket;

      $inprogressticket = Ticket::where('status', 'Inprogress')->count();
      $data['inprogressticket'] = $inprogressticket;

      $onholdticket = Ticket::where('status', 'On-Hold')->count();
      $data['onholdticket'] = $onholdticket;

      $reopenticket = Ticket::where('status', 'Re-Open')->count();
      $data['reopenticket'] = $reopenticket;

      $prioritylow = Ticket::where('priority', 'Low')->count();
      $data['prioritylow'] = $prioritylow;

      $priorityhigh = Ticket::where('priority', 'High')->count();
      $data['priorityhigh'] = $priorityhigh;

      $prioritymedium = Ticket::where('priority', 'Medium')->count();
      $data['prioritymedium'] = $prioritymedium;

      $prioritycritical = Ticket::where('priority', 'Critical')->count();
      $data['prioritycritical'] = $prioritycritical;

      $articlepublished = Article::where('status', 'Published')->count();
      $data['articlepublished'] = $articlepublished;

      $articleunpublished = Article::where('status', 'UnPublished')->count();
      $data['articleunpublished'] = $articleunpublished;


      return view('admin.reports.index')->with($data);
   }

   public function ticketreports()
	{


      $users = User::get();
      $data['users'] = $users;

      $title = Apptitle::first();
      $data['title'] = $title;

      $footertext = Footertext::first();
      $data['footertext'] = $footertext;

      $seopage = Seosetting::first();
      $data['seopage'] = $seopage;

      $post = Pages::all();
      $data['page'] = $post;



      return view('admin.reports.ticketratingreport')->with($data);
  }

   public function employeedetails($id)
   {
      $users = User::find($id);
      $data['users'] = $users;

      $employeerating = Ticket::select('tickets.*')->leftJoin('comments','comments.ticket_id','tickets.id')
      ->where('comments.user_id', $users->id)->distinct('comments.ticket_id', 'tickets.id')->get();
      $data['employeeratings'] = $employeerating;


      $title = Apptitle::first();
      $data['title'] = $title;

      $footertext = Footertext::first();
      $data['footertext'] = $footertext;

      $seopage = Seosetting::first();
      $data['seopage'] = $seopage;

      $post = Pages::all();
      $data['page'] = $post;

      return view('admin.reports.ratingview')->with($data);
   }

	public function ratingticketdelete($id)
	{
		$ticketratingdelete = Userrating::where('ticket_id', $id)->first();

		$employeeratingdelete = Employeerating::where('urating_id', $ticketratingdelete->id)->get();
		foreach($employeeratingdelete as $employeesrating)
		{
			$employeesrating->delete();
		}
		$ticketratingdelete->delete();

		return response()->json(['success' => lang('Delete successfully', 'alerts')]);

	}
}
