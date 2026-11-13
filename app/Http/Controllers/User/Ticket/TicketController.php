<?php

namespace App\Http\Controllers\User\Ticket;

use App\Http\Controllers\Controller;
use App\Mail\mailmailablesend;
use App\Models\Apptitle;
use App\Models\Articles\Article;
use App\Models\CCMAILS;
use App\Models\Customfield;
use App\Models\Footertext;
use App\Models\Pages;
use App\Models\Projects;
use App\Models\Seosetting;
use App\Models\TicketCustomfield;
use App\Models\Ticket\Category;
use App\Models\Ticket\Comment;
use App\Models\Ticket\Ticket;
use App\Models\User;
use App\Notifications\TicketCreateNotifications;
use Auth;
use Illuminate\Http\Request;
use Mail;
use URL;
use App\Models\tickethistory;
use App\Models\Ratingtoken;
use App\Models\Customer;
use Carbon\Carbon;
use App\Models\Setting;
use Modules\Uhelpupdate\Entities\CategoryEnvato;

class TicketController extends Controller
{

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function create()
    {
        if(setting('CUSTOMER_TICKET') == 'no'){

            $categories = Category::whereIn('display', ['ticket', 'both'])->where('status', '1')
                ->get();

            $title = Apptitle::first();
            $data['title'] = $title;

            $footertext = Footertext::first();
            $data['footertext'] = $footertext;

            $seopage = Seosetting::first();
            $data['seopage'] = $seopage;

            $post = Pages::all();
            $data['page'] = $post;

            $populararticle = Article::orderBy('views', 'desc')->latest()->paginate(5);
            $data['populararticles'] = $populararticle;

            $customfields = Customfield::whereIn('displaytypes', ['both', 'createticket'])->where('status','1')->get();
            $data['customfields'] = $customfields;

            $projects = Projects::select('projects.*', 'projects_categories.category_id')->join('projects_categories', 'projects_categories.projects_id', 'projects.id')->get();

           // customer restrict to create tickets based on allowed to create.
           if(setting('RESTRICT_TO_CREATE_TICKET') == 'on' && setting('MAXIMUM_ALLOW_TICKETS') > 0){
            $customer = Customer::where('id', Auth::guard('customer')->user()->id)->get();
            foreach($customer as $customers){
                if($customers->tickets->first()){
                    foreach($customers->tickets as $tic){
                        $tttt = $tic->latest('created_at')->first();
                        if($tttt->created_at->timezone(setting('default_timezone'))->format('Y-m-d') == now()->timezone(setting('default_timezone'))->format('Y-m-d')){
                            if($tttt->created_at->timezone(setting('default_timezone'))->subHour(setting('MAXIMUM_ALLOW_HOURS'))->format('H:i:s') <= $tttt->created_at->timezone(setting('default_timezone'))->format('H:i:s')){
                                $ticketscount = Ticket::where('cust_id', Auth::guard('customer')->user()->id)->whereDate('created_at', Carbon::today())->count();
                                if($ticketscount < setting('MAXIMUM_ALLOW_TICKETS')){
                                    return view('user.ticket.create', compact('categories', 'title', 'footertext'))->with($data);
                                }else{
                                    return redirect()->back()->with('error','You have reached maximum allow tickets to create.');
                                }
                            }
                        }else{
                            return view('user.ticket.create', compact('categories', 'title', 'footertext'))->with($data);
                        }
                    }
                }else{
                    return view('user.ticket.create', compact('categories', 'title', 'footertext'))->with($data);
                }
            }
        }else{
            return view('user.ticket.create', compact('categories', 'title', 'footertext'))->with($data);
        }
    }else{
        return redirect()->back()->with('error','You cannot have access for this ticket create.');
    }


}


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $categories = CategoryEnvato::where('category_id',$request->category)->first();

        if(setting('ENVATO_ON') == 'on' && $categories != null && $request->envato_id == 'undefined'){
            return response()->json(['message' => 'envatoerror', 'error' => lang('Please enter valid details to create a ticket.', 'alerts')], 200);
        }

        $this->validate($request, [
            'subject' => 'required|max:255',
            'category' => 'required',
            'message' => 'required',
            'agree_terms' =>  'required|in:agreed',

        ]);

        $ticket = Ticket::create([
            'subject' => $request->input('subject'),
            'cust_id' => Auth::guard('customer')->user()->id,
            'category_id' => $request->input('category'),
            'message' => $request->input('message'),
            'project' => $request->input('project'),
            'status' => 'New',
        ]);
        $ticket = Ticket::find($ticket->id);
        $ticket->ticket_id = setting('CUSTOMER_TICKETID') . '-' . $ticket->id;
        // Auto Overdue Ticket

        if (setting('AUTO_OVERDUE_TICKET') == 'no') {
            $ticket->auto_overdue_ticket = null;
        } else {
            if (setting('AUTO_OVERDUE_TICKET_TIME') == '0') {
                $ticket->auto_overdue_ticket = null;
            } else {
                if (Auth::guard('customer')->check() && Auth::guard('customer')->user()) {
                    if ($ticket->status == 'Closed') {
                        $ticket->auto_overdue_ticket = null;
                    } else {
                        $ticket->auto_overdue_ticket = now()->addDays(setting('AUTO_OVERDUE_TICKET_TIME'));
                    }
                }
            }
        }
        // Auto Overdue Ticket
        if ($request->input('envato_id')) {

            $ticket->purchasecode = encrypt($request->input('envato_id'));
        }
        if ($request->input('envato_support')) {

            $ticket->purchasecodesupport = $request->input('envato_support');
        }
        $categoryfind = Category::find($request->category);
        $ticket->priority = $categoryfind->priority;
        if ($request->subscategory) {
            $ticket->subcategory = $request->subscategory;
        }
        $ticket->update();

