<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ticket\Ticket;
use Auth;
use App\Models\User;
use App\Models\usersettings;
use App\Models\Apptitle;
use App\Models\Footertext;
use App\Models\Seosetting;
use App\Models\Pages;
use App\Models\Customer;
use App\Models\Groupsusers;
use App\Models\Groups;
use App\Models\Ticket\Category;
use DB;
use DataTables;
use Carbon\Carbon;
use Session;
use Illuminate\Support\Str;
use Artisan;
use App\Models\ticketassignchild;


class AdminDashboardController extends Controller
{



    public function index()
    {
        if(Auth::user()->dashboard == 'Admin'){
            return $this->adminDashboard();
        }
        if(Auth::user()->dashboard == 'Employee' || Auth::user()->dashboard == null){
            return $this->Dashboard();
        }

    }

    //Super Admin Dashboard
    public function adminDashboard()
    {
        $title = Apptitle::first();
        $data['title'] = $title;

        $footertext = Footertext::first();
        $data['footertext'] = $footertext;

        $seopage = Seosetting::first();
        $data['seopage'] = $seopage;

        $post = Pages::all();
        $data['page'] = $post;

        // Ticket Counting
        $totaltickets = Ticket::count();
        $data['totaltickets'] = $totaltickets;

        $totalactivetickets = Ticket::whereIn('status',['Re-Open','Inprogress','On-Hold'])->count();
        $data['totalactivetickets'] = $totalactivetickets;

        $totalclosedtickets = Ticket::where('status','Closed')->count();
        $data['totalclosedtickets'] = $totalclosedtickets;

        $replyrecent = Ticket::whereIn('status',['Re-Open','Inprogress','On-Hold'])->where('replystatus', 'Replied')->count();
        $data['replyrecent'] = $replyrecent;

        $recentticketlist = Ticket::where('status','New')->get();
        $recentticketcount = 0;

        foreach($recentticketlist as $recent){
            if($recent->myassignuser_id == null && $recent->selfassignuser_id == null && $recent->toassignuser_id == null){
                $recentticketcount += 1;
            }
        }
        $data['recentticketcount'] = $recentticketcount;

        $selfassigncount = Ticket::where('selfassignuser_id',Auth::id())->where('status', '!=' ,'Closed')->where('status', '!=' ,'Suspend')->count();
        $data['selfassigncount'] = $selfassigncount;

        $myassignedticket = Ticket::leftJoin('ticketassignchildren','ticketassignchildren.ticket_id','tickets.id')->where('toassignuser_id',Auth::id())->where('status', '!=' ,'Closed')->where('status', '!=' ,'Suspend')->get();
        $myassignedticketcount = 0;
        foreach($myassignedticket as $recent){
            if( $recent->toassignuser_id != null){
                $myassignedticketcount += 1;
            }
        }
        $data['myassignedticketcount'] = $myassignedticketcount;


        $myclosedticketcount = Ticket::where('closedby_user',Auth::id())->count();
        $data['myclosedticketcount'] = $myclosedticketcount;

        $suspendedticketcount = Ticket::where('status','Suspend')->count();
        $data['suspendedticketcount'] = $suspendedticketcount;

        $alltickets = Ticket::whereIn('status', ['New'])->latest('updated_at')->get();
        $data['alltickets'] = $alltickets;

        $data['gtickets'] = Ticket::whereIn('status', ['New'])->latest('updated_at')->get();

        $suspendticketcount = Ticket::where('status', 'Suspend')->where('lastreply_mail',Auth::id())->count();
        $data['suspendticketcount'] = $suspendticketcount;


        return view('admin.superadmindashboard.dashboard')->with($data);
    }

