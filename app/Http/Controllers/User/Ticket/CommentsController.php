<?php

namespace App\Http\Controllers\User\Ticket;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Ticket\Comment;
use App\Models\Ticket\Ticket;
use App\Models\Ticket\Category;
use App\Models\User;
use App\Mail\AppMailer;
use Auth;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Hash;
use App\Notifications\TicketCreateNotifications;
use Mail;
use App\Mail\mailmailablesend;
use App\Models\Ratingtoken;
use App\Models\CCMAILS;
use App\Models\tickethistory;



class CommentsController extends Controller
{
    public function postComment(Request $request,  $ticket_id)
    {
        $ticket = Ticket::where('ticket_id', $ticket_id)->firstOrFail();
        if($ticket->status == "Closed"){

             return redirect()->back()->with("error", lang('The ticket has been already closed.', 'alerts'));
        }
        else{
            $this->validate($request, [
                'comment' => 'required'
            ]);
            $tic = Ticket::where('ticket_id', $ticket_id)->firstOrFail();
            if($tic->comments()->get() != null){
                $comm = $tic->comments()->update([
                    'display' => null
                ]);
            }
            $comment = Comment::create([
                'ticket_id' => $request->input('ticket_id'),
                'cust_id' => Auth::guard('customer')->user()->id,
                'user_id' => null,
                'display' => 1,
                'comment' => $request->input('comment')
            ]);

            foreach ($request->input('comments', []) as $file) {
                $comment->addMedia(public_path('uploads/comment/' . $file))->toMediaCollection('comments');
            }

            // Closing the ticket
            if(request()->has(['status'])){

                $ticket = Ticket::where('ticket_id', $ticket_id)->firstOrFail();

                $ticket->status = $request->input('status');
                $ticket->closing_ticket = now();
                $ticket->update();

                $ticketOwner = $ticket->user;

            }

            $ticket = Ticket::where('ticket_id', $ticket_id)->firstOrFail();
            $ticket->last_reply = now();
            // Auto Overdue Ticket

            if(setting('AUTO_OVERDUE_TICKET') == 'no'){
                $ticket->auto_overdue_ticket = null;
                $ticket->overduestatus = null;
            }else{
                if(setting('AUTO_OVERDUE_TICKET_TIME') == '0'){
                    $ticket->auto_overdue_ticket = null;
                    $ticket->overduestatus = null;
                }else{
                    if(Auth::guard('customer')->check() && Auth::guard('customer')->user()){
                        if($ticket->status == 'Closed'){
                            $ticket->auto_overdue_ticket = null;
                            $ticket->overduestatus = null;
                        }
                        else{
                            $ticket->auto_overdue_ticket = now()->addDays(setting('AUTO_OVERDUE_TICKET_TIME'));
                            $ticket->overduestatus = null;
                        }
                    }
                }
            }
            // Auto Overdue Ticket

            // Auto Closing Ticket

            if(setting('AUTO_CLOSE_TICKET') == 'no'){
                $ticket->auto_close_ticket = null;
            }else{
                if(setting('AUTO_CLOSE_TICKET_TIME') == '0'){
                    $ticket->auto_close_ticket = null;
                }else{

                    if(Auth::guard('customer')->check() && Auth::guard('customer')->user()){
                        $ticket->auto_close_ticket = null;
                    }
                }
            }
            // End Auto Close Ticket

            // Auto Response Ticket

            if(setting('AUTO_RESPONSETIME_TICKET') == 'no'){
                $ticket->auto_replystatus = null;
            }else{
                if(setting('AUTO_RESPONSETIME_TICKET_TIME') == '0'){
                    $ticket->auto_replystatus = null;
                }else{
                    if(Auth::guard('customer')->check() && Auth::guard('customer')->user()){
                        $ticket->auto_replystatus = null;
                    }
                }
            }
            // End Auto Response Ticket

            if(request()->input(['status']) == 'Closed'){
                $ticket->replystatus = 'Solved';
            }else{
                $ticket->replystatus = 'Replied';
            }
            $ticket->update();

            if(request()->input(['status']) == 'Closed')
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
                    <p class="mb-0 fs-17 font-weight-semibold text-dark">'.$comment->cust->username.'<span class="fs-11 mx-1 text-muted">(Closed)</span></p>
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

                /**** End Close Ticket notificaton ****/

                $ccemailsend = CCMAILS::where('ticket_id', $ticket->id)->first();

                $ticketData = [
                    'ticket_username' => $ticket->cust->username,
                    'ticket_id' => $ticket->ticket_id,
                    'ticket_title' => $ticket->subject,
                    'ticket_description' => $ticket->message,
                    'ticket_status' => $ticket->status,
                    'comment' => $comment->comment,
                    'ticket_customer_url' => route('loadmore.load_data', $ticket->ticket_id),
                    'ticket_admin_url' => url('/administration/ticket-view/'.$ticket->ticket_id),
                ];

                try{

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
                        ->send( new mailmailablesend('customer_sendemail_whenticketclosed', $ticketData ) );

                    Mail::to($ccemailsend->ccemails )
                    ->send( new mailmailablesend('CCmail_sendemail_whenticketclosed', $ticketData ) );
                    /**** End Close Ticket mail and notificaton ****/


                }catch(\Exception $e){
                    if(setting('ticketrating') == 'on'){
                        return redirect()->back()->with("success", lang('The response to the ticket was successful.', 'alerts'));
                    }else{

                        $ratingtoken =  Ratingtoken::create([

                            'token' => str_random(64),
                            'ticket_id' => $ticket->id,
                        ]);

                        return redirect()->route('rating', $ratingtoken->token);
                    }
                }
                if(setting('ticketrating') == 'on'){
                    return redirect()->back()->with("success", lang('The response to the ticket was successful.', 'alerts'));
                }else{

                    $ratingtoken =  Ratingtoken::create([

                        'token' => str_random(64),
                        'ticket_id' => $ticket->id,
                    ]);

                    return redirect()->route('rating', $ratingtoken->token);
                }
            }else{


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
                        <span class="text-info font-weight-semibold mx-1">'.$ticket->status.'</span>
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




                $ccemailsend = CCMAILS::where('ticket_id', $ticket->id)->first();

                $ticketData = [
                    'ticket_username' => $ticket->cust->username,
                    'ticket_title' => $ticket->subject,
                    'ticket_id' => $ticket->ticket_id,
                    'ticket_status' => $ticket->status,
                    'comment' => $comment->comment,
                    'ticket_admin_url' => url('/administration/ticket-view/'.$ticket->ticket_id),
                ];

                try{

                    /* customer reply to ticket notification and mail */
                    if($ticket->lastreply_mail == null){
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
                                else if ($ticket->selfassignuser_id) {
                                    $self = User::findOrFail($ticket->selfassignuser_id);
                                    $self->notify(new TicketCreateNotifications($ticket));
                                    if($self->usetting->emailnotifyon == 1){
                                        Mail::to($self->email)
                                        ->send( new mailmailablesend( 'admin_send_email_ticket_reply', $ticketData ) );
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
                            } else if ($ticket->selfassignuser_id) {
                                $self = User::findOrFail($ticket->selfassignuser_id);
                                $self->notify(new TicketCreateNotifications($ticket));
                                if($self->usetting->emailnotifyon == 1){
                                    Mail::to($self->email)
                                    ->send( new mailmailablesend( 'admin_send_email_ticket_reply', $ticketData ) );
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
                                    } else if ($ticket->selfassignuser_id) {

                                        $self = User::findOrFail($ticket->selfassignuser_id);
                                        $self->notify(new TicketCreateNotifications($ticket));
                                        if($self->usetting->emailnotifyon == 1){
                                            Mail::to($self->email)
                                            ->send( new mailmailablesend( 'admin_send_email_ticket_reply', $ticketData ) );
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
                                } else if ($ticket->selfassignuser_id) {
                                    $self = User::findOrFail($ticket->selfassignuser_id);
                                    $self->notify(new TicketCreateNotifications($ticket));
                                    if($self->usetting->emailnotifyon == 1){
                                        Mail::to($self->email)
                                        ->send( new mailmailablesend( 'admin_send_email_ticket_reply', $ticketData ) );
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
                            $user = User::where('id',$ticket->lastreply_mail)->get();
                            foreach($user as $users){
                                $users->notify(new TicketCreateNotifications($ticket));
                                if($users->usetting->emailnotifyon == 1){
                                    Mail::to($users->email)
                                    ->send( new mailmailablesend( 'admin_send_email_ticket_reply', $ticketData ) );
                                }
                            }
                        }

                    }

                    Mail::to($ccemailsend->ccemails)
                    ->send( new mailmailablesend( 'customer_send_ticket_reply', $ticketData) );
                    /* End customer reply to ticket notification and mail */

                }catch(\Exception $e){
                    return redirect()->back()->with("success", lang('The response to the ticket was successful.', 'alerts'));
                }

                return redirect()->back()->with("success", lang('The response to the ticket was successful.', 'alerts'));
            }
        }

    }

    public function storeMedia(Request $request)
    {
        $path = public_path('uploads/comment');

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
            'name'          => $name,
            'original_name' => $file->getClientOriginalName(),
        ]);
    }


    public function updateedit(Request $request, $id){
        if ($request->has('message')) {

            $this->validate($request, [
                'message' => 'required'
            ]);
            $ticket = Ticket::findOrFail($id);
            $ticket->message = $request->input('message');

            $ticket->update();
            return redirect()->back()->with('success', lang('Updated Successfully', 'alerts'));

        }else{
            $this->validate($request, [
                'editcomment' => 'required'
            ]);
            $comment = Comment::findOrFail($id);
            $comment->comment = $request->input('editcomment');

            $comment->update();


            $tickethistory = new tickethistory();
            $tickethistory->ticket_id = $comment->ticket->id;

            $output = '<div class="d-flex align-items-center">
                <div class="mt-0">
                    <p class="mb-0 fs-12 mb-1">Status
                ';
            if($comment->ticket->ticketnote->isEmpty()){
                if($comment->ticket->overduestatus != null){
                    $output .= '
                    <span class="text-teal font-weight-semibold mx-1">'.$comment->ticket->status.'</span>
                    <span class="text-danger font-weight-semibold mx-1">'.$comment->ticket->overduestatus.'</span>
                    ';
                }else{
                    $output .= '
                    <span class="text-danger font-weight-semibold mx-1">'.$comment->ticket->status.'</span>
                    ';
                }

            }else{
                if($comment->ticket->overduestatus != null){
                    $output .= '
                    <span class="text-teal font-weight-semibold mx-1">'.$comment->ticket->status.'</span>
                    <span class="text-danger font-weight-semibold mx-1">'.$comment->ticket->overduestatus.'</span>
                    <span class="text-warning font-weight-semibold mx-1">Note</span>
                    ';
                }else{
                    $output .= '
                    <span class="text-info font-weight-semibold mx-1">'.$comment->ticket->status.'</span>
                    <span class="text-warning font-weight-semibold mx-1">Note</span>
                    ';
                }
            }

            $output .= '
                <p class="mb-0 fs-17 font-weight-semibold text-dark">'.$comment->cust->username.'<span class="fs-11 mx-1 text-muted">(Comment Modified)</span></p>
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


            return redirect()->back()->with('success', lang('Updated Successfully', 'alerts'));
        }


    }

    public function imagedestroy($id)
        {   //For Deleting Users
            $commentss = Media::findOrFail($id);
            $commentss->delete();
            return response()->json([
                'success' => lang('The image has been deleted successfully!', 'alerts')
            ]);
        }

}