        $customfields = Customfield::whereIn('displaytypes', ['both', 'createticket'])->get();

        foreach ($customfields as $customfield) {
            $ticketcustomfield = new TicketCustomfield();
            $ticketcustomfield->ticket_id = $ticket->id;
            $ticketcustomfield->fieldnames = $customfield->fieldnames;
            $ticketcustomfield->fieldtypes = $customfield->fieldtypes;
            if ($customfield->fieldtypes == 'checkbox') {
                if ($request->input('custom_' . $customfield->id) != null) {

                    $string = implode(',', $request->input('custom_' . $customfield->id));
                    $ticketcustomfield->values = $string;
                }

            }
            if ($customfield->fieldtypes != 'checkbox') {
                if ($customfield->fieldprivacy == '1') {
                    $ticketcustomfield->privacymode = $customfield->fieldprivacy;
                    $ticketcustomfield->values = encrypt($request->input('custom_' . $customfield->id));
                } else {

                    $ticketcustomfield->values = $request->input('custom_' . $customfield->id);
                }
            }
            $ticketcustomfield->save();

        }

        $ccmails = new CCMAILS();
        $ccmails->ticket_id = $ticket->id;
        $ccmails->ccemails = $request->ccmail;
        $ccmails->save();

        $tickethistory = new tickethistory();
        $tickethistory->ticket_id = $ticket->id;

        $output = '<div class="d-flex align-items-center">
            <div class="mt-0">
                <p class="mb-0 fs-12 mb-1">Status
            ';
        if($ticket->ticketnote->isEmpty()){
            if($ticket->overduestatus != null){
                $output .= '
                <span class="text-burnt-orange font-weight-semibold mx-1">'.$ticket->status.'</span>
                <span class="text-danger font-weight-semibold mx-1">'.$ticket->overduestatus.'</span>
                ';
            }else{
                $output .= '
                <span class="text-burnt-orange font-weight-semibold mx-1">'.$ticket->status.'</span>
                ';
            }

        }else{
            if($ticket->overduestatus != null){
                $output .= '
                <span class="text-burnt-orange font-weight-semibold mx-1">'.$ticket->status.'</span>
                <span class="text-danger font-weight-semibold mx-1">'.$ticket->overduestatus.'</span>
                <span class="text-warning font-weight-semibold mx-1">Note</span>
                ';
            }else{
                $output .= '
                <span class="text-burnt-orange font-weight-semibold mx-1">'.$ticket->status.'</span>
                <span class="text-warning font-weight-semibold mx-1">Note</span>
                ';
            }
        }

        $output .= '
            <p class="mb-0 fs-17 font-weight-semibold text-dark">'.$ticket->cust->username.'<span class="fs-11 mx-1 text-muted">(Created)</span></p>
        </div>
        <div class="ms-auto">
        <span class="float-end badge badge-danger-light">
            <span class="fs-11 font-weight-semibold">'.$ticket->cust->userType.'</span>
        </span>
        </div>

        </div>
        ';
        $tickethistory->ticketactions = $output;
        $tickethistory->save();


        foreach ($request->input('ticket', []) as $file) {
            $ticket->addMedia(public_path('uploads/ticket/' . $file))->toMediaCollection('ticket');
        }

        // Create a New ticket reply
        $notificationcat = $ticket->category->groupscategoryc()->get();
        $icc = array();
        if ($notificationcat->isNotEmpty()) {

            foreach ($notificationcat as $igc) {

                foreach ($igc->groupsc->groupsuser()->get() as $user) {
                    $icc[] .= $user->users_id;
                }
            }

            if (!$icc) {
                $admins = User::leftJoin('groups_users', 'groups_users.users_id', 'users.id')->whereNull('groups_users.groups_id')->whereNull('groups_users.users_id')->get();
                foreach ($admins as $admin) {
                    $admin->notify(new TicketCreateNotifications($ticket));
                }

            } else {

                $user = User::whereIn('id', $icc)->get();
                foreach ($user as $users) {
                    $users->notify(new TicketCreateNotifications($ticket));
                }
                $admins = User::leftJoin('groups_users', 'groups_users.users_id', 'users.id')->whereNull('groups_users.groups_id')->whereNull('groups_users.users_id')->get();
                foreach ($admins as $admin) {
                    if($admin->getRoleNames()[0] == 'superadmin'){
                        $admin->notify(new TicketCreateNotifications($ticket));
                    }
                }

            }
        } else {
            $admins = User::leftJoin('groups_users', 'groups_users.users_id', 'users.id')->whereNull('groups_users.groups_id')->whereNull('groups_users.users_id')->get();
            foreach ($admins as $admin) {
                $admin->notify(new TicketCreateNotifications($ticket));
            }
        }

        $request->session()->put('customerticket', Auth::guard('customer')->id());
        $ticketData = [
            'ticket_username' => $ticket->cust->username,
            'ticket_id' => $ticket->ticket_id,
            'ticket_title' => $ticket->subject,
            'ticket_description' => $ticket->message,
            'ticket_status' => $ticket->status,
            'ticket_customer_url' => route('loadmore.load_data', $ticket->ticket_id),
            'ticket_admin_url' => url('/administration/ticket-view/' . $ticket->ticket_id),
        ];