    // Employee Dashboard
    public function Dashboard()
    {
        $groups =  Groups::where('groupstatus', '1')->get();

        $group_id = '';
        foreach($groups as $group){
            $group_id .= $group->id . ',';
        }


        $groupexists = Groupsusers::whereIn('groups_id', explode(',', substr($group_id,0,-1)))->where('users_id', Auth::id())->exists();

        // if there in group get group tickets
        if($groupexists){

            $totalactivetickets = Ticket::select('tickets.*',"groups_categories.group_id","groups_users.users_id")
            ->leftJoin('groups_categories','groups_categories.category_id','tickets.category_id')
            ->leftJoin('groups_users','groups_users.groups_id','groups_categories.group_id')
            ->whereIn('tickets.status', ['Inprogress', 'Re-Open', 'On-Hold'])
            ->where('status', '!=' ,'Closed')
            ->whereNotNull('groups_users.users_id')
            ->whereNull('tickets.myassignuser_id')
            ->whereNull('tickets.selfassignuser_id')
            ->where('groups_users.users_id', Auth::id())
            ->count();
            $data['totalactivetickets'] = $totalactivetickets;

            $totalactiverecent = Ticket::select('tickets.*',"groups_categories.group_id","groups_users.users_id")
            ->leftJoin('groups_categories','groups_categories.category_id','tickets.category_id')
            ->leftJoin('groups_users','groups_users.groups_id','groups_categories.group_id')
            ->whereIn('tickets.status', ['Inprogress', 'Re-Open', 'On-Hold'])
            ->where('replystatus', 'Replied')
            ->whereNotNull('groups_users.users_id')
            ->whereNull('tickets.myassignuser_id')
            ->whereNull('tickets.selfassignuser_id')
            ->where('groups_users.users_id', Auth::id())
            ->count();
            $data['totalactiverecent'] = $totalactiverecent;

            $recentticketcount = Ticket::select('tickets.*',"groups_categories.group_id","groups_users.users_id")
            ->leftJoin('groups_categories','groups_categories.category_id','tickets.category_id')
            ->leftJoin('groups_users','groups_users.groups_id','groups_categories.group_id')
            ->where('tickets.status','New')
            ->whereNull('tickets.myassignuser_id')
            ->whereNull('tickets.selfassignuser_id')
            ->whereNotNull('groups_users.users_id')
            ->where('groups_users.users_id', Auth::id())
            ->count();
            $data['recentticketcount'] = $recentticketcount;



            $gticket = Ticket::select('tickets.*',"groups_categories.group_id","groups_users.users_id", "groups.*")
                ->leftJoin('groups_categories','groups_categories.category_id','tickets.category_id')
                ->leftJoin('groups_users','groups_users.groups_id','groups_categories.group_id')
                ->leftJoin('groups','groups.id','groups_users.groups_id')
                ->whereNotNull('groups_users.users_id')
                ->where('groups.groupstatus', '1')
                ->where('groups_users.users_id', Auth::id())
                ->get();
            $data['gtickets'] = $gticket;


            $ticketnote = DB::table('ticketnotes')->pluck('ticketnotes.ticket_id')->toArray();
            $data['ticketnote'] = $ticketnote;


        }
        // If no there in group we get the all tickets
        else{

            $totalactivetickets = Ticket::select('tickets.*',"groups_categories.group_id","groups_users.users_id")
            ->leftJoin('groups_categories','groups_categories.category_id','tickets.category_id')
            ->leftJoin('groups_users','groups_users.groups_id','groups_categories.group_id')
            ->whereIn('tickets.status', ['Re-Open','Inprogress','On-Hold'])
            ->where('status', '!=' ,'Closed')
            ->whereNull('tickets.myassignuser_id')
            ->whereNull('tickets.selfassignuser_id')
            ->whereNull('groups_users.users_id')
            ->count();
            $data['totalactivetickets'] = $totalactivetickets;

            $totalactiverecent = Ticket::select('tickets.*',"groups_categories.group_id","groups_users.users_id")
            ->leftJoin('groups_categories','groups_categories.category_id','tickets.category_id')
            ->leftJoin('groups_users','groups_users.groups_id','groups_categories.group_id')
            ->whereIn('tickets.status', ['Re-Open','Inprogress','On-Hold'])
            ->where('replystatus', 'Replied')
            ->whereNull('tickets.myassignuser_id')
            ->whereNull('tickets.selfassignuser_id')
            ->whereNull('groups_users.users_id')
            ->count();
            $data['totalactiverecent'] = $totalactiverecent;

            $recentticketcount = Ticket::select('tickets.*',"groups_categories.group_id","groups_users.users_id")
            ->leftJoin('groups_categories','groups_categories.category_id','tickets.category_id')
            ->leftJoin('groups_users','groups_users.groups_id','groups_categories.group_id')
            ->where('status','New')
            ->whereNull('tickets.myassignuser_id')
            ->whereNull('tickets.selfassignuser_id')
            ->whereNull('groups_users.users_id')->count();
            $data['recentticketcount'] = $recentticketcount;



            $gtickets = Ticket::latest('updated_at')->get();
            $data['gtickets'] = $gtickets;

            $ticketnote = DB::table('ticketnotes')->pluck('ticketnotes.ticket_id')->toArray();
            $data['ticketnote'] = $ticketnote;

        }

        $title = Apptitle::first();
        $data['title'] = $title;

        $footertext = Footertext::first();
        $data['footertext'] = $footertext;

        $seopage = Seosetting::first();
        $data['seopage'] = $seopage;

        $post = Pages::all();
        $data['page'] = $post;

        $selfassigncount = Ticket::where('selfassignuser_id',Auth::id())->where('status', '!=' ,'Closed')->where('status', '!=' ,'Suspend')->count();
        $data['selfassigncount'] = $selfassigncount;

        $selfassignrecentreply = Ticket::where('selfassignuser_id',Auth::id())->where('replystatus', 'Replied')->where('status', '!=' ,'Closed')->count();
        $data['selfassignrecentreply'] = $selfassignrecentreply;

        $myassignedticketcount = Ticket::leftJoin('ticketassignchildren','ticketassignchildren.ticket_id','tickets.id')->where('toassignuser_id',Auth::id())->where('status', '!=' ,'Closed')->where('status', '!=' ,'Suspend')->count();
        $data['myassignedticketcount'] = $myassignedticketcount;

        $myassignedticketrecentreply = Ticket::leftJoin('ticketassignchildren','ticketassignchildren.ticket_id','tickets.id')->where('toassignuser_id',Auth::id())->where('status', '!=' ,'Closed')->where('replystatus', 'Replied')->count();
        $data['myassignedticketrecentreply'] = $myassignedticketrecentreply;

        $myclosedticketcount = Ticket::where('closedby_user',Auth::id())->count();
        $data['myclosedticketcount'] = $myclosedticketcount;

        $suspendticketcount = Ticket::where('status', 'Suspend')->where('lastreply_mail',Auth::id())->count();
        $data['suspendticketcount'] = $suspendticketcount;

        return view('admin.dashboard')->with($data);

    }

