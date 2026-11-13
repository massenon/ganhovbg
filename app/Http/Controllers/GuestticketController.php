<?php

namespace App\Http\Controllers;

use App\Mail\mailmailablesend;
use App\Models\Apptitle;
use App\Models\Articles\Article;
use App\Models\CCMAILS;
use App\Models\Customer;
use App\Models\CustomerSetting;
use App\Models\Customfield;
use App\Models\Employeerating;
use App\Models\Footertext;
use App\Models\Pages;
use App\Models\Projects;
use App\Models\Ratingtoken;
use App\Models\Seosetting;
use App\Models\SocialAuthSetting;
use App\Models\Subcategorychild;
use App\Models\TicketCustomfield;
use App\Models\Ticket\Category;
use App\Models\Ticket\Comment;
use App\Models\Ticket\Ticket;
use App\Models\User;
use App\Models\Userrating;
use App\Models\VerifyOtp;
use App\Notifications\TicketCreateNotifications;
use Auth;
use GeoIP;
use Illuminate\Http\Request;
use Mail;
use Modules\Uhelpupdate\Entities\APIData;
use Modules\Uhelpupdate\Entities\CategoryEnvato;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use App\Models\tickethistory;


class GuestticketController extends Controller
{
    public function index()
    {
        if (setting('GUEST_TICKET') == 'yes') {
            $tickets = Ticket::paginate(10);
            $categories = Category::whereIn('display', ['ticket', 'both'])->where('status', '1')
                ->get();
            $data['categories'] = $categories;

            $title = Apptitle::first();
            $data['title'] = $title;

            $seopage = Seosetting::first();
            $data['seopage'] = $seopage;

            $post = Pages::all();
            $data['page'] = $post;

            $footertext = Footertext::first();
            $data['footertext'] = $footertext;

            $socialAuthSettings = SocialAuthSetting::first();
            $data['socialAuthSettings'] = $socialAuthSettings;

            $recentarticles = Article::latest()->paginate(5);
            $data['recentarticles'] = $recentarticles;

            $populararticle = Article::orderBy('views', 'desc')->latest()->paginate(5);
            $data['populararticles'] = $populararticle;

            $customfields = Customfield::whereIn('displaytypes', ['both', 'createticket'])->where('status','1')->get();
            $data['customfields'] = $customfields;

            if (setting('GUEST_TICKET_OTP') == 'no') {

                return view('guestticket.index', compact('tickets', 'categories'))->with($data);
            } else {

                return view('guestticket.guestticketnootp', compact('tickets', 'categories'))->with($data);
            }
        }else{
            return redirect()->back()->with('error','You cannot have access for this guest ticket create.');
        }

    }

    // Guest Ticket Creating
    public function gueststore(Request $request)
    {
        $categories = CategoryEnvato::where('category_id',$request->category)->first();

        if(setting('ENVATO_ON') == 'on' && $categories != null && $request->envato_id == 'undefined'){
            return response()->json(['message' => 'envatoerror', 'error' => lang('Please enter valid details to create a ticket.', 'alerts')], 200);
        }

        if ($request->session()->has('sessionemail')) {
            $emailvalidate = $request->session()->get('sessionemail');
        } else {
            $emailvalidate = null;
        }

        if (setting('CAPTCHATYPE') == 'off') {
            $this->validate($request, [
                'subject' => 'required|max:255',
                'category' => 'required',
                'message' => 'required',
                'verifyotp' => 'required',
                'agree_terms' =>  'required|in:agreed',

            ]);
        } else {
            if (setting('CAPTCHATYPE') == 'manual') {
                if (setting('RECAPTCH_ENABLE_GUEST') == 'yes') {
                    $request->validate([
                        'subject' => 'required|max:255',
                        'category' => 'required',
                        'message' => 'required',
                        'captcha' => ['required', 'captcha'],
                        'verifyotp' => 'required',
                        'agree_terms' =>  'required|in:agreed',
                    ]);

                } else {
                    $request->validate([
                        'subject' => 'required|max:255',
                        'category' => 'required',
                        'message' => 'required',
                        'verifyotp' => 'required',
                        'agree_terms' =>  'required|in:agreed',

                    ]);

                }

            }
            if (setting('CAPTCHATYPE') == 'google') {
                if (setting('RECAPTCH_ENABLE_GUEST') == 'yes') {
                    $request->validate([
                        'subject' => 'required|max:255',
                        'category' => 'required',
                        'message' => 'required',
                        'g-recaptcha-response' => 'required|recaptcha',
                        'verifyotp' => 'required',
                        'agree_terms' =>  'required|in:agreed',

                    ]);

                } else {
                    $request->validate([
                        'subject' => 'required|max:255',
                        'category' => 'required',
                        'message' => 'required',
                        'verifyotp' => 'required',
                        'agree_terms' =>  'required|in:agreed',

                    ]);
                }
            }
        }

        $guest = Customer::where('email', $emailvalidate)->first();
        if ($guest) {
            if ($guest->userType == 'Guest') {
                $ticket = Ticket::create([
                    'subject' => $request->input('subject'),
                    'cust_id' => $guest->id,
                    'category_id' => $request->input('category'),
                    'message' => $request->input('message'),
                    'project' => $request->input('project'),
                    'status' => 'New',
                ]);
                $ticket = Ticket::find($ticket->id);
                $ticket->ticket_id = setting('CUSTOMER_TICKETID') . 'G-' . $ticket->id;

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

                // Auto Overdue Ticket
                if (setting('AUTO_OVERDUE_TICKET') == 'no') {
                    $ticket->auto_overdue_ticket = null;
                    $ticket->overduestatus = null;
                } else {
                    if (setting('AUTO_OVERDUE_TICKET_TIME') == '0') {
                        $ticket->auto_overdue_ticket = null;
                        $ticket->overduestatus = null;
                    } else {

                        if ($ticket->status == 'Closed') {
                            $ticket->auto_overdue_ticket = null;
                            $ticket->overduestatus = null;
                        } else {
                            $ticket->auto_overdue_ticket = now()->addDays(setting('AUTO_OVERDUE_TICKET_TIME'));
                            $ticket->overduestatus = null;
                        }

                    }
                }
                // End Auto Overdue Ticket
                $ticket->update();

                $geolocation = GeoIP::getLocation(request()->getClientIp());
                $custupdate = Customer::find($ticket->cust_id);
                $custupdate->last_login_ip = $geolocation->ip;
                $custupdate->timezone = $geolocation->timezone;
                $custupdate->country = $geolocation->country;
                $custupdate->update();

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

                foreach ($request->input('ticket', []) as $file) {
                    $ticket->addMedia(public_path('uploads/guestticket/' . $file))->toMediaCollection('ticket');
                }

                // Create a New ticket reply
                if($ticket->category){
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
                                $admin->notify(new TicketCreateNotifications($ticket));
                            }

                        }
                    } else {
                        $admins = User::leftJoin('groups_users', 'groups_users.users_id', 'users.id')->whereNull('groups_users.groups_id')->whereNull('groups_users.users_id')->get();
                        foreach ($admins as $admin) {
                            $admin->notify(new TicketCreateNotifications($ticket));
                        }
                    }
                }
                $verifyUser = VerifyOtp::where('type', 'guestotp')->where('otp', $request->verifyotp)->first();
                if ($verifyUser) {

                    $verifyUser->delete();
                }

                $guestdetailssession = session()->put('guestdetailssession', $ticket->ticket_id);
                $guestimageaccess = session()->put('guestimageaccess', $ticket->ticket_id);

                $ccemailsend = CCMAILS::where('ticket_id', $ticket->id)->first();

