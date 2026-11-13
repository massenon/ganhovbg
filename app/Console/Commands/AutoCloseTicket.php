<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use App\Models\Ticket\Ticket;
use Mail;
use App\Mail\mailmailablesend;
use App\Mail\VerifyMail;
use Auth;
use App\Models\Customer;
use App\Notifications\TicketCreateNotifications;
use App\Models\tickethistory;

class AutoCloseTicket extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ticket:autoclose';

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

        if(setting('AUTO_CLOSE_TICKET') == 'yes'){
            $autocloses = Ticket::get();
            foreach($autocloses as $autoclose){
                if($autoclose->auto_close_ticket != null){
                    if($autoclose->auto_close_ticket->timezone(setting('default_timezone')) < now()->timezone(setting('default_timezone')))
                    {
                        if($autoclose->replystatus == 'Solved'){
                            $autoclose->status = 'Closed';
                            $autoclose->closing_ticket = now();
                            $autoclose->auto_close_ticket = null;
                        }else{
                            $autoclose->status = 'Closed';
                            $autoclose->replystatus = 'Unanswered';
                            $autoclose->closing_ticket = now();
                            $autoclose->auto_close_ticket = null;
                        }
                        $autoclose->update();

                        $tickethistory = new tickethistory();
                        $tickethistory->ticket_id = $autoclose->id;

                        $output = '<div class="d-flex align-items-center">
                            <div class="mt-0">
                                <p class="mb-0 fs-12 mb-1">Status
                            ';
                        if($autoclose->ticketnote->isEmpty()){
                            if($autoclose->overduestatus != null){
                                $output .= '
                                <span class="text-danger font-weight-semibold mx-1">'.$autoclose->status.'</span>
                                <span class="text-danger font-weight-semibold mx-1">'.$autoclose->replystatus.'</span>
                                <span class="text-danger font-weight-semibold mx-1">'.$autoclose->overduestatus.'</span>
                                ';
                            }else{
                                $output .= '
                                <span class="text-danger font-weight-semibold mx-1">'.$autoclose->status.'</span>
                                <span class="text-danger font-weight-semibold mx-1">'.$autoclose->replystatus.'</span>
                                ';
                            }
                            
                        }else{
                            if($autoclose->overduestatus != null){
                                $output .= '
                                <span class="text-danger font-weight-semibold mx-1">'.$autoclose->status.'</span>
                                <span class="text-danger font-weight-semibold mx-1">'.$autoclose->replystatus.'</span>
                                <span class="text-danger font-weight-semibold mx-1">'.$autoclose->overduestatus.'</span>
                                <span class="text-warning font-weight-semibold mx-1">Note</span>
                                ';
                            }else{
                                $output .= '
                                <span class="text-danger font-weight-semibold mx-1">'.$autoclose->status.'</span>
                                <span class="text-danger font-weight-semibold mx-1">'.$autoclose->replystatus.'</span>
                                <span class="text-warning font-weight-semibold mx-1">Note</span>
                                ';
                            }
                        }

                        $output .= '
                            <p class="mb-0 fs-17 font-weight-semibold text-dark">Auto Close</p>
                        </div>
                        
                        </div>
                        ';
                        $tickethistory->ticketactions = $output;
                        $tickethistory->save();
            
                        $ticketData = [
                            'ticket_username' => $autoclose->cust->username,
                            'ticket_id' => $autoclose->ticket_id,
                            'ticket_title' => $autoclose->subject,
                            'ticket_description' => $autoclose->message,
                            'ticket_status' => $autoclose->status,
                            'ticket_customer_url' => route('loadmore.load_data', $autoclose->ticket_id),
                            'ticket_admin_url' => url('/administration/ticket-view/'.$autoclose->ticket_id),
                        ];
                
                        try{
                            Mail::to($autoclose->cust->email)
                            ->send( new mailmailablesend( 'customer_send_ticket_autoclose', $ticketData ) );
                        }
                        catch(\Exception $e){
                            //
                        }
            
                        $cust = Customer::find($autoclose->cust_id);
                        $cust->notify(new TicketCreateNotifications($autoclose));
                    }
                }
                
            }
        }
        
       

    }

}