    public function dashboardtabledata()
    {

        if(Auth::user()->dashboard == 'Admin'){
            return $this->adminDashboardtabledata();
        }
        if(Auth::user()->dashboard == 'Employee' || Auth::user()->dashboard == null){
            return $this->Dashboardtabledatas();
        }

        // return auth()->user()->hasRole('superadmin') ? $this->adminDashboardtabledata() : $this->Dashboardtabledatas();

    }

    //superadmin dashborad
    public function adminDashboardtabledata()
    {
        $data['alltickets'] = Ticket::whereIn('status', ['New'])->latest('updated_at')->get();
        $data['ticketnote'] = DB::table('ticketnotes')->pluck('ticketnotes.ticket_id')->toArray();



        return view('admin.superadmindashboard.dashboardtabledata')->with($data);
    }

    //Employee dashboard
    public function Dashboardtabledatas()
    {
        $groups =  Groups::where('groupstatus', '1')->get();

        $group_id = '';
        foreach($groups as $group){
            $group_id .= $group->id . ',';
        }


        $groupexists = Groupsusers::whereIn('groups_id', explode(',', substr($group_id,0,-1)))->where('users_id', Auth::id())->exists();


        // if there in group get group tickets
        if($groupexists){

            // All tickets
            $gticket = Ticket::select('tickets.*',"groups_categories.group_id","groups_users.users_id")
                ->leftJoin('groups_categories','groups_categories.category_id','tickets.category_id')
                ->leftJoin('groups_users','groups_users.groups_id','groups_categories.group_id')
                ->whereIn('tickets.status', ['New'])
                ->whereNotNull('groups_users.users_id')
                ->where('groups_users.users_id', Auth::id())
                ->latest('tickets.updated_at')
                ->get();
            $data['gtickets'] = $gticket;
            // ticket note
            $ticketnote = DB::table('ticketnotes')->pluck('ticketnotes.ticket_id')->toArray();
            $data['ticketnote'] = $ticketnote;

        }
        // If no there in group we get the all tickets
        else{


            $gtickets = Ticket::select('tickets.*',"groups_categories.group_id","groups_users.users_id")
            ->leftJoin('groups_categories','groups_categories.category_id','tickets.category_id')
            ->leftJoin('groups_users','groups_users.groups_id','groups_categories.group_id')
            ->whereIn('tickets.status', ['New'])
            ->whereNull('groups_users.users_id')
            ->latest('tickets.updated_at')
            ->get();;
            $data['gtickets'] = $gtickets;

            $ticketnote = DB::table('ticketnotes')->pluck('ticketnotes.ticket_id')->toArray();
            $data['ticketnote'] = $ticketnote;

        }


        return view('admin.dashboardtabledata')->with($data);
    }