        try {
            // Create a New ticket reply
            $notificationcat = $ticket->category->groupscategoryc()->get();
            $icc = array();
            if ($notificationcat->isNotEmpty()) {

                foreach ($notificationcat as $igc) {

                    foreach ($igc->groupsc->groupsuser()->get() as $user) {
                        $icc[] .= $user->users_id;
                    }
                }

                if (!$icc) {
                    $admins = User::leftJoin('groups_users', 'groups_users.users_id', 'users.id')->whereNull('groups_users.groups_id')->whereNull('groups_users.users_id')->get();
                    foreach ($admins as $admin) {
                        if($admin->usetting->emailnotifyon == 1){
                            Mail::to($admin->email)
                                ->send(new mailmailablesend('admin_send_email_ticket_created', $ticketData));
                        }
                    }

                } else {

                    $user = User::whereIn('id', $icc)->get();
                    foreach ($user as $users) {
                        if($users->usetting->emailnotifyon == 1){
                            Mail::to($users->email)
                            ->send(new mailmailablesend('admin_send_email_ticket_created', $ticketData));
                        }
                    }
                    $admins = User::leftJoin('groups_users', 'groups_users.users_id', 'users.id')->whereNull('groups_users.groups_id')->whereNull('groups_users.users_id')->get();
                    foreach ($admins as $admin) {
                        if($admin->getRoleNames()[0] == 'superadmin' && $admin->usetting->emailnotifyon == 1){
                            Mail::to($admin->email)
                            ->send(new mailmailablesend('admin_send_email_ticket_created', $ticketData));
                        }
                    }

                }
            } else {
                $admins = User::leftJoin('groups_users', 'groups_users.users_id', 'users.id')->whereNull('groups_users.groups_id')->whereNull('groups_users.users_id')->get();
                foreach ($admins as $admin) {
                    if($admin->usetting->emailnotifyon == 1){
                        Mail::to($admin->email)
                            ->send(new mailmailablesend('admin_send_email_ticket_created', $ticketData));
                    }
                }
            }

            Mail::to($ticket->cust->email)
                ->send(new mailmailablesend('customer_send_ticket_created', $ticketData));

            Mail::to($ccemailsend->ccemails)
                ->send(new mailmailablesend('customer_send_ticket_created', $ticketData));

        } catch (\Exception$e) {
            return response()->json(['success' => lang('A ticket has been opened with the ticket ID', 'alerts') . $ticket->ticket_id], 200);
        }