                $ticketData = [
                    'ticket_username' => $ticket->cust->username,
                    'ticket_id' => $ticket->ticket_id,
                    'ticket_title' => $ticket->subject,
                    'ticket_status' => $ticket->status,
                    'ticket_description' => $ticket->message,
                    'ticket_customer_url' => route('guest.ticketdetailshow', $ticket->ticket_id),
                    'ticket_admin_url' => url('/administration/ticket-view/' . $ticket->ticket_id),
                ];
                try {

                    if($ticket->category){
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
                                    if($admin->usetting->emailnotifyon == 1){
                                        Mail::to($admin->email)
                                            ->send(new mailmailablesend('admin_send_email_ticket_created', $ticketData));
                                    }
                                }

                            }
                        } else {
                            $admins = User::leftJoin('groups_users', 'groups_users.users_id', 'users.id')->whereNull('groups_users.groups_id')->whereNull('groups_users.users_id')->get();
                            foreach ($admins as $admin) {
                                if($admin->getRoleNames()[0] == 'superadmin' && $admin->usetting->emailnotifyon == 1){
                                    Mail::to($admin->email)
                                    ->send(new mailmailablesend('admin_send_email_ticket_created', $ticketData));
                                }
                            }
                        }
                    }
                    Mail::to($ticket->cust->email)
                        ->send(new mailmailablesend('customer_send_guestticket_created', $ticketData));

                    Mail::to($ccemailsend->ccemails)
                        ->send(new mailmailablesend('customer_send_guestticket_created', $ticketData));

                } catch (\Exception$e) {
                    return response()->json(['guest' => 'pass', 'data' => $ticket, 'success' => lang('A ticket has been opened with the ticket ID', 'alerts') . $ticket->ticket_id], 200);
                }

                return response()->json(['guest' => 'pass', 'data' => $ticket, 'success' => lang('A ticket has been opened with the ticket ID', 'alerts') . $ticket->ticket_id], 200);
            }

            if ($guest->userType == 'Customer') {
                return response()->json(['error' => lang('Email is already registered, Please login to create a Ticket', 'alerts'), 'email' => 'already']);
            }
        }
        if (!$guest) {
            return response()->json(['warning' => lang('Email is not registered, Please register the account', 'alerts'), 'email' => 'already']);
        }
    }

    // Guest Ticket Creating1
    public function gueststore1(Request $request)
    {
        $categories = CategoryEnvato::where('category_id',$request->category)->first();

        if(setting('ENVATO_ON') == 'on' && $categories != null && $request->envato_id == 'undefined'){
            return response()->json(['message' => 'envatoerror', 'error' => lang('Please enter valid details to create a ticket.', 'alerts')], 200);
        }

        if (setting('GUEST_TICKET') == 'yes') {
            session()->put('guestdetailssession', $request->session()->get('guestdetailssession'));
            if (setting('CAPTCHATYPE') == 'off') {
                $this->validate($request, [
                    'subject' => 'required|max:255',
                    'category' => 'required',
                    'message' => 'required',
                    'email' => 'required|max:255|indisposable|email',
                    'agree_terms' =>  'required|in:agreed',

                ]);
            } else {
                if (setting('CAPTCHATYPE') == 'manual') {
                    if (setting('RECAPTCH_ENABLE_GUEST') == 'yes') {
                        $request->validate([
                            'subject' => 'required|max:255',
                            'category' => 'required',
                            'message' => 'required',
                            'email' => 'required|max:255|indisposable|email',
                            'captcha' => ['required', 'captcha'],
                            'agree_terms' =>  'required|in:agreed',
                        ]);

                    } else {
                        $request->validate([
                            'subject' => 'required|max:255',
                            'category' => 'required',
                            'email' => 'required|max:255|indisposable|email',
                            'message' => 'required',
                            'agree_terms' =>  'required|in:agreed',

                        ]);

                    }

                }
                if (setting('CAPTCHATYPE') == 'google') {
                    if (setting('RECAPTCH_ENABLE_GUEST') == 'yes') {
                        $request->validate([
                            'subject' => 'required|max:255',
                            'category' => 'required',
                            'message' => 'required',
                            'email' => 'required|max:255|indisposable|email',
                            'g-recaptcha-response' => 'required|recaptcha',
                            'agree_terms' =>  'required|in:agreed',

                        ]);

                    } else {
                        $request->validate([
                            'subject' => 'required|max:255',
                            'category' => 'required',
                            'email' => 'required|max:255|indisposable|email',
                            'message' => 'required',
                            'agree_terms' =>  'required|in:agreed',

                        ]);
                    }
                }
            }
            $email = $request->email;
            $completeDomain = substr(strrchr($email, "@"), 1);
            $emaildomainlist = setting('EMAILDOMAIN_LIST');
            $emaildomainlistArray = explode(",", $emaildomainlist);

            if (setting('EMAILDOMAIN_BLOCKTYPE') == 'blockemail') {

                if (setting('EMAILDOMAIN_LIST') == null) {
                    $tickets = $this->validateemaildomain($request);
                    if ($tickets == 'customer') {
                        return response()->json(['error' => lang('Email is already registered, Please login to create a Ticket', 'alerts'), 'email' => 'already']);
                    }
                    if ($tickets == 'notguest') {
                        return response()->json(['warning' => lang('Email is not registered, Please register the account', 'alerts'), 'email' => 'already']);
                    }

                    session()->put('guestdetailssession', $tickets->ticket_id);
                    session()->put('guestimageaccess', $tickets->ticket_id);
                    return response()->json(['guest' => 'pass', 'data' => $tickets, 'success' => lang('A ticket has been opened with the ticket ID', 'alerts') . $tickets->ticket_id], 200);
                } else {
                    if (in_array($completeDomain, $emaildomainlistArray)) {

                        return response()->json(['message' => 'domainblock', 'error' => lang('Domain is Blocked List', 'alerts')], 200);
                    }
                    $tickets = $this->validateemaildomain($request);
                    if ($tickets == 'customer') {
                        return response()->json(['error' => lang('Email is already registered, Please login to create a Ticket', 'alerts'), 'email' => 'already']);
                    }
                    if ($tickets == 'notguest') {
                        return response()->json(['warning' => lang('Email is not registered, Please register the account', 'alerts'), 'email' => 'already']);
                    }

                    session()->put('guestdetailssession', $tickets->ticket_id);
                    session()->put('guestimageaccess', $tickets->ticket_id);
                    return response()->json(['guest' => 'pass', 'data' => $tickets, 'success' => lang('A ticket has been opened with the ticket ID', 'alerts') . $tickets->ticket_id], 200);
                }
            }

            if (setting('EMAILDOMAIN_BLOCKTYPE') == 'allowemail') {
                if (setting('EMAILDOMAIN_LIST') == null) {
                    $tickets = $this->validateemaildomain($request);
                    if ($tickets == 'customer') {
                        return response()->json(['error' => lang('Email is already registered, Please login to create a Ticket', 'alerts'), 'email' => 'already']);
                    }
                    if ($tickets == 'notguest') {
                        return response()->json(['warning' => lang('Email is not registered, Please register the account', 'alerts'), 'email' => 'already']);
                    }
                    $guestdetailssession = session()->put('guestdetailssession', $tickets->ticket_id);
                    return response()->json(['guest' => 'pass', 'data' => $tickets, 'success' => lang('A ticket has been opened with the ticket ID', 'alerts') . $tickets->ticket_id], 200);
                } else {
                    if (in_array($completeDomain, $emaildomainlistArray)) {
                        $tickets = $this->validateemaildomain($request);
                        if ($tickets == 'customer') {
                            return response()->json(['error' => lang('Email is already registered, Please login to create a Ticket', 'alerts'), 'email' => 'already']);
                        }
                        if ($tickets == 'notguest') {
                            return response()->json(['warning' => lang('Email is not registered, Please register the account', 'alerts'), 'email' => 'already']);
                        }
                        $guestdetailssession = session()->put('guestdetailssession', $tickets->ticket_id);
                        return response()->json(['guest' => 'pass', 'data' => $tickets, 'success' => lang('A ticket has been opened with the ticket ID', 'alerts') . $tickets->ticket_id], 200);
                    }
                    return response()->json(['message' => 'domainblock', 'error' => lang('Domain is Blocked List', 'alerts')], 200);

                }
            }
        }else{
            return response()->json(['message' => 'accessdenied', 'error' => lang('you are not autherised to this ticket create', 'alerts')], 500);
        }

    }

    private function validateemaildomain($request)
    {

        $userexits = Customer::where('email', $request->email)->count();
        if ($userexits == 1) {
            $guest = Customer::where('email', $request->email)->first();

        } else {

            $guest = Customer::create([

                'firstname' => '',
                'lastname' => '',
                'username' => 'GUEST',
                'email' => $request->email,
                'userType' => 'Guest',
                'password' => null,
                'status' => '1',
                'image' => null,

            ]);
            $customersetting = new CustomerSetting();
            $customersetting->custs_id = $guest->id;
            $customersetting->save();

        }
        $guest = Customer::where('email', $request->email)->first();
        if (!$guest) {
            return 'notguest';
        }
        if ($guest) {

            if ($guest->userType == 'Customer') {

                return 'customer';

            }

            $ticket = Ticket::create([
                'subject' => $request->input('subject'),
                'cust_id' => $guest->id,
                'category_id' => $request->input('category'),
                'message' => $request->input('message'),
                'project' => $request->input('project'),
                'status' => 'New',
            ]);
            $ticket = Ticket::find($ticket->id);
            $ticket->ticket_id = setting('CUSTOMER_TICKETID') . 'G-' . $ticket->id;
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

            // Auto Overdue Ticket
            if (setting('AUTO_OVERDUE_TICKET') == 'no') {
                $ticket->auto_overdue_ticket = null;
                $ticket->overduestatus = null;
            } else {
                if (setting('AUTO_OVERDUE_TICKET_TIME') == '0') {
                    $ticket->auto_overdue_ticket = null;
                    $ticket->overduestatus = null;
                } else {

                    if ($ticket->status == 'Closed') {
                        $ticket->auto_overdue_ticket = null;
                        $ticket->overduestatus = null;
                    } else {
                        $ticket->auto_overdue_ticket = now()->addDays(setting('AUTO_OVERDUE_TICKET_TIME'));
                        $ticket->overduestatus = null;
                    }

                }
            }
            // End Auto Overdue Ticket

            $ticket->update();
            $geolocation = GeoIP::getLocation(request()->getClientIp());
            $custupdate = Customer::find($ticket->cust_id);
            $custupdate->last_login_ip = $geolocation->ip;
            $custupdate->timezone = $geolocation->timezone;
            $custupdate->country = $geolocation->country;
            $custupdate->update();

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

            foreach ($request->input('ticket', []) as $file) {
                $ticket->addMedia(public_path('uploads/guestticket/' . $file))->toMediaCollection('ticket');
            }


            $tickethistory = new tickethistory();
                $tickethistory->ticket_id = $ticket->id;
                $tickethistory->ticketactions = '
                <div class="d-flex align-items-center">
                    <div class="mt-0">
                        <p class="mb-0 fs-12 mb-1">Status
                            <span class="text-burnt-orange font-weight-semibold mx-1">'.$ticket->status.'</span>
                        </p>
                        <p class="mb-0 fs-17 font-weight-semibold text-dark">'.$ticket->cust->username.'<span class="fs-11 mx-1 text-muted">(Ticket Created)</span></p>
                    </div>
                    <div class="ms-auto">
                        <span class="float-end badge badge-danger-light">
                            <span class="fs-11 font-weight-semibold">'.$ticket->cust->userType.'</span>
                        </span>
                    </div>

                </div>'
                ;
                $tickethistory->save();

            // Create a New ticket reply
            if($ticket->category){
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
                            $admin->notify(new TicketCreateNotifications($ticket));
                        }

                    }
                } else {
                    $admins = User::leftJoin('groups_users', 'groups_users.users_id', 'users.id')->whereNull('groups_users.groups_id')->whereNull('groups_users.users_id')->get();
                    foreach ($admins as $admin) {
                        $admin->notify(new TicketCreateNotifications($ticket));
                    }
                }
            }
            $ccemailsend = CCMAILS::where('ticket_id', $ticket->id)->first();

            $ticketData = [
                'ticket_username' => $ticket->cust->username,
                'ticket_title' => $ticket->subject,
                'ticket_id' => $ticket->ticket_id,
                'ticket_status' => $ticket->status,
                'ticket_description' => $ticket->message,
                'ticket_customer_url' => route('guest.ticketdetailshow', $ticket->ticket_id),
                'ticket_admin_url' => url('/administration/ticket-view/' . $ticket->ticket_id),
            ];
            try {

                if($ticket->category){
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
                }
                Mail::to($ticket->cust->email)
                    ->send(new mailmailablesend('customer_send_guestticket_created', $ticketData));

                Mail::to($ccemailsend->ccemails)
                    ->send(new mailmailablesend('customer_send_guestticket_created', $ticketData));

            } catch (\Exception$e) {

                return $ticket;
            }

            return $ticket;
        }
    }

    public function guestdetails($id)
    {

        $ticket = Ticket::find($id);
        $data['ticket'] = $ticket;

        $title = Apptitle::first();
        $data['title'] = $title;

        $seopage = Seosetting::first();
        $data['seopage'] = $seopage;

        $post = Pages::all();
        $data['page'] = $post;

        $footertext = Footertext::first();
        $data['footertext'] = $footertext;

        $socialAuthSettings = SocialAuthSetting::first();
        $data['socialAuthSettings'] = $socialAuthSettings;

        return view('guestticket.viewticketdetails')->with($data);

    }

    public function guestmedia(Request $request)
    {
        $path = public_path('uploads/guestticket/');

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

    public function guestview($ticket_id)
    {

        $ticket = Ticket::where('ticket_id', $ticket_id)->firstOrFail();
        $comments = $ticket->comments()->paginate(5);
        $category = $ticket->category;

        $title = Apptitle::first();
        $data['title'] = $title;

        $footertext = Footertext::first();
        $data['footertext'] = $footertext;

        $seopage = Seosetting::first();
        $data['seopage'] = $seopage;

        $post = Pages::all();
        $data['page'] = $post;

        $socialAuthSettings = SocialAuthSetting::first();
        $data['socialAuthSettings'] = $socialAuthSettings;

        if (session()->get('guestdetailssession') == $ticket_id) {
            return view('guestticket.show', compact('ticket', 'category', 'comments'))->with($data);
        } else {

            return view('guestticket.shownosession')->with($data);
        }

    }

    public function postComment(Request $request, $ticket_id)
    {
        $ticket = Ticket::where('ticket_id', $ticket_id)->firstOrFail();
        if ($ticket->status == "Closed") {

            return redirect()->back()->with("error", lang('The ticket has been already closed.', 'alerts'));
        } else {
            $this->validate($request, [
                'comment' => 'required',
            ]);
            $comment = Comment::create([
                'ticket_id' => $request->input('ticket_id'),
                'cust_id' => $ticket->cust->id,
                'user_id' => null,
                'comment' => $request->input('comment'),
            ]);
            $geolocation = GeoIP::getLocation(request()->getClientIp());
            $custupdate = Customer::find($ticket->cust_id);
            $custupdate->last_login_ip = $geolocation->ip;
            $custupdate->timezone = $geolocation->timezone;
            $custupdate->country = $geolocation->country;
            $custupdate->update();

            foreach ($request->input('comments', []) as $file) {
                $comment->addMedia(public_path('uploads/guestticket/' . $file))->toMediaCollection('comments');
            }

            // Closing the ticket

            if (request()->has(['status'])) {

                $ticket = Ticket::where('ticket_id', $ticket_id)->firstOrFail();

                $ticket->status = $request->input('status');
                $ticket->closing_ticket = now();
                $ticket->replystatus = 'Replied';
                $ticket->update();

                $ticketOwner = $ticket->user;

            }

            $ticket = Ticket::where('ticket_id', $ticket_id)->firstOrFail();
            $ticket->last_reply = now();
            // Auto Overdue Ticket

            if (setting('AUTO_OVERDUE_TICKET') == 'no') {

                $ticket->auto_overdue_ticket = null;
            } else {
                if (setting('AUTO_OVERDUE_TICKET_TIME') == '0') {

                    $ticket->auto_overdue_ticket = null;
                } else {

                    if ($ticket->status == 'Closed') {
                        $ticket->auto_overdue_ticket = null;
                    } else {
                        $ticket->auto_overdue_ticket = now()->addDays(setting('AUTO_OVERDUE_TICKET_TIME'));
                    }
                }
            }
            // Auto Overdue Ticket

            // Auto Closing Ticket

            if (setting('AUTO_CLOSE_TICKET') == 'no') {

                $ticket->auto_close_ticket = null;
            } else {
                if (setting('AUTO_CLOSE_TICKET_TIME') == '0') {

                    $ticket->auto_close_ticket = null;
                } else {

                    $ticket->auto_close_ticket = null;

                }
            }
            // End Auto Close Ticket

            // Auto Response Ticket

            if (setting('AUTO_RESPONSETIME_TICKET') == 'no') {
                $ticket->auto_replystatus = null;
            } else {
                if (setting('AUTO_RESPONSETIME_TICKET_TIME') == '0') {
                    $ticket->auto_replystatus = null;
                } else {
                    $ticket->auto_replystatus = null;
                }
            }
            // End Auto Response Ticket

            if (request()->input(['status']) == 'Closed') {
                $ticket->replystatus = 'Solved';
            }
            $ticket->update();

            $ccemailsend = CCMAILS::where('ticket_id', $ticket->id)->first();

            if (request()->input(['status']) == 'Closed')
            {

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
                        <span class="text-success font-weight-semibold mx-1">'.$ticket->replystatus.'</span>
                        <span class="text-danger font-weight-semibold mx-1">'.$ticket->overduestatus.'</span>
                        ';
                    }else{
                        $output .= '
                        <span class="text-danger font-weight-semibold mx-1">'.$ticket->status.'</span>
                        <span class="text-success font-weight-semibold mx-1">'.$ticket->replystatus.'</span>
                        ';
                    }

                }else{
                    if($ticket->overduestatus != null){
                        $output .= '
                        <span class="text-danger font-weight-semibold mx-1">'.$ticket->status.'</span>
                        <span class="text-success font-weight-semibold mx-1">'.$ticket->replystatus.'</span>
                        <span class="text-danger font-weight-semibold mx-1">'.$ticket->overduestatus.'</span>
                        <span class="text-warning font-weight-semibold mx-1">Note</span>
                        ';
                    }else{
                        $output .= '
                        <span class="text-danger font-weight-semibold mx-1">'.$ticket->status.'</span>
                        <span class="text-success font-weight-semibold mx-1">'.$ticket->replystatus.'</span>
                        <span class="text-warning font-weight-semibold mx-1">Note</span>
                        ';
                    }
                }

                $output .= '
                    <p class="mb-0 fs-17 font-weight-semibold text-dark">'.$comment->cust->username.'<span class="fs-11 mx-1 text-muted">(Created)</span></p>
                </div>
                <div class="ms-auto">
                <span class="float-end badge badge-danger-light">
                    <span class="fs-11 font-weight-semibold">'.$comment->cust->userType.'</span>
                </span>
                </div>

                </div>
                ';
                $tickethistory->ticketactions = $output;
                $tickethistory->save();


                $ticketData = [
                    'ticket_username' => $ticket->cust->username,
                    'ticket_id' => $ticket->ticket_id,
                    'ticket_title' => $ticket->subject,
                    'ticket_status' => $ticket->status,
                    'comment' => $comment->comment,
                    'ticket_description' => $ticket->message,
                    'ticket_customer_url' => route('loadmore.load_data', $ticket->ticket_id),
                    'ticket_admin_url' => url('/administration/ticket-view/' . $ticket->ticket_id),
                ];

                try {

                    /**** Close Ticket mail and notificaton ****/
                    $notificationcatss = $ticket->category->groupscategoryc()->get();
                    $icc = array();
                    if($notificationcatss->isNotEmpty()){

                        foreach($notificationcatss as $igc){

                            foreach($igc->groupsc->groupsuser()->get() as $user){
                                $icc[] .= $user->users_id;
                            }
                        }

                        if(!$icc){
                            $admins = User::leftJoin('groups_users','groups_users.users_id','users.id')->whereNull('groups_users.groups_id')->whereNull('groups_users.users_id')->get();
                            foreach($admins as $admin){
                                $admin->notify(new TicketCreateNotifications($ticket));
                                if($admin->usetting->emailnotifyon == 1){
                                    Mail::to($admin->email)
                                    ->send( new mailmailablesend('admin_sendemail_whenticketclosed', $ticketData ) );
                                }
                            }

                        }else{
                            if($ticket->myassignuser){
                                $assignee = $ticket->ticketassignmutliples;
                                foreach($assignee as $assignees){
                                    $user = User::where('id',$assignees->toassignuser_id)->get();
                                    foreach($user as $users){
                                        if($users->id == $assignees->toassignuser_id){
                                            $users->notify(new TicketCreateNotifications($ticket));
                                            if($users->usetting->emailnotifyon == 1){
                                                Mail::to($users->email)
                                                ->send( new mailmailablesend( 'admin_sendemail_whenticketclosed', $ticketData ) );
                                            }
                                        }
                                    }
                                }
                            }
                            else if ($ticket->selfassignuser_id) {
                                $self = User::findOrFail($ticket->selfassignuser_id);
                                $self->notify(new TicketCreateNotifications($ticket));
                                if($self->usetting->emailnotifyon == 1){
                                    Mail::to($self->email)
                                    ->send( new mailmailablesend( 'admin_sendemail_whenticketclosed', $ticketData ) );
                                }
                            }
                            else if($icc ){
                                $user = User::whereIn('id', $icc)->get();
                                foreach($user as $users){
                                    $users->notify(new TicketCreateNotifications($ticket));
                                    if($users->usetting->emailnotifyon == 1){
                                        Mail::to($users->email)
                                        ->send( new mailmailablesend( 'admin_sendemail_whenticketclosed', $ticketData ) );
                                    }
                                }
                                $admins = User::leftJoin('groups_users','groups_users.users_id','users.id')->whereNull('groups_users.groups_id')->whereNull('groups_users.users_id')->get();
                                foreach($admins as $admin){
                                    if($admin->getRoleNames()[0] == 'superadmin'){
                                        $admin->notify(new TicketCreateNotifications($ticket));
                                        if($admin->usetting->emailnotifyon == 1){
                                            Mail::to($admin->email)
                                            ->send( new mailmailablesend( 'admin_sendemail_whenticketclosed', $ticketData ) );
                                        }
                                    }
                                }
                            }
                            else {
                                $users = User::leftJoin('groups_users','groups_users.users_id','users.id')->whereNull('groups_users.groups_id')->whereNull('groups_users.users_id')->get();
                                foreach($users as $user){
                                    $user->notify(new TicketCreateNotifications($ticket));
                                    if($user->usetting->emailnotifyon == 1){
                                        Mail::to($user->email)
                                        ->send( new mailmailablesend( 'admin_sendemail_whenticketclosed', $ticketData ) );
                                    }
                                }
                            }
                        }
                    }else{
                        if($ticket->myassignuser){
                            $assignee = $ticket->ticketassignmutliples;
                            foreach($assignee as $assignees){
                                $user = User::where('id',$assignees->toassignuser_id)->get();
                                foreach($user as $users){
                                    if($users->id == $assignees->toassignuser_id){
                                        $users->notify(new TicketCreateNotifications($ticket));
                                        if($users->usetting->emailnotifyon == 1){
                                            Mail::to($users->email)
                                            ->send( new mailmailablesend( 'admin_sendemail_whenticketclosed', $ticketData ) );
                                        }
                                    }
                                }
                            }
                        } else if ($ticket->selfassignuser_id) {
                            $self = User::findOrFail($ticket->selfassignuser_id);
                            $self->notify(new TicketCreateNotifications($ticket));
                            if($self->usetting->emailnotifyon == 1){
                                Mail::to($self->email)
                                ->send( new mailmailablesend( 'admin_sendemail_whenticketclosed', $ticketData ) );
                            }
                        } else {

                            $users = User::leftJoin('groups_users','groups_users.users_id','users.id')->whereNull('groups_users.groups_id')->whereNull('groups_users.users_id')->get();
                            foreach($users as $user){
                                $user->notify(new TicketCreateNotifications($ticket));
                                if($user->usetting->emailnotifyon == 1){
                                    Mail::to($user->email)
                                    ->send( new mailmailablesend( 'admin_sendemail_whenticketclosed', $ticketData ) );
                                }
                            }
                        }
                    }


                    Mail::to($ticket->cust->email)
                        ->send(new mailmailablesend('customer_sendemail_whenticketclosed', $ticketData));

                    Mail::to($ccemailsend->ccemails)
                        ->send(new mailmailablesend('CCmail_sendemail_whenticketclosed', $ticketData));
                    /**** End Close Ticket mail and notificaton ****/

                } catch (\Exception$e) {
                    if (setting('ticketrating') == 'on') {

                        return redirect()->back()->with("success", lang('The response to the ticket was successful.', 'alerts'));

                    } else {

                        $ratingtoken = Ratingtoken::create([

                            'token' => str_random(64),
                            'ticket_id' => $ticket->id,
                        ]);

                        return redirect()->route('guest.rating', $ratingtoken->token);
                    }
                }

                if (setting('ticketrating') == 'on') {

                    return redirect()->back()->with("success", lang('The response to the ticket was successful.', 'alerts'));

                } else {

                    $ratingtoken = Ratingtoken::create([

                        'token' => str_random(64),
                        'ticket_id' => $ticket->id,
                    ]);

                    return redirect()->route('guest.rating', $ratingtoken->token);
                }
            } else {

                $tickethistory = new tickethistory();
                $tickethistory->ticket_id = $ticket->id;

                $output = '<div class="d-flex align-items-center">
                    <div class="mt-0">
                        <p class="mb-0 fs-12 mb-1">Status
                    ';
                if($ticket->ticketnote->isEmpty()){
                    if($ticket->overduestatus != null){
                        $output .= '
                        <span class="text-info font-weight-semibold mx-1">'.$ticket->status.'</span>
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
                        <span class="text-info font-weight-semibold mx-1">'.$ticket->status.'</span>
                        <span class="text-danger font-weight-semibold mx-1">'.$ticket->overduestatus.'</span>
                        <span class="text-warning font-weight-semibold mx-1">Note</span>
                        ';
                    }else{
                        $output .= '
                        <span class="text-info font-weight-semibold mx-1">'.$ticket->status.'</span>
                        <span class="text-warning font-weight-semibold mx-1">Note</span>
                        ';
                    }
                }

                $output .= '
                    <p class="mb-0 fs-17 font-weight-semibold text-dark">'.$comment->cust->username.'<span class="fs-11 mx-1 text-muted">(Responded)</span></p>
                </div>
                <div class="ms-auto">
                <span class="float-end badge badge-danger-light">
                    <span class="fs-11 font-weight-semibold">'.$comment->cust->userType.'</span>
                </span>
                </div>

                </div>
                ';
                $tickethistory->ticketactions = $output;
                $tickethistory->save();

                if ($ticket->cust->userType == 'Guest') {
                    $ticketData = [
                        'ticket_username' => $ticket->cust->username,
                        'ticket_title' => $ticket->subject,
                        'ticket_id' => $ticket->ticket_id,
                        'ticket_status' => $ticket->status,
                        'comment' => $comment->comment,
                        'ratinglink' => route('guest.rating', $ticket->ticket_id),
                        'ticket_customer_url' => route('guest.ticketdetailshow', $ticket->ticket_id),
                        'ticket_admin_url' => url('/administration/ticket-view/' . $ticket->ticket_id),
                    ];
                }
                if ($ticket->cust->userType == 'Customer') {
                    $ticketData = [
                        'ticket_username' => $ticket->cust->username,
                        'ticket_title' => $ticket->subject,
                        'ticket_id' => $ticket->ticket_id,
                        'ticket_status' => $ticket->status,
                        'comment' => $comment->comment,
                        'ratinglink' => route('guest.rating', $ticket->ticket_id),
                        'ticket_customer_url' => route('loadmore.load_data', $ticket->ticket_id),
                        'ticket_admin_url' => url('/administration/ticket-view/' . $ticket->ticket_id),
                    ];
                }

                try {

                    /** Reply ticket notification and mail**/
                    if($ticket->lastreply_mail == null){
                        if($ticket->category){
                            $notificationcatss = $ticket->category->groupscategoryc()->get();
                            $icc = array();
                            if($notificationcatss->isNotEmpty()){

                                foreach($notificationcatss as $igc){

                                    foreach($igc->groupsc->groupsuser()->get() as $user){
                                        $icc[] .= $user->users_id;
                                    }
                                }

                                if(!$icc){
                                    $admins = User::leftJoin('groups_users','groups_users.users_id','users.id')->whereNull('groups_users.groups_id')->whereNull('groups_users.users_id')->get();
                                    foreach($admins as $admin){
                                        if($admin->getRoleNames()[0] == 'superadmin'){
                                            $admin->notify(new TicketCreateNotifications($ticket));
                                            if($admin->usetting->emailnotifyon == 1){
                                                Mail::to($admin->email)
                                                ->send( new mailmailablesend( 'admin_send_email_ticket_reply', $ticketData ) );
                                            }
                                        }
                                    }

                                }else{
                                    if($ticket->myassignuser){
                                        $assignee = $ticket->ticketassignmutliples;
                                        foreach($assignee as $assignees){
                                            $user = User::where('id',$assignees->toassignuser_id)->get();
                                            foreach($user as $users){
                                                if($users->id == $assignees->toassignuser_id){
                                                    $users->notify(new TicketCreateNotifications($ticket));
                                                    if($users->usetting->emailnotifyon == 1){
                                                        Mail::to($users->email)
                                                        ->send( new mailmailablesend( 'admin_send_email_ticket_reply', $ticketData ) );
                                                    }
                                                }
                                            }
                                        }
                                    }
                                    else if ($ticket->selfassignuser_id) {
                                        $self = User::findOrFail($ticket->selfassignuser_id);
                                        $self->notify(new TicketCreateNotifications($ticket));
                                        if($self->usetting->emailnotifyon == 1){
                                            Mail::to($self->email)
                                            ->send( new mailmailablesend( 'admin_send_email_ticket_reply', $ticketData ) );
                                        }
                                    }
                                    else if($icc ){
                                        $user = User::whereIn('id', $icc)->get();
                                        foreach($user as $users){
                                            $users->notify(new TicketCreateNotifications($ticket));
                                            if($users->usetting->emailnotifyon == 1){
                                                Mail::to($users->email)
                                                ->send( new mailmailablesend( 'admin_send_email_ticket_reply', $ticketData ) );
                                            }
                                        }
                                        $admins = User::leftJoin('groups_users','groups_users.users_id','users.id')->whereNull('groups_users.groups_id')->whereNull('groups_users.users_id')->get();
                                        foreach($admins as $admin){
                                            if($admin->getRoleNames()[0] == 'superadmin'){
                                                $admin->notify(new TicketCreateNotifications($ticket));
                                                if($admin->usetting->emailnotifyon == 1){
                                                    Mail::to($admin->email)
                                                    ->send( new mailmailablesend( 'admin_send_email_ticket_reply', $ticketData ) );
                                                }
                                            }
                                        }
                                    }
                                    else {
                                        $users = User::leftJoin('groups_users','groups_users.users_id','users.id')->whereNull('groups_users.groups_id')->whereNull('groups_users.users_id')->get();
                                        foreach($users as $user){
                                            $user->notify(new TicketCreateNotifications($ticket));
                                            if($user->usetting->emailnotifyon == 1){
                                                Mail::to($user->email)
                                                ->send( new mailmailablesend( 'admin_send_email_ticket_reply', $ticketData ) );
                                            }
                                        }
                                    }
                                }
                            }else{
                                if($ticket->myassignuser){
                                    $assignee = $ticket->ticketassignmutliples;
                                    foreach($assignee as $assignees){
                                        $user = User::where('id',$assignees->toassignuser_id)->get();
                                        foreach($user as $users){
                                            if($users->id == $assignees->toassignuser_id){
                                                $users->notify(new TicketCreateNotifications($ticket));
                                                if($users->usetting->emailnotifyon == 1){
                                                    Mail::to($users->email)
                                                    ->send( new mailmailablesend( 'admin_send_email_ticket_reply', $ticketData ) );
                                                }
                                            }
                                        }
                                    }
                                } else if ($ticket->selfassignuser_id) {
                                    $self = User::findOrFail($ticket->selfassignuser_id);
                                    $self->notify(new TicketCreateNotifications($ticket));
                                    if($self->usetting->emailnotifyon == 1){
                                        Mail::to($self->email)
                                        ->send( new mailmailablesend( 'admin_send_email_ticket_reply', $ticketData ) );
                                    }
                                } else {
                                    $users = User::leftJoin('groups_users','groups_users.users_id','users.id')->whereNull('groups_users.groups_id')->whereNull('groups_users.users_id')->get();
                                    foreach($users as $user){
                                        $user->notify(new TicketCreateNotifications($ticket));
                                        if($user->usetting->emailnotifyon == 1){
                                            Mail::to($user->email)
                                            ->send( new mailmailablesend( 'admin_send_email_ticket_reply', $ticketData ) );
                                        }
                                    }
                                }
                            }
                        }else{
                            $admins = User::leftJoin('groups_users','groups_users.users_id','users.id')->whereNull('groups_users.groups_id')->whereNull('groups_users.users_id')->get();
                            foreach($admins as $admin){
                                $admin->notify(new TicketCreateNotifications($ticket));
                                if($admin->usetting->emailnotifyon == 1){
                                    Mail::to($admin->email)
                                    ->send( new mailmailablesend( 'admin_send_email_ticket_reply', $ticketData ) );
                                }
                            }
                        }
                    }

                    if($ticket->lastreply_mail != null){
                        if($ticket->category){
                           $notificationcatss = $ticket->category->groupscategoryc()->get();
                            $icc = array();
                            if($notificationcatss->isNotEmpty()){

                                foreach($notificationcatss as $igc){

                                    foreach($igc->groupsc->groupsuser()->get() as $user){
                                        $icc[] .= $user->users_id;
                                    }
                                }

                                if(!$icc){
                                    $admins = User::leftJoin('groups_users','groups_users.users_id','users.id')->whereNull('groups_users.groups_id')->whereNull('groups_users.users_id')->get();
                                    foreach($admins as $admin){
                                        if($admin->getRoleNames()[0] == 'superadmin'){
                                            $admin->notify(new TicketCreateNotifications($ticket));
                                            if($admin->usetting->emailnotifyon == 1){
                                                Mail::to($admin->email)
                                                ->send( new mailmailablesend( 'admin_send_email_ticket_reply', $ticketData ) );
                                            }
                                        }
                                    }

                                }else{
                                    if($ticket->myassignuser_id){
                                        $assignee = $ticket->ticketassignmutliples;
                                        foreach($assignee as $assignees){
                                            $user = User::where('id',$assignees->toassignuser_id)->get();
                                            foreach($user as $users){
                                                if($users->id == $assignees->toassignuser_id){
                                                    $users->notify(new TicketCreateNotifications($ticket));
                                                    if($users->usetting->emailnotifyon == 1){
                                                        Mail::to($users->email)
                                                        ->send( new mailmailablesend( 'admin_send_email_ticket_reply', $ticketData ) );
                                                    }
                                                }
                                            }
                                        }
                                    } else if ($ticket->selfassignuser_id) {

                                        $self = User::findOrFail($ticket->selfassignuser_id);
                                        $self->notify(new TicketCreateNotifications($ticket));
                                        if($self->usetting->emailnotifyon == 1){
                                            Mail::to($self->email)
                                            ->send( new mailmailablesend( 'admin_send_email_ticket_reply', $ticketData ) );
                                        }
                                    } else if($icc){
                                        $user = User::whereIn('id', $icc)->get();
                                        foreach($user as $users){
                                            $users->notify(new TicketCreateNotifications($ticket));
                                            if($users->usetting->emailnotifyon == 1){
                                                Mail::to($users->email)
                                                ->send( new mailmailablesend( 'admin_send_email_ticket_reply', $ticketData ) );
                                            }
                                        }
                                        $admins = User::leftJoin('groups_users','groups_users.users_id','users.id')->whereNull('groups_users.groups_id')->whereNull('groups_users.users_id')->get();
                                        foreach($admins as $admin){
                                            if($admin->getRoleNames()[0] == 'superadmin'){
                                                $admin->notify(new TicketCreateNotifications($ticket));
                                                if($admin->usetting->emailnotifyon == 1){
                                                    Mail::to($admin->email)
                                                    ->send( new mailmailablesend( 'admin_send_email_ticket_reply', $ticketData ) );
                                                }
                                            }
                                        }
                                    }else {
                                        $users = User::leftJoin('groups_users','groups_users.users_id','users.id')->whereNull('groups_users.groups_id')->whereNull('groups_users.users_id')->get();
                                        foreach($users as $user){
                                            $user->notify(new TicketCreateNotifications($ticket));
                                            if($user->usetting->emailnotifyon == 1){
                                                Mail::to($user->email)
                                                ->send( new mailmailablesend( 'admin_send_email_ticket_reply', $ticketData ) );
                                            }
                                        }
                                    }


                                }
                            }else{
                                if($ticket->myassignuser){
                                    $assignee = $ticket->ticketassignmutliples;
                                    foreach($assignee as $assignees){
                                        $user = User::where('id',$assignees->toassignuser_id)->get();
                                        foreach($user as $users){
                                            if($users->id == $assignees->toassignuser_id){
                                                $users->notify(new TicketCreateNotifications($ticket));
                                                if($users->usetting->emailnotifyon == 1){
                                                    Mail::to($users->email)
                                                    ->send( new mailmailablesend( 'admin_send_email_ticket_reply', $ticketData ) );
                                                }
                                            }
                                        }
                                    }
                                } else if ($ticket->selfassignuser_id) {
                                    $self = User::findOrFail($ticket->selfassignuser_id);
                                    $self->notify(new TicketCreateNotifications($ticket));
                                    if($self->usetting->emailnotifyon == 1){
                                        Mail::to($self->email)
                                        ->send( new mailmailablesend( 'admin_send_email_ticket_reply', $ticketData ) );
                                    }
                                } else {

                                    $users = User::leftJoin('groups_users','groups_users.users_id','users.id')->whereNull('groups_users.groups_id')->whereNull('groups_users.users_id')->get();
                                    foreach($users as $user){
                                        $user->notify(new TicketCreateNotifications($ticket));
                                        if($user->usetting->emailnotifyon == 1){
                                            Mail::to($user->email)
                                            ->send( new mailmailablesend( 'admin_send_email_ticket_reply', $ticketData ) );
                                        }
                                    }
                                }
                            }
                        }else{
                            $admins = User::leftJoin('groups_users','groups_users.users_id','users.id')->whereNull('groups_users.groups_id')->whereNull('groups_users.users_id')->get();
                            foreach($admins as $admin){
                                $admin->notify(new TicketCreateNotifications($ticket));
                                if($admin->usetting->emailnotifyon == 1){
                                    Mail::to($admin->email)
                                    ->send( new mailmailablesend( 'admin_send_email_ticket_reply', $ticketData ) );
                                }
                            }
                        }

                    }

                    Mail::to($ccemailsend->ccemails)
                        ->send(new mailmailablesend('customer_send_ticket_reply', $ticketData));
                    /** End Reply ticket notification and mails **/

                } catch (\Exception$e) {
                    return redirect()->back()->with("success", lang('The response to the ticket was successful.', 'alerts'));
                }

                return redirect()->back()->with("success", lang('The response to the ticket was successful.', 'alerts'));
            }
        }

    }

    public function envatoverify(Request $request)
    {
        if ($request->data) {

            $apidatatoken = APIData::first();

            $envato_license = $request->data;

			return response()->json(['valid' => 'true', 'message' => lang('The purchase code has been validated and is supported.', 'alerts'), 'key' => $envato_license]);

        }
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

            return redirect('/')->with("error", lang('Your rating has already been submitted.', 'alerts'));
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

        $socialAuthSettings = SocialAuthSetting::first();
        $data['socialAuthSettings'] = $socialAuthSettings;

        $rating = $ticket->comments()->whereNotNull('user_id')->get();
        $comment = Comment::select('user_id')->where('ticket_id', $ticket->id)->distinct()->get();

        if ($rating->isEmpty()) {
            return redirect()->back()->with("success", lang('Your comment has be submitted.', 'alerts'));
        } else {
            return view('guestticket.rating', compact('ticket', 'title', 'comment', 'footertext'))->with($data);
        }

    }

    /// rating system ///
    public function star5($id)
    {

        $user = User::with('usetting')->findorFail($id);
        $user->usetting->increment('star5');
        $user->usetting->update();

        return redirect('/')->with('success', lang('Thank you for rating us.', 'alerts'));

    }

    public function star4($id)
    {

        $user = User::with('usetting')->findorFail($id);
        $user->usetting->increment('star4');
        $user->usetting->update();

        return redirect('/')->with('success', lang('Thank you for rating us.', 'alerts'));

    }

    public function star3($id)
    {

        $user = User::with('usetting')->findorFail($id);
        $user->usetting->increment('star3');
        $user->usetting->update();

        return redirect('/')->with('success', lang('Thank you for rating us.', 'alerts'));

    }

    public function star2($id)
    {

        $user = User::with('usetting')->findorFail($id);
        $user->usetting->increment('star2');
        $user->usetting->update();

        return redirect('/')->with('success', lang('Thank you for rating us.', 'alerts'));

    }

    public function star1($id)
    {

        $user = User::with('usetting')->findorFail($id);
        $user->usetting->increment('star1');
        $user->usetting->update();

        return redirect('/')->with('success', lang('Thank you for rating us.', 'alerts'));
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

    public function imagedestroy($id)
    {
        //For Deleting Users
        $commentss = Media::findOrFail($id);
        $commentss->delete();
        return response()->json([
            'success' => lang('The image has been deleted successfully!', 'alerts'),
        ]);
    }

    public function close(Request $request, $ticket_id)
    {

        $ticket = Ticket::where('ticket_id', $ticket_id)->firstOrFail();

        $ticket->status = 'Re-Open';

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
            <p class="mb-0 fs-17 font-weight-semibold text-dark">'.$ticket->cust->username.'<span class="fs-11 mx-1 text-muted">(Closed)</span></p>
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

        // Create a New ticket reply
        if($ticket->category)
        {
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
                        $admin->notify(new TicketCreateNotifications($ticket));
                    }

                }
            } else {
                $admins = User::leftJoin('groups_users', 'groups_users.users_id', 'users.id')->whereNull('groups_users.groups_id')->whereNull('groups_users.users_id')->get();
                foreach ($admins as $admin) {
                    $admin->notify(new TicketCreateNotifications($ticket));
                }
            }
        }

        // Notification category Empty
        if(!$ticket->category)
        {
            $admins = User::get();
            foreach($admins as $admin){
                $admin->notify(new TicketCreateNotifications($ticket));
            }

        }

        $ccemailsend = CCMAILS::where('ticket_id', $ticket->id)->first();

        $ticketData = [
            'ticket_title' => $ticket->subject,
            'ticket_description' => $ticket->message,
            'ticket_id' => $ticket->ticket_id,
            'ticket_username' => $ticket->cust->username,
            'ticket_status' => $ticket->status,
            'ticket_customer_url' => route('guest.ticketdetailshow', $ticket->ticket_id),
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
                            if($admin->usetting->emailnotifyon == 1){
                                Mail::to($admin->email)
                                    ->send(new mailmailablesend('admin_sendemail_whenticketreopen', $ticketData));
                            }
                        }

                    } else {

                        $user = User::whereIn('id', $icc)->get();
                        foreach ($user as $users) {
                            if($users->usetting->emailnotifyon == 1){
                                Mail::to($users->email)
                                    ->send(new mailmailablesend('admin_sendemail_whenticketreopen', $ticketData));
                            }
                        }
                        $admins = User::leftJoin('groups_users', 'groups_users.users_id', 'users.id')->whereNull('groups_users.groups_id')->whereNull('groups_users.users_id')->get();
                        foreach ($admins as $admin) {
                            if($admin->usetting->emailnotifyon == 1){
                                Mail::to($admin->email)
                                    ->send(new mailmailablesend('admin_sendemail_whenticketreopen', $ticketData));
                            }
                        }

                    }
                } else {
                    $admins = User::leftJoin('groups_users', 'groups_users.users_id', 'users.id')->whereNull('groups_users.groups_id')->whereNull('groups_users.users_id')->get();
                    foreach ($admins as $admin) {
                        if($admin->usetting->emailnotifyon == 1){
                            Mail::to($admin->email)
                                ->send(new mailmailablesend('admin_sendemail_whenticketreopen', $ticketData));
                        }
                    }
                }
            }
            if(!$ticket->category)
            {
                $admins = User::get();
                foreach($admins as $admin){
                    if($admin->usetting->emailnotifyon == 1){
                        Mail::to($admin->email)
                        ->send(new mailmailablesend('admin_sendemail_whenticketreopen', $ticketData));
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

    public function emailsvalidateguest(Request $request)
    {
        if (setting('GUEST_TICKET') == 'yes') {
            $request->validate([
                'email' => 'required|max:255|indisposable|email',

            ]);
            $email = $request->email;
            $completeDomain = substr(strrchr($email, "@"), 1);
            $emaildomainlist = setting('EMAILDOMAIN_LIST');
            $emaildomainlistArray = explode(",", $emaildomainlist);

            if (setting('EMAILDOMAIN_BLOCKTYPE') == 'blockemail') {

                if (setting('EMAILDOMAIN_LIST') == null) {
                    $ticket = $this->createverifyotp($request);
                    return response()->json(['message' => 'createverifyotp', 'success' => lang('An OTP (One Time Password) has been sent to your email ID. Please enter the OTP below to submit your guest ticket', 'alerts')], 200);
                } else {
                    if (in_array($completeDomain, $emaildomainlistArray)) {

                        return response()->json(['message' => 'domainblock', 'error' => lang('Domain is Blocked List', 'alerts')], 200);
                    }
                    $ticket = $this->createverifyotp($request);
                    return response()->json(['message' => 'createverifyotp', 'success' => lang('An OTP (One Time Password) has been sent to your email ID. Please enter the OTP below to submit your guest ticket.', 'alerts')], 200);
                }
            }

            if (setting('EMAILDOMAIN_BLOCKTYPE') == 'allowemail') {
                if (setting('EMAILDOMAIN_LIST') == null) {
                    $ticket = $this->createverifyotp($request);
                    return response()->json(['message' => 'createverifyotp', 'success' => lang('An OTP (One Time Password) has been sent to your email ID. Please enter the OTP below to submit your guest ticket.', 'alerts')], 200);
                } else {
                    if (in_array($completeDomain, $emaildomainlistArray)) {
                        $ticket = $this->createverifyotp($request);
                        return response()->json(['message' => 'createverifyotp', 'success' => lang('An OTP (One Time Password) has been sent to your email ID. Please enter the OTP below to submit your guest ticket.', 'alerts')], 200);
                    }
                    return response()->json(['message' => 'domainblock', 'error' => lang('Domain is Blocked List', 'alerts')], 200);

                }
            }

        }else{
            return response()->json(['message' => 'accessdenied', 'error' => lang('you are not autherised to this ticket create', 'alerts')], 500);
        }
    }

    private function createverifyotp($request)
    {

        $guest = VerifyOtp::where('type', 'guestotp')->where('cust_id', $request->email)->first();

        if ($guest) {
            $guest->otp = rand(100000, 999999);
            $guest->update();
            if ($request->session()->has('sessionemail')) {
                $request->session()->forget('sessionemail');
            }
            $request->session()->put('sessionemail', $guest->cust_id);
            $guestticket = [

                'guestotp' => $guest->otp,
                'guestemail' => $guest->cust_id,
                'guestname' => 'Guest',
            ];

            try {

                Mail::to($guest->cust_id)
                    ->send(new mailmailablesend('guestticket_email_verification', $guestticket));

            } catch (\Exception$e) {

                // return response()->json(['success' => lang('Please check your Email', 'alerts'), 'email' => 'exists']);
            }
        }
        if (!$guest) {
            $verifyOtp = VerifyOtp::create([
                'cust_id' => $request->email,
                'otp' => rand(100000, 999999),
                'type' => 'guestotp',
            ]);

            if ($request->session()->has('sessionemail')) {
                $request->session()->forget('sessionemail');
            }
            $request->session()->put('sessionemail', $verifyOtp->cust_id);
            $guestticket = [

                'guestotp' => $verifyOtp->otp,
                'guestemail' => $verifyOtp->cust_id,
                'guestname' => 'Guest',
            ];

            try {

                Mail::to($verifyOtp->cust_id)
                    ->send(new mailmailablesend('guestticket_email_verification', $guestticket));

            } catch (\Exception$e) {

                // return response()->json(['success' => lang('Please check your Email', 'alerts'), 'email' => 'exists']);
            }
        }

    }

    public function verifyotp(Request $request)
    {
        if ($request->session()->has('sessionemail')) {
            $emailvalidate = $request->session()->get('sessionemail');
        }
        $verify = VerifyOtp::where('type', 'guestotp')->where('otp', $request->otpvalue)->first();
        if ($verify) {
            if ($emailvalidate == $verify->cust_id) {
                $customerfind = Customer::where('email', $verify->cust_id)->first();
                if (!$customerfind) {
                    $guest = Customer::create([

                        'firstname' => '',
                        'lastname' => '',
                        'username' => 'GUEST',
                        'email' => $verify->cust_id,
                        'userType' => 'Guest',
                        'password' => null,
                        'status' => '1',
                        'image' => null,

                    ]);
                    $customersetting = new CustomerSetting();
                    $customersetting->custs_id = $guest->id;
                    $customersetting->save();

                    return response()->json(['success' => lang('verified'), $guest], 200);
                }

                if ($customerfind) {
                    return response()->json(['success' => lang('verified'), $customerfind], 200);

                }

            }
        } else {
            return response()->json(['error' => lang('Invalid OTP')], 200);
        }
        if (!$verify) {
            return response()->json(['error' => lang('Invalid OTP')], 200);
        }

    }

    public function subcategorylist(Request $request)
    {

        $parent_id = $request->cat_id;

        $subcategoriess = Subcategorychild::where('category_id', $parent_id)->get();

        $output = '';
        if ($subcategoriess->isNotEmpty()) {
            $output .= '<option label="select subcategory"></option>';
            foreach ($subcategoriess as $subcats) {
                $sucatss = $subcats->subcatlists()->where('status', '1')->get();
                if ($sucatss->isNotEmpty()) {
                    foreach ($sucatss as $subcategory) {

                        $output .= '<option value="' . $subcategory->id . '">' . $subcategory->subcategoryname . '</option>';
                    }
                }

            }
        }

        //projectlist
        $subcategories = Projects::select('projects.*', 'projects_categories.category_id')->join('projects_categories', 'projects_categories.projects_id', 'projects.id')
            ->where('projects_categories.category_id', $parent_id)
            ->get();

        // envato asssign

        $categoryenvato = CategoryEnvato::where('category_id', $parent_id)->get();

        $data = [
            'subcategoriess' => $output,
            'subcategories' => $subcategories,
            'envatosuccess' => $categoryenvato,
        ];
        return response()->json($data
            , 200);
    }

    public function ticketview($id)
    {

        $ticket = Ticket::find($id);
        $data['ticket'] = $ticket;

        $title = Apptitle::first();
        $data['title'] = $title;

        $seopage = Seosetting::first();
        $data['seopage'] = $seopage;

        $post = Pages::all();
        $data['page'] = $post;

        $footertext = Footertext::first();
        $data['footertext'] = $footertext;

        $socialAuthSettings = SocialAuthSetting::first();
        $data['socialAuthSettings'] = $socialAuthSettings;

        return view('guestticket.ticketverify')->with($data);

    }

    public function senddataverify(Request $request)
    {
        $ticketid = $request->id;

        $ticketfind = Ticket::find($ticketid);

        $email = $ticketfind->cust->email;

        $guest = VerifyOtp::where('type', 'guestverifyotp')->where('cust_id', $email)->first();

        if (!$guest) {
            $verifyOtp = VerifyOtp::create([
                'cust_id' => $email,
                'otp' => rand(100000, 999999),
                'type' => 'guestverifyotp',
            ]);

            if ($request->session()->has('sessionverifyemail')) {
                $request->session()->forget('sessionverifyemail');
            }
            $request->session()->put('sessionverifyemail', $ticketfind->ticket_id);
            session()->put('guestdetailssession',  $ticketfind->ticket_id);
            $guestticket = [

                'guestotp' => $verifyOtp->otp,
                'guestemail' => $verifyOtp->cust_id,
                'guestname' => 'Guest',
            ];

            try {

                Mail::to($verifyOtp->cust_id)
                    ->send(new mailmailablesend('guestticket_email_verification_view', $guestticket));

            } catch (\Exception$e) {

                return response()->json(['success' => lang('Please check your Email', 'alerts'), 'verifyemail' => 'OK']);
            }
            return response()->json(['success' => lang('Please check your Email', 'alerts'), 'verifyemail' => 'OK']);
        }

        if ($guest) {
            $guest->otp = rand(100000, 999999);
            $guest->update();
            if ($request->session()->has('sessionverifyemail')) {
                $request->session()->forget('sessionverifyemail');
            }
            $request->session()->put('sessionverifyemail', $guest->cust_id);
            $guestticket = [

                'guestotp' => $guest->otp,
                'guestemail' => $guest->cust_id,
                'guestname' => 'Guest',
            ];

            try {

                Mail::to($guest->cust_id)
                    ->send(new mailmailablesend('guestticket_email_verification_view', $guestticket));

            } catch (\Exception$e) {

                return response()->json(['success' => lang('Please check your Email', 'alerts'), 'verifyemail' => 'OK']);
            }
            return response()->json(['success' => lang('Please check your Email', 'alerts'), 'verifyemail' => 'OK']);
        }

    }

    public function verifyguestotp(Request $request)
    {
        $otpverify = $request->otpvalue;

        if ($request->session()->has('sessionemail')) {
            $emailvalidate = $request->session()->get('sessionemail');
        }
        $verify = VerifyOtp::where('type', 'guestverifyotp')->where('otp', $request->otpvalue)->first();
        if ($verify) {
            $guestticketdetailssession = session()->put('guestticketdetailssession', $verify->cust_id);
            return response()->json(['success' => lang('Valid OTP')], 200);
        }
        if (!$verify) {
            return response()->json(['error' => lang('Invalid OTP')], 200);
        }
    }

    public function ticketdetailshow($ticket_id)
    {
        $ticket = Ticket::where('ticket_id', $ticket_id)->firstOrFail();
        $comments = $ticket->comments()->paginate(5);
        $category = $ticket->category;

        $title = Apptitle::first();
        $data['title'] = $title;

        $footertext = Footertext::first();
        $data['footertext'] = $footertext;

        $seopage = Seosetting::first();
        $data['seopage'] = $seopage;

        $post = Pages::all();
        $data['page'] = $post;

        $socialAuthSettings = SocialAuthSetting::first();
        $data['socialAuthSettings'] = $socialAuthSettings;

        $verifyUser = VerifyOtp::where('type', 'guestverifyotp')->where('cust_id', $ticket->cust->email)->first();
        if ($verifyUser) {

            $verifyUser->delete();
        }

        if (session()->get('sessionverifyemail') == $ticket_id) {

            return view('guestticket.showticketview', compact('ticket', 'category', 'comments'))->with($data);
        } else {

            return redirect()->route('guest.ticketview', $ticket->id);
        }
    }

    public function notificationalerts()
    {

        if (Auth::check() && Auth::user()) {
            $data = Auth::user()->unreadNotifications()->where('notifiable', '0')->get();
            $dataa = $data->count();
            return response()->json($data, 200);
        }

    }

    public function notificationalertsread()
    {
        if (Auth::check() && Auth::user()) {
            $data = Auth::user()->unreadNotifications()->get();
            foreach ($data as $dataas) {
                $dataas->notifiable = '1';
                $dataas->update();

            }
            return response()->noContent();
        }
    }

    public function notificationsreading()
    {

        $notify = auth()->user()->unreadNotifications()->paginate(2);
        $data['notifys'] = $notify;
        return view('includes.admin.allnotify')->with($data);
    }

    public function badgecount()
    {

        $badgecount = auth()->user()->unreadNotifications->count();
        $data['badgecount'] = $badgecount;
        return view('includes.admin.badgecount')->with($data);
    }

    public function markasreadcount()
    {

        $badgecount = auth()->user()->unreadNotifications->count();
        $data['badgecount'] = $badgecount;
        return view('includes.admin.markasreadcount')->with($data);
    }

    public function allnotifycount()
    {

        $badgecount = auth()->user()->unreadNotifications->count();
        $data['badgecount'] = $badgecount;
        return view('includes.admin.allnotify')->with($data);
    }

    public function cnotificationalerts()
    {

        if (Auth::guard('customer')->check() && Auth::guard('customer')->user()) {
            $data = Auth::guard('customer')->user()->unreadNotifications()->where('notifiable', '0')->get();
            $dataa = $data->count();
            return response()->json($data, 200);
        }

    }

    public function cnotificationalertsread()
    {
        if (Auth::guard('customer')->check() && Auth::guard('customer')->user()) {
            $data = Auth::guard('customer')->user()->unreadNotifications()->get();
            foreach ($data as $dataas) {
                $dataas->notifiable = '1';
                $dataas->update();

            }
            return response()->noContent();
        }
    }

    public function cnotificationsreading()
    {

        $notify = auth()->guard('customer')->user()->unreadNotifications()->paginate(2);
        $data['notifys'] = $notify;
        return view('includes.user.allnotify')->with($data);
    }

    public function cbadgecount()
    {

        $badgecount = auth()->guard('customer')->user()->unreadNotifications->count();
        $data['badgecount'] = $badgecount;
        return view('includes.user.badgecount')->with($data);
    }
    public function cmarkasreadcount()
    {

        $badgecount = auth()->guard('customer')->user()->unreadNotifications->count();
        $data['badgecount'] = $badgecount;
        return view('includes.user.markasreadcount')->with($data);
    }

    public function callnotifycount()
    {

        $badgecount = auth()->guard('customer')->user()->unreadNotifications->count();
        $data['badgecount'] = $badgecount;
        return view('includes.user.allnotify')->with($data);
    }

    public function markallnotify()
    {
        auth()->guard('customer')->user()->unreadNotifications->markAsRead();

        return response()->noContent();
    }
}