    public function activeticket()
    {

        if(Auth::user()->dashboard == 'Admin'){
            return $this->adminallactiveticket();
        }
        if(Auth::user()->dashboard == 'Employee' || Auth::user()->dashboard == null){
            return $this->employeeallactiveticket();
        }



    }

    public function adminallactiveticket()
    {
        $allactivetickets = Ticket::whereIn('status', ['Re-Open','Inprogress','On-Hold'])->latest('updated_at')->get();
        $data['allactivetickets'] = $allactivetickets;

        $allactiveinprogresstickets = Ticket::where('status', 'Inprogress')->count();
        $data['allactiveinprogresstickets'] = $allactiveinprogresstickets;

        $allactivereopentickets = Ticket::whereIn('status', ['Re-Open'])->count();
        $data['allactivereopentickets'] = $allactivereopentickets;

        $allactiveonholdtickets = Ticket::whereIn('status', ['On-Hold'])->count();
        $data['allactiveonholdtickets'] = $allactiveonholdtickets;

        $allactiveassignedtickets = Ticket::whereIn('status', ['Re-Open','Inprogress','On-Hold'])->where(function($r){
            $r->whereNotNull('myassignuser_id')
            ->orWhereNotNull('selfassignuser_id');
        })->count();
        $data['allactiveassignedtickets'] = $allactiveassignedtickets;

        $allactiveoverduetickets = Ticket::whereIn('status', ['Re-Open','Inprogress','On-Hold'])->whereNotNull('overduestatus')->count();
        $data['allactiveoverduetickets'] = $allactiveoverduetickets;

        $title = Apptitle::first();
        $data['title'] = $title;

        $footertext = Footertext::first();
        $data['footertext'] = $footertext;

        $seopage = Seosetting::first();
        $data['seopage'] = $seopage;

        $post = Pages::all();
        $data['page'] = $post;

        return view('admin.superadmindashboard.activeticket' )->with($data);
    }

    public function employeeallactiveticket()
    {

        $groupexists = Groupsusers::where('users_id', Auth::id())->exists();

        // if there in group get group tickets
        if($groupexists){

            $activetickets = Ticket::select('tickets.*',"groups_categories.group_id","groups_users.users_id")
                ->leftJoin('groups_categories','groups_categories.category_id','tickets.category_id')
                ->leftJoin('groups_users','groups_users.groups_id','groups_categories.group_id')
                ->whereIn('tickets.status', ['Re-Open','Inprogress','On-Hold'])
                ->whereNotNull('groups_users.users_id')
                ->where('groups_users.users_id', Auth::id())
                ->latest('tickets.updated_at')
                ->get();
            $data['gtickets'] = $activetickets;

            $ticketnote = DB::table('ticketnotes')->pluck('ticketnotes.ticket_id')->toArray();
            $data['ticketnote'] = $ticketnote;

        }
        // If no there in group we get the all tickets
        else{


            $activetickets = Ticket::select('tickets.*',"groups_categories.group_id","groups_users.users_id")
                ->leftJoin('groups_categories','groups_categories.category_id','tickets.category_id')
                ->leftJoin('groups_users','groups_users.groups_id','groups_categories.group_id')
                ->whereIn('tickets.status', ['Re-Open','Inprogress','On-Hold'])
                ->whereNull('groups_users.users_id')
                ->latest('tickets.updated_at')
                ->get();
            $data['gtickets'] = $activetickets;
            $ticketnote = DB::table('ticketnotes')->pluck('ticketnotes.ticket_id')->toArray();
            $data['ticketnote'] = $ticketnote;

        }

        $title = Apptitle::first();
        $data['title'] = $title;

        $footertext = Footertext::first();
        $data['footertext'] = $footertext;

        $seopage = Seosetting::first();
        $data['seopage'] = $seopage;

        $post = Pages::all();
        $data['page'] = $post;

        return view('admin.userticket.viewticket.activeticket' )->with($data);

    }

