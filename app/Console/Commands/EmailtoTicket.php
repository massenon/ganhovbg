<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\Ticket\Ticket;
use App\Models\Customer;
use App\Models\User;
use App\Models\EmailTicket;
use App\Models\CustomerSetting;

use Mail;
use App\Mail\mailmailablesend;
use App\Notifications\TicketCreateNotifications;
use App\Models\tickethistory;
use \Webklex\IMAP\Facades\Client;
use Illuminate\Support\Facades\Auth;
use \Webklex\IMAP\Support\AttachmentCollection;
use File;
use Illuminate\Support\Str;
use App\Models\Setting;
use Carbon\Carbon;

class EmailtoTicket extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'imap:emailticket';

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
        
        if(setting('IMAP_STATUS') == 'on'){
            $client = Client::make([
                'host'          => setting('IMAP_HOST'),
                'port'          => setting('IMAP_PORT'),
                'encryption'    => setting('IMAP_ENCRYPTION'),
                'validate_cert' => true,
                'username'      => setting('IMAP_USERNAME'),
                'password'      => setting('IMAP_PASSWORD'),
                'protocol'      => setting('IMAP_PROTOCOL')
            ]);
            $client->connect();
            $aFolder = $client->getFolders();
            foreach($aFolder as $folder){
                foreach($folder->messages()->unseen()->get() as $message){
                    $userexits = Customer::where('email', $message->getFrom()[0]->mail)->count();
                    if($userexits == 1){
                        $guest = Customer::where('email', $message->getFrom()[0]->mail)->first();

                    }else{
                        $guest = Customer::create([

                            'firstname' => '',
                            'lastname' => '',
                            'username' => $message->getFrom()[0]->personal,
                            'email' => $message->getFrom()[0]->mail,
                            'userType' => 'Guest',
                            'password' => null,
                            'country' => '',
                            'timezone' => 'UTC',
                            'status' => '1',
                            'image' => null,

                        ]);
                        $customersetting = new CustomerSetting();
                        $customersetting->custs_id = $guest->id;
                        $customersetting->save();
                    }
                    $body = $message->getHTMLBody(true);
                    $stripped_body = strip_tags($body);

                    $ticket = Ticket::create([
                        'subject' => $message->getSubject(),
                        'cust_id' => $guest->id,
                        'category_id' => null,
                        'priority' => null,
                        'message' => $stripped_body,
                        'status' => 'New',
                    ]);
                    $ticket = Ticket::find($ticket->id);
                    if($guest->userType == 'Guest'){
                        $ticket->ticket_id = setting('CUSTOMER_TICKETID').'G-'.$ticket->id;
                    }else{
                        $ticket->ticket_id = setting('CUSTOMER_TICKETID').'-'.$ticket->id;
                    }
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

                    $finalfile = [];
                    $attachedfiles = $message->getAttachments();
                    foreach($attachedfiles as $attachedfile){
                        array_push($finalfile, $attachedfile->name);
                    }
                    $newArr = implode(",", $finalfile);
                    $ticket->emailticketfile =  $newArr;

                    $message->getAttachments()->each(function ($oAttachment) use ($message) {
                        file_put_contents(public_path('uploads/emailtoticket' . '/' . $oAttachment->name), $oAttachment->content);
                    });

                    $ticket->update();


                    $ticket = Ticket::find($ticket->id);
                    if($ticket->emailticketfile != null){
                        $arraytype = explode(',', $ticket->emailticketfile);
                        foreach($arraytype as $arraytypes){
                            $file_path = public_path('uploads/emailtoticket/'. $arraytypes);
                            $file_size = File::size($file_path) / 1024 / 1024;

                            $attachexten = explode(".", $arraytypes);
                            $appliexten = explode(",", setting('FILE_UPLOAD_TYPES'));
                            $appliextenfinal = Str::remove('.', $appliexten);
                            if(!in_array($attachexten[1], $appliextenfinal) || $file_size > setting('FILE_UPLOAD_MAX') || count($arraytype) > setting('MAX_FILE_UPLOAD')){
                                $ticket->emailticketfile = 'mismatch';
                                $ticket->update();
                                File::delete($file_path);

                            }
                        }
                    }


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
                        <p class="mb-0 fs-17 font-weight-semibold text-dark">Guest User<span class="fs-11 mx-1 text-muted">(Email Ticket)</span></p>
                    </div>
                    <div class="ms-auto">
                    <span class="float-end badge badge-danger-light">
                        <span class="fs-11 font-weight-semibold">' .$guest->userType . ' </span>
                    </span>
                    </div>

                    </div>
                    ';
                    $tickethistory->ticketactions = $output;
                    $tickethistory->save();


                    foreach(User::all() as $user){
                        $user->notify(new TicketCreateNotifications($ticket));
                    }

                    $ticketData = [
                        'ticket_id' => $ticket->ticket_id,
                        'ticket_username' => $ticket->cust->username,
                        'ticket_title' => $ticket->subject,
                        'ticket_file_format' => setting('FILE_UPLOAD_TYPES'),
                        'ticket_file_size' => setting('FILE_UPLOAD_MAX'),
                        'ticket_file_count' => setting('MAX_FILE_UPLOAD'),
                        'ticket_description' => $ticket->message,
                        'ticket_customer_url' => route('guest.ticketdetailshow', $ticket->ticket_id),
                        'ticket_admin_url' => url('/administration/ticket-view/'.$ticket->ticket_id),
                    ];

                    try{
                        if($ticket->emailticketfile == 'mismatch'){
                            Mail::to($ticket->cust->email)
                            ->send( new mailmailablesend( 'customer_send_guestticket_created_with_attachment_failed', $ticketData ) );
                        }else{
                            Mail::to($ticket->cust->email)
                            ->send( new mailmailablesend( 'customer_send_guestticket_created', $ticketData ) );
                        }

                        foreach(User::all() as $admin){
                            if($admin->getRoleNames()[0] == 'superadmin' && $admin->usetting->emailnotifyon == 1){
                                Mail::to($admin->email)
                                    ->send(new mailmailablesend('admin_send_email_ticket_created', $ticketData));
                            }
                        }

                    }catch(\Exception $e){

                    }
                    $message->setFlag('SEEN');
                }
            }
        }
    }
}