        return response()->json(['success' => lang('A ticket has been opened with the ticket ID', 'alerts') . $ticket->ticket_id], 200);

    }

    public function storeMedia(Request $request)
    {
        $path = public_path('uploads/ticket');

        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        $file = $request->file('file');

        $name = gn_safe_upload_name($file);
        if ($name === null) {
            return response()->json(['error' => 'Ce type de fichier n’est pas autorisé.'], 422);
        }

        $file->move($path, $name);

        return response()->json([
            'name' => $name,
            'original_name' => $file->getClientOriginalName(),
        ]);
    }

    public function activeticket()
    {

        $title = Apptitle::first();
        $data['title'] = $title;

        $footertext = Footertext::first();
        $data['footertext'] = $footertext;

        $seopage = Seosetting::first();
        $data['seopage'] = $seopage;

        $post = Pages::all();
        $data['page'] = $post;

        $activetickets = Ticket::where('cust_id', Auth::guard('customer')->user()->id)->whereIn('status', ['New', 'Re-Open', 'Inprogress'])->latest('updated_at')->get();
        $data['activetickets'] = $activetickets;

        return view('user.ticket.viewticket.activeticket', compact('title', 'footertext'))->with($data);
    }

    public function closedticket()
    {

        $title = Apptitle::first();
        $data['title'] = $title;

        $footertext = Footertext::first();
        $data['footertext'] = $footertext;

        $seopage = Seosetting::first();
        $data['seopage'] = $seopage;

        $post = Pages::all();
        $data['page'] = $post;

        $closedtickets = Ticket::where('cust_id', Auth::guard('customer')->user()->id)->where('status', 'Closed')->latest('updated_at')->get();
        $data['closedtickets'] = $closedtickets;

        return view('user.ticket.viewticket.closedticket', compact('title', 'footertext'))->with($data);
    }

    public function onholdticket()
    {

        $title = Apptitle::first();
        $data['title'] = $title;

        $footertext = Footertext::first();
        $data['footertext'] = $footertext;

        $seopage = Seosetting::first();
        $data['seopage'] = $seopage;

        $post = Pages::all();
        $data['page'] = $post;

        $onholdtickets = Ticket::where('cust_id', Auth::guard('customer')->user()->id)->where('status', 'On-Hold')->latest('updated_at')->get();
        $data['onholdtickets'] = $onholdtickets;

        return view('user.ticket.viewticket.onholdticket', compact('title', 'footertext'))->with($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $req, $ticket_id)
    {
        $ticket = Ticket::where('ticket_id', $ticket_id)->firstOrFail();
        $comments = $ticket->comments()->with('ticket')->paginate(5);
        $category = $ticket->category;

        $title = Apptitle::first();
        $data['title'] = $title;

        $footertext = Footertext::first();
        $data['footertext'] = $footertext;

        $seopage = Seosetting::first();
        $data['seopage'] = $seopage;

        $post = Pages::all();
        $data['page'] = $post;

        // customer restrict to reply for the ticket.
        $commentsNull = $ticket->comments()->get();
        if(setting('RESTRICT_TO_REPLY_TICKET') == 'on' && $commentsNull->all() != null && setting('MAXIMUM_ALLOW_REPLIES') > 0){
            $latestone = $ticket->comments()->latest('created_at')->first();

            if($latestone->user_id == null){
                $ticComment = $ticket->comments()->where('cust_id', Auth::guard('customer')->user()->id)->latest('created_at')->first();
                if($ticComment != null){
                    if($ticComment->created_at->timezone(setting('default_timezone'))->format('Y-m-d') == now()->timezone(setting('default_timezone'))->format('Y-m-d')){

                        $star1 = $ticComment->created_at->timezone(setting('default_timezone'))->subHour(setting('REPLY_ALLOW_IN_HOURS'))->format('Y-m-d H:i:s');
                        $star2 = $ticComment->created_at->timezone(setting('default_timezone'))->format('Y-m-d H:i:s');
                        $star3 = $ticComment->created_at->timezone(setting('default_timezone'))->addHour(setting('REPLY_ALLOW_IN_HOURS'))->format('Y-m-d H:i:s');

                        if($star3 < now()->timezone(setting('default_timezone'))->format('Y-m-d H:i:s')){
                            if ($ticket->cust_id == Auth::guard('customer')->id()) {
                                $createdcount = '';
                                if (request()->ajax()) {
                                    $view = view('user.ticket.showticketdata', compact('comments', 'createdcount'))->render();
                                    return response()->json(['html' => $view]);
                                }

                                return view('user.ticket.showticket', compact('ticket', 'category', 'comments', 'title', 'footertext', 'createdcount'))->with($data);
                            }else{
                                return back()->with('error', lang('Cannot Access This Ticket'));
                            }
                        }else{
                            $createdcount = $ticket->comments()->where('cust_id', Auth::guard('customer')->user()->id)->count();
                            if ($ticket->cust_id == Auth::guard('customer')->id()) {
                                if (request()->ajax()) {
                                    $view = view('user.ticket.showticketdata', compact('comments', 'createdcount'))->render();
                                    return response()->json(['html' => $view]);
                                }

                                return view('user.ticket.showticket', compact('ticket', 'category', 'comments', 'title', 'footertext', 'createdcount'))->with($data);
                            }else{
                                return back()->with('error', lang('Cannot Access This Ticket'));
                            }
                        }
                    }else{
                        if ($ticket->cust_id == Auth::guard('customer')->id()) {
                            $createdcount = '';
                            if (request()->ajax()) {
                                $view = view('user.ticket.showticketdata', compact('comments', 'createdcount'))->render();
                                return response()->json(['html' => $view]);
                            }

                            return view('user.ticket.showticket', compact('ticket', 'category', 'comments', 'title', 'footertext', 'createdcount'))->with($data);
                        }else{
                            return back()->with('error', lang('Cannot Access This Ticket'));
                        }
                    }
                }
            }else{
                if ($ticket->cust_id == Auth::guard('customer')->id()) {
                    $createdcount = '';
                    if (request()->ajax()) {
                        $view = view('user.ticket.showticketdata', compact('comments', 'createdcount'))->render();
                        return response()->json(['html' => $view]);
                    }

                    return view('user.ticket.showticket', compact('ticket', 'category', 'comments', 'title', 'footertext', 'createdcount'))->with($data);
                }else{
                    return back()->with('error', lang('Cannot Access This Ticket'));
                }
            }
        }else{
            if ($ticket->cust_id == Auth::guard('customer')->id()) {
                $createdcount = '';
                if (request()->ajax()) {
                    $view = view('user.ticket.showticketdata', compact('comments', 'createdcount'))->render();
                    return response()->json(['html' => $view]);
                }

                return view('user.ticket.showticket', compact('ticket', 'category', 'comments', 'title', 'footertext', 'createdcount'))->with($data);
            }else{
                return back()->with('error', lang('Cannot Access This Ticket'));
            }
        }

    }

    /**
     * Close the specified ticket.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function close(Request $request, $ticket_id)
    {

        $ticket = Ticket::where('ticket_id', $ticket_id)->firstOrFail();

        $ticket->status = 'Re-Open';
        $ticket->replystatus = null;
        $ticket->closedby_user = null;

        $ticket->update();


        $tickethistory = new tickethistory();
        $tickethistory->ticket_id = $ticket->id;

        $output = '<div class="d-flex align-items-center">
            <div class="mt-0">
                <p class="mb-0 fs-12 mb-1">Status
            ';
        if($ticket->ticketnote->isEmpty()){
            if($ticket->overduestatus != null){
                $output .= '
                <span class="text-teal font-weight-semibold mx-1">'.$ticket->status.'</span>
                <span class="text-danger font-weight-semibold mx-1">'.$ticket->overduestatus.'</span>
                ';
            }else{
                $output .= '
                <span class="text-teal font-weight-semibold mx-1">'.$ticket->status.'</span>
                ';
            }

        }else{
            if($ticket->overduestatus != null){
                $output .= '
                <span class="text-teal font-weight-semibold mx-1">'.$ticket->status.'</span>
                <span class="text-danger font-weight-semibold mx-1">'.$ticket->overduestatus.'</span>
                <span class="text-warning font-weight-semibold mx-1">Note</span>
                ';
            }else{
                $output .= '
                <span class="text-teal font-weight-semibold mx-1">'.$ticket->status.'</span>
                <span class="text-warning font-weight-semibold mx-1">Note</span>
                ';
            }
        }

        $output .= '
            <p class="mb-0 fs-17 font-weight-semibold text-dark">'.Auth::guard('customer')->user()->username.'<span class="fs-11 mx-1 text-muted">(Re-opened)</span></p>
        </div>
        <div class="ms-auto">
        <span class="float-end badge badge-danger-light">
            <span class="fs-11 font-weight-semibold">'.Auth::guard('customer')->user()->userType.'</span>
        </span>
        </div>

        </div>
        ';
        $tickethistory->ticketactions = $output;
        $tickethistory->save();

        // // Create a New ticket reply
        // if($ticket->category)
        // {
        //     $notificationcat = $ticket->category->groupscategoryc()->get();
        //     $icc = array();
        //     if ($notificationcat->isNotEmpty()) {

        //         foreach ($notificationcat as $igc) {

        //             foreach ($igc->groupsc->groupsuser()->get() as $user) {
        //                 $icc[] .= $user->users_id;
        //             }
        //         }

        //         if (!$icc) {
        //             $admins = User::leftJoin('groups_users', 'groups_users.users_id', 'users.id')->whereNull('groups_users.groups_id')->whereNull('groups_users.users_id')->get();
        //             foreach ($admins as $admin) {
        //                 $admin->notify(new TicketCreateNotifications($ticket));
        //             }

        //         } else {

        //             $user = User::whereIn('id', $icc)->get();
        //             foreach ($user as $users) {
        //                 $users->notify(new TicketCreateNotifications($ticket));
        //             }
        //             $admins = User::leftJoin('groups_users', 'groups_users.users_id', 'users.id')->whereNull('groups_users.groups_id')->whereNull('groups_users.users_id')->get();
        //             foreach ($admins as $admin) {
        //                 $admin->notify(new TicketCreateNotifications($ticket));
        //             }

        //         }
        //     } else {
        //         $admins = User::leftJoin('groups_users', 'groups_users.users_id', 'users.id')->whereNull('groups_users.groups_id')->whereNull('groups_users.users_id')->get();
        //         foreach ($admins as $admin) {
        //             $admin->notify(new TicketCreateNotifications($ticket));
        //         }
        //     }
        // }
        // // Notification category Empty
        // if(!$ticket->category)
        // {
        //     $admins = User::leftJoin('groups_users', 'groups_users.users_id', 'users.id')->whereNull('groups_users.groups_id')->whereNull('groups_users.users_id')->get();
        //     foreach($admins as $admin){
        //         if($admin->getRoleNames()[0] != 'superadmin' && $admin->usetting->emailnotifyon == 1){
        //             $admin->notify(new TicketCreateNotifications($ticket));
        //         }
        //     }

        // }

        $ccemailsend = CCMAILS::where('ticket_id', $ticket->id)->first();

        $ticketData = [
            'ticket_username' => $ticket->cust->username,
            'ticket_id' => $ticket->ticket_id,
            'ticket_title' => $ticket->subject,
            'ticket_description' => $ticket->message,
            'ticket_status' => $ticket->status,
            'ticket_customer_url' => route('loadmore.load_data', $ticket->ticket_id),
            'ticket_admin_url' => url('/administration/ticket-view/' . $ticket->ticket_id),
        ];

        try {

            if($ticket->category)
            {

                $notificationcatss = $ticket->category->groupscategoryc()->get();
                $icc = array();
                if ($notificationcatss->isNotEmpty()) {

                    foreach ($notificationcatss as $igc) {

                        foreach ($igc->groupsc->groupsuser()->get() as $user) {
                            $icc[] .= $user->users_id;
                        }
                    }

                    if (!$icc) {
                        $admins = User::leftJoin('groups_users', 'groups_users.users_id', 'users.id')->whereNull('groups_users.groups_id')->whereNull('groups_users.users_id')->get();
                        foreach ($admins as $admin) {
                            $admin->notify(new TicketCreateNotifications($ticket));
                            if($admin->usetting->emailnotifyon == 1){
                                Mail::to($admin->email)
                                    ->send(new mailmailablesend('admin_sendemail_whenticketreopen', $ticketData));
                            }
                        }

                    } else {

                        if($ticket->myassignuser){
                            $assignee = $ticket->ticketassignmutliples;
                            foreach($assignee as $assignees){
                                $user = User::where('id',$assignees->toassignuser_id)->get();
                                foreach($user as $users){
                                    if($users->id == $assignees->toassignuser_id && $users->getRoleNames()[0] != 'superadmin'){
                                        $users->notify(new TicketCreateNotifications($ticket));
                                        if($users->usetting->emailnotifyon == 1){
                                            Mail::to($users->email)
                                            ->send( new mailmailablesend( 'admin_sendemail_whenticketreopen', $ticketData ) );
                                        }
                                    }
                                }
                            }
                        }else if ($ticket->selfassignuser_id) {
                            $self = User::findOrFail($ticket->selfassignuser_id);
                            if($self->getRoleNames()[0] != 'superadmin'){
                                $self->notify(new TicketCreateNotifications($ticket));
                                if($self->usetting->emailnotifyon == 1){
                                    Mail::to($self->email)
                                    ->send( new mailmailablesend( 'admin_sendemail_whenticketreopen', $ticketData ) );
                                }
                            }
                        }else if($icc ){
                            $user = User::whereIn('id', $icc)->get();
                            foreach($user as $users){
                                $users->notify(new TicketCreateNotifications($ticket));
                                if($users->usetting->emailnotifyon == 1){
                                    Mail::to($users->email)
                                    ->send( new mailmailablesend( 'admin_sendemail_whenticketreopen', $ticketData ) );
                                }
                            }
                        }else {
                            $users = User::leftJoin('groups_users', 'groups_users.users_id', 'users.id')->whereNull('groups_users.groups_id')->whereNull('groups_users.users_id')->get();
                            foreach($users as $user){
                                if($user->getRoleNames()[0] != 'superadmin'){
                                    $user->notify(new TicketCreateNotifications($ticket));
                                    if($user->usetting->emailnotifyon == 1){
                                        Mail::to($user->email)
                                        ->send( new mailmailablesend( 'admin_sendemail_whenticketreopen', $ticketData ) );
                                    }
                                }
                            }
                        }

                    }
                } else {
                    if($ticket->myassignuser){
                        $assignee = $ticket->ticketassignmutliples;
                        foreach($assignee as $assignees){
                            $user = User::where('id',$assignees->toassignuser_id)->get();
                            foreach($user as $users){
                                if($users->id == $assignees->toassignuser_id && $users->getRoleNames()[0] != 'superadmin'){
                                    $users->notify(new TicketCreateNotifications($ticket));
                                    if($users->usetting->emailnotifyon == 1){
                                        Mail::to($users->email)
                                        ->send( new mailmailablesend( 'admin_sendemail_whenticketreopen', $ticketData ) );
                                    }
                                }
                            }
                        }
                    } else if ($ticket->selfassignuser_id) {
                        $self = User::findOrFail($ticket->selfassignuser_id);
                        if($self->getRoleNames()[0] != 'superadmin'){
                            $self->notify(new TicketCreateNotifications($ticket));
                            if($self->usetting->emailnotifyon == 1){
                                Mail::to($self->email)
                                ->send( new mailmailablesend( 'admin_sendemail_whenticketreopen', $ticketData ) );
                            }
                        }
                    } else {

                        $users = User::leftJoin('groups_users', 'groups_users.users_id', 'users.id')->whereNull('groups_users.groups_id')->whereNull('groups_users.users_id')->get();
                        foreach($users as $user){
                            if($user->getRoleNames()[0] != 'superadmin'){
                                $user->notify(new TicketCreateNotifications($ticket));
                                if($user->usetting->emailnotifyon == 1){
                                    Mail::to($user->email)
                                    ->send( new mailmailablesend( 'admin_sendemail_whenticketreopen', $ticketData ) );
                                }
                            }
                        }
                    }
                }
            }

            $admins = User::leftJoin('groups_users','groups_users.users_id','users.id')->whereNull('groups_users.groups_id')->whereNull('groups_users.users_id')->get();
            foreach($admins as $admin){
                if($admin->getRoleNames()[0] == 'superadmin'){
                    $admin->notify(new TicketCreateNotifications($ticket));
                    if($admin->usetting->emailnotifyon == 1){
                        Mail::to($admin->email)
                        ->send( new mailmailablesend( 'admin_sendemail_whenticketreopen', $ticketData ) );
                    }
                }
            }

            Mail::to($ticket->cust->email)
                ->send(new mailmailablesend('customer_send_ticket_reopen', $ticketData));

            Mail::to($ccemailsend->ccemails)
                ->send(new mailmailablesend('customer_send_ticket_reopen', $ticketData));

        } catch (\Exception$e) {
            return redirect()->back()->with("success", lang('The ticket has been successfully reopened.', 'alerts'));
        }

        return redirect()->back()->with("success", lang('The ticket has been successfully reopened.', 'alerts'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $ticket = Ticket::where('cust_id', Auth::guard('customer')->user()->id)->findOrFail($id);
        $comment = $ticket->comments()->get();

        if (count($comment) > 0) {
            $media = $ticket->getMedia('ticket');

            foreach ($media as $media) {

                $media->delete();

            }
            $medias = $ticket->comments()->firstOrFail()->getMedia('comments');

            foreach ($medias as $mediass) {

                $mediass->delete();

            }
            $comment->each->delete();
            $ticket->delete();

            $tickethistory = new tickethistory();
            $tickethistory->ticket_id = $ticket->id;

            $output = '<div class="d-flex align-items-center">
                <div class="mt-0">
                    <p class="mb-0 fs-12 mb-1">Status
                ';
            if($ticket->ticketnote->isEmpty()){
                if($ticket->overduestatus != null){
                    $output .= '
                    <span class="text-teal font-weight-semibold mx-1">'.$ticket->status.'</span>
                    <span class="text-danger font-weight-semibold mx-1">'.$ticket->overduestatus.'</span>
                    ';
                }else{
                    $output .= '
                    <span class="text-teal font-weight-semibold mx-1">'.$ticket->status.'</span>
                    ';
                }

            }else{
                if($ticket->overduestatus != null){
                    $output .= '
                    <span class="text-teal font-weight-semibold mx-1">'.$ticket->status.'</span>
                    <span class="text-danger font-weight-semibold mx-1">'.$ticket->overduestatus.'</span>
                    <span class="text-warning font-weight-semibold mx-1">Note</span>
                    ';
                }else{
                    $output .= '
                    <span class="text-teal font-weight-semibold mx-1">'.$ticket->status.'</span>
                    <span class="text-warning font-weight-semibold mx-1">Note</span>
                    ';
                }
            }

            $output .= '
                <p class="mb-0 fs-17 font-weight-semibold text-dark">'.Auth::guard('customer')->user()->username.'<span class="fs-11 mx-1 text-muted">(Ticket Deleted)</span></p>
            </div>
            <div class="ms-auto">
            <span class="float-end badge badge-danger-light">
                <span class="fs-11 font-weight-semibold">'.Auth::guard('customer')->user()->userType.'</span>
            </span>
            </div>

            </div>
            ';
            $tickethistory->ticketactions = $output;
            $tickethistory->save();

            return response()->json(['success' => lang('The ticket was successfully deleted.', 'alerts')]);
        } else {

            $media = $ticket->getMedia('ticket');

            foreach ($media as $media) {

                $media->delete();

            }
            $ticket->delete();

            $tickethistory = new tickethistory();
            $tickethistory->ticket_id = $ticket->id;

            $output = '<div class="d-flex align-items-center">
                <div class="mt-0">
                    <p class="mb-0 fs-12 mb-1">Status
                ';
            if($ticket->ticketnote->isEmpty()){
                if($ticket->overduestatus != null){
                    $output .= '
                    <span class="text-teal font-weight-semibold mx-1">'.$ticket->status.'</span>
                    <span class="text-danger font-weight-semibold mx-1">'.$ticket->overduestatus.'</span>
                    ';
                }else{
                    $output .= '
                    <span class="text-teal font-weight-semibold mx-1">'.$ticket->status.'</span>
                    ';
                }

            }else{
                if($ticket->overduestatus != null){
                    $output .= '
                    <span class="text-teal font-weight-semibold mx-1">'.$ticket->status.'</span>
                    <span class="text-danger font-weight-semibold mx-1">'.$ticket->overduestatus.'</span>
                    <span class="text-warning font-weight-semibold mx-1">Note</span>
                    ';
                }else{
                    $output .= '
                    <span class="text-teal font-weight-semibold mx-1">'.$ticket->status.'</span>
                    <span class="text-warning font-weight-semibold mx-1">Note</span>
                    ';
                }
            }

            $output .= '
                <p class="mb-0 fs-17 font-weight-semibold text-dark">'.Auth::guard('customer')->user()->username.'<span class="fs-11 mx-1 text-muted">(Ticket Deleted)</span></p>
            </div>
            <div class="ms-auto">
            <span class="float-end badge badge-danger-light">
                <span class="fs-11 font-weight-semibold">'.Auth::guard('customer')->user()->userType.'</span>
            </span>
            </div>

            </div>
            ';
            $tickethistory->ticketactions = $output;
            $tickethistory->save();


            return response()->json(['success' => lang('The ticket was successfully deleted.', 'alerts')]);

        }
    }

    public function ticketmassdestroy(Request $request)
    {
        $student_id_array = $request->input('id');

        $tickets = Ticket::where('cust_id', Auth::guard('customer')->user()->id)->whereIn('id', $student_id_array)->get();

        foreach ($tickets as $ticket) {
            $comment = $ticket->comments()->get();

            if (count($comment) > 0) {
                $media = $ticket->getMedia('ticket');

                foreach ($media as $media) {

                    $media->delete();

                }
                $medias = $ticket->comments()->firstOrFail()->getMedia('comments');

                foreach ($medias as $mediass) {

                    $mediass->delete();

                }
                $comment->each->delete();
                $ticket->delete();

                $tickethistory = new tickethistory();
                $tickethistory->ticket_id = $ticket->id;

                $output = '<div class="d-flex align-items-center">
                    <div class="mt-0">
                        <p class="mb-0 fs-12 mb-1">Status
                    ';
                if($ticket->ticketnote->isEmpty()){
                    if($ticket->overduestatus != null){
                        $output .= '
                        <span class="text-danger font-weight-semibold mx-1">'.$ticket->status.'</span>
                        <span class="text-danger font-weight-semibold mx-1">'.$ticket->overduestatus.'</span>
                        ';
                    }else{
                        $output .= '
                        <span class="text-danger font-weight-semibold mx-1">'.$ticket->status.'</span>
                        ';
                    }

                }else{
                    if($ticket->overduestatus != null){
                        $output .= '
                        <span class="text-danger font-weight-semibold mx-1">'.$ticket->status.'</span>
                        <span class="text-danger font-weight-semibold mx-1">'.$ticket->overduestatus.'</span>
                        <span class="text-warning font-weight-semibold mx-1">Note</span>
                        ';
                    }else{
                        $output .= '
                        <span class="text-danger font-weight-semibold mx-1">'.$ticket->status.'</span>
                        <span class="text-warning font-weight-semibold mx-1">Note</span>
                        ';
                    }
                }

                $output .= '
                    <p class="mb-0 fs-17 font-weight-semibold text-dark">'.Auth::guard('customer')->user()->username.'<span class="fs-11 mx-1 text-muted">(Ticket Deleted)</span></p>
                </div>
                <div class="ms-auto">
                <span class="float-end badge badge-primary-light">
                    <span class="fs-11 font-weight-semibold">'.Auth::guard('customer')->user()->userType.'</span>
                </span>
                </div>

                </div>
                ';
                $tickethistory->ticketactions = $output;
                $tickethistory->save();
                foreach($ticket->ticket_history as $deletetickethistory)
                {
                    $deletetickethistory->delete();
                }

                return response()->json(['success' => lang('The ticket was successfully deleted.', 'alerts')]);
            } else {

                $media = $ticket->getMedia('ticket');

                foreach ($media as $media) {

                    $media->delete();

                }
                $ticket->delete();

                $tickethistory = new tickethistory();
                $tickethistory->ticket_id = $ticket->id;

                $output = '<div class="d-flex align-items-center">
                    <div class="mt-0">
                        <p class="mb-0 fs-12 mb-1">Status
                    ';
                if($ticket->ticketnote->isEmpty()){
                    if($ticket->overduestatus != null){
                        $output .= '
                        <span class="text-danger font-weight-semibold mx-1">'.$ticket->status.'</span>
                        <span class="text-danger font-weight-semibold mx-1">'.$ticket->overduestatus.'</span>
                        ';
                    }else{
                        $output .= '
                        <span class="text-danger font-weight-semibold mx-1">'.$ticket->status.'</span>
                        ';
                    }

                }else{
                    if($ticket->overduestatus != null){
                        $output .= '
                        <span class="text-danger font-weight-semibold mx-1">'.$ticket->status.'</span>
                        <span class="text-danger font-weight-semibold mx-1">'.$ticket->overduestatus.'</span>
                        <span class="text-warning font-weight-semibold mx-1">Note</span>
                        ';
                    }else{
                        $output .= '
                        <span class="text-danger font-weight-semibold mx-1">'.$ticket->status.'</span>
                        <span class="text-warning font-weight-semibold mx-1">Note</span>
                        ';
                    }
                }

                $output .= '
                    <p class="mb-0 fs-17 font-weight-semibold text-dark">'.Auth::guard('customer')->user()->username.'<span class="fs-11 mx-1 text-muted">(Ticket Deleted)</span></p>
                </div>
                <div class="ms-auto">
                <span class="float-end badge badge-primary-light">
                    <span class="fs-11 font-weight-semibold">'.Auth::guard('customer')->user()->userType.'</span>
                </span>
                </div>

                </div>
                ';
                $tickethistory->ticketactions = $output;
                $tickethistory->save();
                foreach($ticket->ticket_history as $deletetickethistory)
                {
                    $deletetickethistory->delete();
                }
            }
        }
        return response()->json(['success' => lang('The ticket was successfully deleted.', 'alerts')]);

    }

    public function sublist(Request $request)
    {

        $parent_id = $request->cat_id;

        $subcategories = Projects::select('projects.*', 'projects_categories.category_id')->join('projects_categories', 'projects_categories.projects_id', 'projects.id')
            ->where('projects_categories.category_id', $parent_id)
            ->get();

        return response()->json([
            'subcategories' => $subcategories,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function rating($ticket_id)
    {

        $ratingticket = Ratingtoken::where('token', $ticket_id)->first();
        if (!$ratingticket) {

            return redirect('espace-client')->with("error", lang('Your rating has already been submitted.'));
        }
        $ticket = Ticket::where('id', $ratingticket->ticket_id)->first();
        $title = Apptitle::first();
        $data['title'] = $title;

        $footertext = Footertext::first();
        $data['footertext'] = $footertext;

        $seopage = Seosetting::first();
        $data['seopage'] = $seopage;

        $post = Pages::all();
        $data['page'] = $post;

        $rating = $ticket->comments()->whereNotNull('user_id')->get();
        $comment = Comment::select('user_id')->where('ticket_id', $ticket->id)->distinct()->get();
        // $ticket->comments()->select('user_id')->distinct()->get();
        if ($rating->isEmpty()) {
            return redirect()->back();
        } else {
            return view('user.ticket.rating', compact('ticket', 'comment', 'title', 'footertext'))->with($data);
        }

    }

    /// rating system ///
    public function star5($id)
    {

        $user = User::with('usetting')->findorFail($id);
        $user->usetting->increment('star5');
        $user->usetting->update();

        return redirect('espace-client')->with('success', lang('Thank you for rating us.', 'alerts'));

    }

    public function star4($id)
    {

        $user = User::with('usetting')->findorFail($id);
        $user->usetting->increment('star4');
        $user->usetting->update();

        return redirect('espace-client')->with('success', lang('Thank you for rating us.', 'alerts'));

    }

    public function star3($id)
    {

        $user = User::with('usetting')->findorFail($id);
        $user->usetting->increment('star3');
        $user->usetting->update();

        return redirect('espace-client')->with('success', lang('Thank you for rating us.', 'alerts'));

    }

    public function star2($id)
    {

        $user = User::with('usetting')->findorFail($id);
        $user->usetting->increment('star2');
        $user->usetting->update();

        return redirect('espace-client')->with('success', lang('Thank you for rating us.', 'alerts'));

    }

    public function star1($id)
    {

        $user = User::with('usetting')->findorFail($id);

        $user->usetting->increment('star1');
        $user->usetting->update();
        return redirect('espace-client')->with('success', lang('Thank you for rating us.', 'alerts'));
    }

    public function ticketrating(Request $req)
    {

        $ticketfinding = Ticket::find($req->ticket_id);

        $ratingticket = Userrating::where('ticket_id', $req->ticket_id)->first();
        if ($ratingticket) {
            $ratingticket->ratingstar = $req->ratingticket;
            $ratingticket->ratingcomment = $req->ratingcomment;
            $ratingticket->update();

            $employeeratingloop = Employeerating::where('urating_id', $ratingticket->id)->get();
            foreach ($employeeratingloop as $employeeratings) {
                $employeeratings->delete();
            }

            $commentsfind = $ticketfinding->comments()->where('user_id', '!=', null)->distinct()->get();
            foreach ($commentsfind as $commentfinds) {
                $employeerating = new Employeerating();
                $employeerating->urating_id = $ratingticket->id;
                $employeerating->rating = $ratingticket->ratingstar;
                $employeerating->user_id = $commentfinds->user_id;
                $employeerating->save();
            }

        } else {

            $ticketrating = new Userrating();
            $ticketrating->ticket_id = $req->ticket_id;
            $ticketrating->ratingstar = $req->ratingticket;
            $ticketrating->ratingcomment = $req->ratingcomment;
            $ticketrating->cust_id = $ticketfinding->cust->id;
            $ticketrating->save();

            $ticketsfind = Ticket::where('id', $req->ticket_id)->first();
            $commentsfind = $ticketsfind->comments()->where('user_id', '!=', null)->distinct()->get();
            foreach ($commentsfind as $commentfinds) {
                $employeerating = new Employeerating();
                $employeerating->urating_id = $ticketrating->id;
                $employeerating->rating = $ticketrating->ratingstar;
                $employeerating->user_id = $commentfinds->user_id;
                $employeerating->save();
            }

        }
        $ratingticketdelete = Ratingtoken::where('ticket_id', $req->ticket_id)->first();
        $ratingticketdelete->delete();

        return redirect('/')->with('success', lang('Thank you for rating us.', 'alerts'));

    }
    /// end rating system ///

    // Print Ticket
    public function pdfmake($id)
    {
        $title = Apptitle::first();
        $data['title'] = $title;

        $footertext = Footertext::first();
        $data['footertext'] = $footertext;

        $seopage = Seosetting::first();
        $data['seopage'] = $seopage;

        $page = Pages::all();
        $data['page'] = $page;

        $showprintticket = Ticket::findOrFail($id);
        $data['showprintticket'] = $showprintticket;

        return view('user.ticket.ticketshowpdf')->with($data);

    }

}