    // Superadmin all closed Ticket
    public function closedticket()
    {

        $allclosedtickets = Ticket::where('status', 'Closed')->latest('updated_at')->get();
        $data['allclosedtickets'] = $allclosedtickets;

        $title = Apptitle::first();
        $data['title'] = $title;

        $footertext = Footertext::first();
        $data['footertext'] = $footertext;

        $seopage = Seosetting::first();
        $data['seopage'] = $seopage;

        $post = Pages::all();
        $data['page'] = $post;

        return view('admin.superadmindashboard.closedticket')->with($data);
    }

    public function assignedTickets()
    {

        $assignedtickets = Ticket::where('myassignuser_id', Auth::id())->whereNull('selfassignuser_id')->where('status', '!=' ,'Closed')->latest('updated_at')->get();
        $data['gtickets'] = $assignedtickets;

        $ticketnote = DB::table('ticketnotes')->pluck('ticketnotes.ticket_id')->toArray();
        $data['ticketnote'] = $ticketnote;

        $active = Ticket::whereIn('status', ['New', 'Re-Open','Inprogress'])->get();

        $closed = Ticket::where('status', 'Closed')->get();

        $title = Apptitle::first();
        $data['title'] = $title;

        $footertext = Footertext::first();
        $data['footertext'] = $footertext;

        $seopage = Seosetting::first();
        $data['seopage'] = $seopage;

        $post = Pages::all();
        $data['page'] = $post;

        $agent = User::count();
        $data['agent'] = $agent;

        $customer = User::count();
        $data['customer'] = $customer;


        $assignedticketsnew = Ticket::where('myassignuser_id', Auth::id())->whereNull('selfassignuser_id')->where('status', 'New')->count();
        $data['assignedticketsnew'] = $assignedticketsnew;

        $assignedticketsinprogress = Ticket::where('myassignuser_id', Auth::id())->whereNull('selfassignuser_id')->where('status', 'Inprogress')->count();
        $data['assignedticketsinprogress'] = $assignedticketsinprogress;

        $assignedticketsonhold = Ticket::where('myassignuser_id', Auth::id())->whereNull('selfassignuser_id')->where('status', 'On-Hold')->count();
        $data['assignedticketsonhold'] = $assignedticketsonhold;

        $assignedticketsreopen = Ticket::where('myassignuser_id', Auth::id())->whereNull('selfassignuser_id')->where('status', 'Re-Open')->count();
        $data['assignedticketsreopen'] = $assignedticketsreopen;

        $assignedticketsoverdue = Ticket::where('myassignuser_id', Auth::id())->whereNull('selfassignuser_id')->where('overduestatus', 'Overdue')->count();
        $data['assignedticketsoverdue'] = $assignedticketsoverdue;

        $assignedticketsclosed = Ticket::where('myassignuser_id', Auth::id())->whereNull('selfassignuser_id')->where('status', 'Closed')->count();
        $data['assignedticketsclosed'] = $assignedticketsclosed;

        return view('admin.assignedtickets.index', compact('active','closed'))->with($data);
    }

    public function myassignedTickets()
    {

        $myassignedtickets = Ticket::select('tickets.*', 'ticketassignchildren.toassignuser_id')->whereNull('selfassignuser_id')->leftjoin('ticketassignchildren', 'ticketassignchildren.ticket_id', 'tickets.id')->where('status', '!=' ,'Closed')->where('status', '!=' ,'Suspend')->latest('updated_at')->get();
        $data['gtickets'] = $myassignedtickets;

        $ticketnote = DB::table('ticketnotes')->pluck('ticketnotes.ticket_id')->toArray();
        $data['ticketnote'] = $ticketnote;


        $active = Ticket::whereIn('status', ['New', 'Re-Open','Inprogress'])->get();

        $closed = Ticket::where('status', 'Closed')->get();

        $title = Apptitle::first();
        $data['title'] = $title;

        $footertext = Footertext::first();
        $data['footertext'] = $footertext;

        $seopage = Seosetting::first();
        $data['seopage'] = $seopage;

        $post = Pages::all();
        $data['page'] = $post;

        $agent = User::count();
        $data['agent'] = $agent;

        $customer = User::count();
        $data['customer'] = $customer;




        return view('admin.assignedtickets.myassignedticket', compact('active','closed'))->with($data);
    }

    public function onholdticket()
    {


        if(Auth::user()->dashboard == 'Admin'){
            return $this->adminonholdticket();
        }
        if(Auth::user()->dashboard == 'Employee' || Auth::user()->dashboard == null){
            return $this->employeeonholdticket();
        }



    }

