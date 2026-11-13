<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Ticket\Ticket;
use Mail;
use App\Mail\mailmailablesend;
use App\Mail\VerifyMail;
use Auth;
use Carbon\Carbon;
use App\Models\tickethistory;

class AutoResponseTicket extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ticket:autoresponseticket';

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
        if(setting('AUTO_RESPONSETIME_TICKET') == 'yes'){

            $responsetimes = Ticket::get();

            foreach($responsetimes as $responsetime){
                if($responsetime->auto_replystatus != null){
                    if($responsetime->auto_replystatus->timezone(setting('default_timezone')) < now()->timezone(setting('default_timezone'))){

                        $responsetime->replystatus = 'Waiting for response';
                        $responsetime->auto_replystatus = null;
                        $responsetime->update();


                        $tickethistory = new tickethistory();
                        $tickethistory->ticket_id = $responsetime->id;

                        $output = '<div class="d-flex align-items-center">
                            <div class="mt-0">
                                <p class="mb-0 fs-12 mb-1">Status
                            ';
                        if($responsetime->ticketnote->isEmpty()){
                            if($responsetime->overduestatus != null){
                                $output .= '
                                <span class="text-danger font-weight-semibold mx-1">'.$responsetime->status.'</span>
                                <span class="text-warning font-weight-semibold mx-1">'.$responsetime->replystatus.'</span>
                                <span class="text-danger font-weight-semibold mx-1">'.$responsetime->overduestatus.'</span>
                                ';
                            }else{
                                $output .= '
                                <span class="text-danger font-weight-semibold mx-1">'.$responsetime->status.'</span>
                                <span class="text-warning font-weight-semibold mx-1">'.$responsetime->replystatus.'</span>
                                ';
                            }

                        }else{
                            if($responsetime->overduestatus != null){
                                $output .= '
                                <span class="text-danger font-weight-semibold mx-1">'.$responsetime->status.'</span>
                                <span class="text-warning font-weight-semibold mx-1">'.$responsetime->replystatus.'</span>
                                <span class="text-danger font-weight-semibold mx-1">'.$responsetime->overduestatus.'</span>
                                <span class="text-warning font-weight-semibold mx-1">Note</span>
                                ';
                            }else{
                                $output .= '
                                <span class="text-danger font-weight-semibold mx-1">'.$responsetime->status.'</span>
                                <span class="text-warning font-weight-semibold mx-1">'.$responsetime->replystatus.'</span>
                                <span class="text-warning font-weight-semibold mx-1">Note</span>
                                ';
                            }
                        }

                        $output .= '
                            <p class="mb-0 fs-17 font-weight-semibold text-dark">Auto Response</p>
                        </div>

                        </div>
                        ';
                        $tickethistory->ticketactions = $output;
                        $tickethistory->save();

                        $ticketData = [
                            'ticket_username' => $responsetime->cust->username,
                            'ticket_closingtime' => setting('AUTO_CLOSE_TICKET_TIME'),
                            'ticket_title' => $responsetime->subject,
                            'ticket_id' => $responsetime->ticket_id,
                            'ticket_description' => $responsetime->message,
                            'ticket_status' => $responsetime->status,
                            'replystatus' => $responsetime->replystatus,
                            'ticket_customer_url' => route('loadmore.load_data', $responsetime->ticket_id),
                            'ticket_admin_url' => url('/administration/ticket-view/'.$responsetime->ticket_id),
                        ];

                        try{

                            Mail::to($responsetime->cust->email)
                            ->send( new mailmailablesend( 'customer_send_ticket_response', $ticketData ) );

                        }catch(\Exception $e){
                            //
                        }
                        $cust = Customer::find($responsetime->cust_id);
                        $cust->notify(new TicketCreateNotifications($responsetime));

                    }
                }

            }
        }

    }
}
