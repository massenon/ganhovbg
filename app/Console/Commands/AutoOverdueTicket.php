<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Ticket\Ticket;
use App\Models\User;
use Mail;
use App\Mail\mailmailablesend;
use App\Mail\VerifyMail;
use Auth;
use App\Notifications\TicketCreateNotifications;
use App\Models\tickethistory;

class AutoOverdueTicket extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ticket:autooverdue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if(setting('AUTO_OVERDUE_TICKET') == 'yes'){
            $autooverdues = Ticket::get();

            foreach($autooverdues as $autooverdue){
                if($autooverdue->auto_overdue_ticket != null){
                    if($autooverdue->auto_overdue_ticket->timezone(setting('default_timezone')) < now()->timezone(setting('default_timezone'))){
                        $autooverdue->overduestatus = 'Overdue';
                        $autooverdue->auto_overdue_ticket = null;

                        $autooverdue->update();


                        $tickethistory = new tickethistory();
                        $tickethistory->ticket_id = $autooverdue->id;

                        $output = '<div class="d-flex align-items-center">
                            <div class="mt-0">
                                <p class="mb-0 fs-12 mb-1">Status
                            ';
                        if($autooverdue->ticketnote->isEmpty()){
                            if($autooverdue->overduestatus != null){
                                $output .= '
                                <span class="text-danger font-weight-semibold mx-1">'.$autooverdue->status.'</span>
                                <span class="text-danger font-weight-semibold mx-1">'.$autooverdue->overduestatus.'</span>
                                ';
                            }else{
                                $output .= '
                                <span class="text-danger font-weight-semibold mx-1">'.$autooverdue->status.'</span>
                                ';
                            }

                        }else{
                            if($autooverdue->overduestatus != null){
                                $output .= '
                                <span class="text-danger font-weight-semibold mx-1">'.$autooverdue->status.'</span>
                                <span class="text-danger font-weight-semibold mx-1">'.$autooverdue->overduestatus.'</span>
                                <span class="text-warning font-weight-semibold mx-1">Note</span>
                                ';
                            }else{
                                $output .= '
                                <span class="text-danger font-weight-semibold mx-1">'.$autooverdue->status.'</span>
                                <span class="text-warning font-weight-semibold mx-1">Note</span>
                                ';
                            }
                        }

                        $output .= '
                            <p class="mb-0 fs-17 font-weight-semibold text-dark">Auto Overdue</p>
                        </div>

                        </div>
                        ';
                        $tickethistory->ticketactions = $output;
                        $tickethistory->save();

                        $ticketData = [
                            'ticket_title' => $autooverdue->subject,
                            'ticket_overduetime' => setting('AUTO_OVERDUE_TICKET_TIME'),
                            'ticket_id' => $autooverdue->ticket_id,
                            'ticket_description' => $autooverdue->message,
                            'ticket_status' => $autooverdue->status,
                            'ticket_customer_url' => route('loadmore.load_data', $autooverdue->ticket_id),
                            'ticket_admin_url' => url('/administration/ticket-view/'.$autooverdue->ticket_id),
                        ];

                        try{

                            if($autooverdue->category)
                            {
                                $notificationcatss = $autooverdue->category->groupscategoryc()->get();
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
                                            if($admin->getRoleNames()[0] != 'superadmin'){
                                                $admin->notify(new TicketCreateNotifications($autooverdue));
                                                if($admin->usetting->emailnotifyon == 1){
                                                    Mail::to($admin->email)
                                                    ->send( new mailmailablesend( 'customer_send_ticket_overdue', $ticketData ) );
                                                }
                                            }
                                        }

                                    }else{

                                        if($autooverdue->myassignuser){
                                            $assignee = $autooverdue->ticketassignmutliples;
                                            foreach($assignee as $assignees){
                                                $user = User::where('id',$assignees->toassignuser_id)->get();
                                                foreach($user as $users){
                                                    if($users->id == $assignees->toassignuser_id && $users->getRoleNames()[0] != 'superadmin'){
                                                        $users->notify(new TicketCreateNotifications($autooverdue));
                                                        if($users->usetting->emailnotifyon == 1){
                                                            Mail::to($users->email)
                                                            ->send( new mailmailablesend( 'customer_send_ticket_overdue', $ticketData ) );
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                        else if ($autooverdue->selfassignuser_id) {
                                            $self = User::findOrFail($autooverdue->selfassignuser_id);
                                            if($self->getRoleNames()[0] != 'superadmin'){
                                                $self->notify(new TicketCreateNotifications($autooverdue));
                                                if($self->usetting->emailnotifyon == 1){
                                                    Mail::to($self->email)
                                                    ->send( new mailmailablesend( 'customer_send_ticket_overdue', $ticketData ) );
                                                }
                                            }
                                        }
                                        else if($icc ){
                                            $user = User::whereIn('id', $icc)->get();
                                            foreach($user as $users){
                                                $users->notify(new TicketCreateNotifications($autooverdue));
                                                if($users->usetting->emailnotifyon == 1){
                                                    Mail::to($users->email)
                                                    ->send( new mailmailablesend( 'customer_send_ticket_overdue', $ticketData ) );
                                                }
                                            }
                                        }
                                        else {
                                            $users = User::leftJoin('groups_users','groups_users.users_id','users.id')->whereNull('groups_users.groups_id')->whereNull('groups_users.users_id')->get();
                                            foreach($users as $user){
                                                if($user->getRoleNames()[0] != 'superadmin'){
                                                    $user->notify(new TicketCreateNotifications($autooverdue));
                                                    if($user->usetting->emailnotifyon == 1){
                                                        Mail::to($user->email)
                                                        ->send( new mailmailablesend( 'customer_send_ticket_overdue', $ticketData ) );
                                                    }
                                                }
                                            }
                                        }

                                    }
                                }else{

                                    if($autooverdue->myassignuser){
                                        $assignee = $autooverdue->ticketassignmutliples;
                                        foreach($assignee as $assignees){
                                            $user = User::where('id',$assignees->toassignuser_id)->get();
                                            foreach($user as $users){
                                                if($users->id == $assignees->toassignuser_id && $users->getRoleNames()[0] != 'superadmin'){
                                                    $users->notify(new TicketCreateNotifications($autooverdue));
                                                    if($users->usetting->emailnotifyon == 1){
                                                        Mail::to($users->email)
                                                        ->send( new mailmailablesend( 'customer_send_ticket_overdue', $ticketData ) );
                                                    }
                                                }
                                            }
                                        }
                                    } else if ($autooverdue->selfassignuser_id) {
                                        $self = User::findOrFail($autooverdue->selfassignuser_id);
                                        if($self->getRoleNames()[0] != 'superadmin'){
                                            $self->notify(new TicketCreateNotifications($autooverdue));
                                            if($self->usetting->emailnotifyon == 1){
                                                Mail::to($self->email)
                                                ->send( new mailmailablesend( 'customer_send_ticket_overdue', $ticketData ) );
                                            }
                                        }
                                    } else {

                                        $users = User::leftJoin('groups_users','groups_users.users_id','users.id')->whereNull('groups_users.groups_id')->whereNull('groups_users.users_id')->get();
                                        foreach($users as $user){
                                            if($user->getRoleNames()[0] != 'superadmin'){
                                                $user->notify(new TicketCreateNotifications($autooverdue));
                                                if($user->usetting->emailnotifyon == 1){
                                                    Mail::to($user->email)
                                                    ->send( new mailmailablesend( 'customer_send_ticket_overdue', $ticketData ) );
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                            if(!$autooverdue->category)
                            {

                                if($autooverdue->myassignuser){
                                    $assignee = $autooverdue->ticketassignmutliples;
                                    foreach($assignee as $assignees){
                                        $user = User::where('id',$assignees->toassignuser_id)->get();
                                        foreach($user as $users){
                                            if($users->id == $assignees->toassignuser_id && $users->getRoleNames()[0] != 'superadmin'){
                                                $users->notify(new TicketCreateNotifications($autooverdue));
                                                if($users->usetting->emailnotifyon == 1){
                                                    Mail::to($users->email)
                                                    ->send( new mailmailablesend( 'customer_send_ticket_overdue', $ticketData ) );
                                                }
                                            }
                                        }
                                    }
                                } else if ($autooverdue->selfassignuser_id) {
                                    $self = User::findOrFail($autooverdue->selfassignuser_id);
                                    if($self->getRoleNames()[0] != 'superadmin'){
                                        $self->notify(new TicketCreateNotifications($autooverdue));
                                        if($self->usetting->emailnotifyon == 1){
                                            Mail::to($self->email)
                                            ->send( new mailmailablesend( 'customer_send_ticket_overdue', $ticketData ) );
                                        }
                                    }
                                } else {

                                    $users = User::leftJoin('groups_users','groups_users.users_id','users.id')->whereNull('groups_users.groups_id')->whereNull('groups_users.users_id')->get();
                                    foreach($users as $user){
                                        if($user->getRoleNames()[0] != 'superadmin'){
                                            $user->notify(new TicketCreateNotifications($autooverdue));
                                            if($user->usetting->emailnotifyon == 1){
                                                Mail::to($user->email)
                                                ->send( new mailmailablesend( 'customer_send_ticket_overdue', $ticketData ) );
                                            }
                                        }
                                    }
                                }

                            }

                            $admins = User::leftJoin('groups_users','groups_users.users_id','users.id')->whereNull('groups_users.groups_id')->whereNull('groups_users.users_id')->get();
                            foreach($admins as $admin){
                                if($admin->getRoleNames()[0] == 'superadmin'){
                                    $admin->notify(new TicketCreateNotifications($autooverdue));
                                    if($admin->usetting->emailnotifyon == 1){
                                        Mail::to($admin->email)
                                        ->send( new mailmailablesend( 'customer_send_ticket_overdue', $ticketData ) );
                                    }
                                }
                            }



                        }catch(\Exception $e){
                            //
                        }
                    }
                }
            }
        }

    }
}