    public function adminonholdticket()
    {
        $allonholdtickets = Ticket::where('status','On-Hold')->latest('updated_at')->get();
        $data['allonholdtickets'] = $allonholdtickets;

        $title = Apptitle::first();
        $data['title'] = $title;

        $footertext = Footertext::first();
        $data['footertext'] = $footertext;

        $seopage = Seosetting::first();
        $data['seopage'] = $seopage;

        $post = Pages::all();
        $data['page'] = $post;

        return view('admin.superadmindashboard.onholdtickets')->with($data);
    }

    public function employeeonholdticket()
    {


        $groupexists = Groupsusers::where('users_id', Auth::id())->exists();

        // if there in group get group tickets
        if($groupexists){

            $onholdtickets = Ticket::select('tickets.*',"groups_categories.group_id","groups_users.users_id")
                ->leftJoin('groups_categories','groups_categories.category_id','tickets.category_id')
                ->leftJoin('groups_users','groups_users.groups_id','groups_categories.group_id')
                ->where('status','On-Hold')
                ->whereNotNull('groups_users.users_id')
                ->where('groups_users.users_id', Auth::id())
                ->get();
            $data['gtickets'] = $onholdtickets;

            $ticketnote = DB::table('ticketnotes')->pluck('ticketnotes.ticket_id')->toArray();
            $data['ticketnote'] = $ticketnote;

        }
        // If no there in group we get the all tickets
        else{

            $onholdtickets = Ticket::select('tickets.*',"groups_categories.group_id","groups_users.users_id")
            ->leftJoin('groups_categories','groups_categories.category_id','tickets.category_id')
            ->leftJoin('groups_users','groups_users.groups_id','groups_categories.group_id')
            ->where('status','On-Hold')
            ->whereNull('groups_users.users_id')
            ->get();
            $data['gtickets'] = $onholdtickets;

            $ticketnote = DB::table('ticketnotes')->pluck('ticketnotes.ticket_id')->toArray();
            $data['ticketnote'] = $ticketnote;

        }

        $title = Apptitle::first();
        $data['title'] = $title;

        $footertext = Footertext::first();
        $data['footertext'] = $footertext;

        $seopage = Seosetting::first();
        $data['seopage'] = $seopage;

        $post = Pages::all();
        $data['page'] = $post;

        return view('admin.assignedtickets.onholdtickets')->with($data);
    }

    public function overdueticket()
    {

        if(Auth::user()->dashboard == 'Admin'){
            return $this->adminoverdueticket();
        }
        if(Auth::user()->dashboard == 'Employee' || Auth::user()->dashboard == null){
            return $this->employeeoverdueticket();
        }


    }


    public function adminoverdueticket()
    {
        $alloverduetickets = Ticket::whereIn('overduestatus', ['Overdue'])->latest('updated_at')->get();
        $data['alloverduetickets'] = $alloverduetickets;

        $title = Apptitle::first();
        $data['title'] = $title;

        $footertext = Footertext::first();
        $data['footertext'] = $footertext;

        $seopage = Seosetting::first();
        $data['seopage'] = $seopage;

        $post = Pages::all();
        $data['page'] = $post;

        return view('admin.superadmindashboard.overdueticket')->with($data);

    }


    public function employeeoverdueticket()
    {

        $groupexists = Groupsusers::where('users_id', Auth::id())->exists();

        // if there in group get group tickets
        if($groupexists){
            $overduetickets = Ticket::select('tickets.*',"groups_categories.group_id","groups_users.users_id")
                ->leftJoin('groups_categories','groups_categories.category_id','tickets.category_id')
                ->leftJoin('groups_users','groups_users.groups_id','groups_categories.group_id')
                ->whereIn('overduestatus', ['Overdue'])
                ->whereNotNull('groups_users.users_id')
                ->where('groups_users.users_id', Auth::id())
                ->get();
            $data['gtickets'] = $overduetickets;

            $ticketnote = DB::table('ticketnotes')->pluck('ticketnotes.ticket_id')->toArray();
            $data['ticketnote'] = $ticketnote;

        }
        // If no there in group we get the all tickets
        else{
            $overduetickets = Ticket::select('tickets.*',"groups_categories.group_id","groups_users.users_id")
            ->leftJoin('groups_categories','groups_categories.category_id','tickets.category_id')
            ->leftJoin('groups_users','groups_users.groups_id','groups_categories.group_id')
            ->whereIn('overduestatus', ['Overdue'])
            ->whereNull('groups_users.users_id')
            ->get();
            $data['gtickets'] = $overduetickets;

            $ticketnote = DB::table('ticketnotes')->pluck('ticketnotes.ticket_id')->toArray();
            $data['ticketnote'] = $ticketnote;

        }

        $title = Apptitle::first();
        $data['title'] = $title;

        $footertext = Footertext::first();
        $data['footertext'] = $footertext;

        $seopage = Seosetting::first();
        $data['seopage'] = $seopage;

        $post = Pages::all();
        $data['page'] = $post;

        $tickets = Ticket::whereIn('overduestatus', ['Overdue'])->get();

        return view('admin.assignedtickets.overdueticket', compact('tickets'))->with($data);
    }


    public function adminallassignedtickets()
    {
        $allassignedtickets = Ticket::latest('updated_at')->get();
        $data['allassignedtickets'] = $allassignedtickets;

        $title = Apptitle::first();
        $data['title'] = $title;

        $footertext = Footertext::first();
        $data['footertext'] = $footertext;

        $seopage = Seosetting::first();
        $data['seopage'] = $seopage;

        $post = Pages::all();
        $data['page'] = $post;

        return view('admin.superadmindashboard.allassignedtickets')->with($data);
    }

    public function recenttickets()
    {

        if(Auth::user()->dashboard == 'Admin'){
            return $this->adminrecentticket();
        }
        if(Auth::user()->dashboard == 'Employee' || Auth::user()->dashboard == null){
            return $this->employeerecentticket();
        }

    }

    public function adminrecentticket()
    {
        $recenttickets = Ticket::whereIn('status', ['New'])->latest('updated_at')->get();
        $data['recenttickets'] = $recenttickets;

        $title = Apptitle::first();
        $data['title'] = $title;

        $footertext = Footertext::first();
        $data['footertext'] = $footertext;

        $seopage = Seosetting::first();
        $data['seopage'] = $seopage;

        $post = Pages::all();
        $data['page'] = $post;

        return view('admin.superadmindashboard.recenttickets')->with($data);
    }


    public function employeerecentticket()
    {

        $groupexists = Groupsusers::where('users_id', Auth::id())->exists();

        // if there in group get group tickets
        if($groupexists){

            $recenttickets = Ticket::select('tickets.*',"groups_categories.group_id","groups_users.users_id")
                ->leftJoin('groups_categories','groups_categories.category_id','tickets.category_id')
                ->leftJoin('groups_users','groups_users.groups_id','groups_categories.group_id')
                ->whereIn('tickets.status', ['New'])
                ->whereNotNull('groups_users.users_id')
                ->where('groups_users.users_id', Auth::id())
                ->latest('tickets.updated_at')
                ->get();
                $data['gtickets'] = $recenttickets;

            $ticketnote = DB::table('ticketnotes')->pluck('ticketnotes.ticket_id')->toArray();
            $data['ticketnote'] = $ticketnote;


        }
        // If no there in group we get the all tickets
        else{

            $recenttickets = Ticket::select('tickets.*',"groups_categories.group_id","groups_users.users_id")
            ->leftJoin('groups_categories','groups_categories.category_id','tickets.category_id')
            ->leftJoin('groups_users','groups_users.groups_id','groups_categories.group_id')
            ->whereIn('tickets.status', ['New'])
            ->whereNull('groups_users.users_id')
            ->latest('tickets.updated_at')
            ->get();
            $data['gtickets'] = $recenttickets;

            $ticketnote = DB::table('ticketnotes')->pluck('ticketnotes.ticket_id')->toArray();
            $data['ticketnote'] = $ticketnote;

        }

        $title = Apptitle::first();
        $data['title'] = $title;

        $footertext = Footertext::first();
        $data['footertext'] = $footertext;

        $seopage = Seosetting::first();
        $data['seopage'] = $seopage;

        $post = Pages::all();
        $data['page'] = $post;

        return view('admin.assignedtickets.recenttickets')->with($data);

    }

    public function markNotification(Request $request)
    {
        auth()->user()
            ->unreadNotifications
            ->when($request->input('id'), function ($query) use ($request) {
                return $query->where('id', $request->input('id'));
            })
            ->markAsRead();

        return response()->noContent();
    }


    public function autorefresh(Request $request, $id)
    {
        $calID = User::with('usetting')->find($id);
        if($calID->usetting == null){
            $usersettings = new usersettings();
            $usersettings->users_id = $request->id;
            $usersettings->ticket_refresh = $request->status;
            $usersettings->save();
        }
        else{
            $calID->usetting->ticket_refresh = $request->status;
            $calID->usetting->save();
        }

        return response()->json(['code'=>200, 'success'=> lang('Updated successfully', 'alerts')], 200);

    }


    public function summernoteimageupload(Request $request)
    {
        $files = $request->file('image');

        $destinationPath = public_path() . "" . '/uploads/data/'; // upload path
        $profileImage = date('YmdHis') . "." . $files->getClientOriginalExtension();
        $path = $files->move($destinationPath, $profileImage);

        $destinationPath1 = url('/').'/uploads/data/' .$profileImage;
        return response()->json(['code'=>200, 'data' => $destinationPath1,  ], 200);
    }


    public function Notificationview($id)
   {
        $notification = auth()->user()->notifications()->where('id', $id)->firstOrFail();
        $data['notifications'] = $notification;

        $title = Apptitle::first();
        $data['title'] = $title;

        $footertext = Footertext::first();
        $data['footertext'] = $footertext;

        $seopage = Seosetting::first();
        $data['seopage'] = $seopage;

        $post = Pages::all();
        $data['page'] = $post;

        return view('admin.notification.viewnotification')->with($data);
   }

   public function notifystatus(Request $request)
   {
       $status = $request->statusnotify;
       if(!$status){
            $notifications = auth()->user()->notifications()->paginate('10')->groupBy(function($date) {
                return \Carbon\Carbon::parse($date->created_at)->format('Y-m-d');
            });
       }
       else{
            $notifications =  auth()->user()->notifications()->whereIn('data->status', $status)->paginate('10')->groupBy(function($date) {
                return \Carbon\Carbon::parse($date->created_at)->format('Y-m-d');
            });
       }

       $title = Apptitle::first();
        $data['title'] = $title;

        $footertext = Footertext::first();
        $data['footertext'] = $footertext;

        $seopage = Seosetting::first();
        $data['seopage'] = $seopage;

       $view = view('admin.notificationpageinclude',compact('notifications','title', 'footertext', 'seopage'))->render();
       return response()->json(['html'=>$view]);
   }

   public function notifysearch(Request $request)
   {
       $status = $request->notifysearch;

       if($status){
            $notifications = auth()->user()->notifications()->where(function($query){
                $keyword = request()->notifysearch;
                $query->where('data->title','LIKE', "%{$keyword}%")
                ->orWhere('data->ticket_id','LIKE', "%{$keyword}%")
                ->orWhere('data->mailsubject','LIKE', "%{$keyword}%")
                ->orWhere('data->mailtext','LIKE', "%{$keyword}%");
            })->get()->groupBy(function($date) {
                return \Carbon\Carbon::parse($date->created_at)->format('Y-m-d');
            });
       }
       else{
            $notifications =  auth()->user()->notifications()->paginate()->groupBy(function($date) {
                return \Carbon\Carbon::parse($date->created_at)->format('Y-m-d');
            });
       }

       $title = Apptitle::first();
        $data['title'] = $title;

        $footertext = Footertext::first();
        $data['footertext'] = $footertext;

        $seopage = Seosetting::first();
        $data['seopage'] = $seopage;

       $view = view('admin.notificationpageinclude',compact('notifications','title', 'footertext', 'seopage'))->render();
       return response()->json(['html'=>$view]);
   }

   public function notifydelete(Request $request)
   {
       $id = $request->id;

       $notificationsdelete = auth()->user()->notifications()->find($id);
       $notificationsdelete->delete();

       return response()->json(['success'=> lang('Deleted successfully', 'alerts'),200]);
   }

   public function markallnotify()
   {
        auth()->user()->unreadNotifications->markAsRead();

        return response()->noContent();
   }

   public function clearcache()
   {
       Artisan::call('optimize:clear');

        return response()->json(['success'=> lang('Cache Clear Successfull', 'alerts')]);
   }

   public function suspendedtickets()
   {
        $data['suspendedtickets'] = Ticket::where('status', 'Suspend')->latest('updated_at')->get();

        $title = Apptitle::first();
        $data['title'] = $title;

        $footertext = Footertext::first();
        $data['footertext'] = $footertext;

        $seopage = Seosetting::first();
        $data['seopage'] = $seopage;

        $post = Pages::all();
        $data['page'] = $post;

        return view('admin.superadmindashboard.suspendedtickets')->with($data);
   }


}
