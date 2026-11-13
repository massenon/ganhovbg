<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\Customer;
use Carbon\Carbon;
use Mail;
use App\Mail\mailmailablesend;


class CustomerAutoDelete extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'customer:inactive_delete';

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


        $customers = Customer::get();

        foreach($customers as $customer)
        {
            if(setting('customer_inactive_notify') == 'on'){

                if($customer->last_logins_at != null)
                {
                    if(Carbon::parse($customer->last_logins_at)->addMonths(setting('customer_inactive_notify_date'))->timezone(setting('default_timezone')) < now()->timezone(setting('default_timezone')))
                    {
                        if($customer->inactive_date == null)
                        {
                            $customersData = [
                                'customer_username' =>$customer->username,
                                'customer_email' => $customer->email,
                                'customer_time' => setting('customer_inactive_week_date') .' days',
                                'customer_months' => setting('customer_inactive_notify_date') .' months',
                            ];
                            try{

                                Mail::to($customer->email)
                                ->send( new mailmailablesend( 'when_send_customnotify_email_todelete_member', $customersData));


                            }catch(\Exception $e){


                                $customer->inactive_date =  now();
                                $customer->update();
                            }

                            $customer->inactive_date =  now();
                            $customer->last_logins_at =  null;
                            $customer->update();
                        }

                    }
                }

                if($customer->inactive_date != null)
                {

                    if(Carbon::parse($customer->inactive_date)->addDays(setting('customer_inactive_week_date'))->timezone(setting('default_timezone')) < now()->timezone(setting('default_timezone')))
                    {
                        $ticket = $customer->tickets()->get();

                        foreach ($ticket as $tickets) {
                            foreach ($tickets->getMedia('ticket') as $media) {
                                $media->delete();
                            }
                            foreach($tickets->comments()->get() as $comment ){

                                foreach ($comment->getMedia('comments') as $media) {
                                    $media->delete();
                                }

                                $comment->delete();

                            }

                            $tickets->delete();

                        }
                        $customer->custsetting()->delete();
                        $customer->customercustomsetting()->delete();

                        $customer->delete();
                    }
                }
            }

            if(setting('guest_inactive_notify') == 'on'){

                if($customer->userType == 'Guest')
                {
                    if(Carbon::parse($customer->updated_at)->addMonths(setting('guest_inactive_notify_date'))->timezone(setting('default_timezone')) < now()->timezone(setting('default_timezone')))
                    {
                        if($customer->inactive_date == null)
                        {
                            $customersData = [
                                'customer_username' =>$customer->username,
                                'customer_email' => $customer->email,
                                'customer_time' => setting('guest_inactive_week_date') .' days',
                                'customer_months' => setting('guest_inactive_notify_date') .' months',
                            ];
                            try{

                                Mail::to($customer->email)
                                ->send( new mailmailablesend( 'when_send_customnotify_email_todelete_member', $customersData));


                            }catch(\Exception $e){


                                $customer->inactive_date =  now();
                                $customer->update();
                            }

                            $customer->inactive_date =  now();
                            $customer->last_logins_at =  null;
                            $customer->update();
                        }

                    }


                    if($customer->inactive_date != null)
                    {

                        if(Carbon::parse($customer->inactive_date)->addDays(setting('guest_inactive_week_date'))->timezone(setting('default_timezone')) < now()->timezone(setting('default_timezone')))
                        {
                            $ticket = $customer->tickets()->get();

                            foreach ($ticket as $tickets) {
                                foreach ($tickets->getMedia('ticket') as $media) {
                                    $media->delete();
                                }
                                foreach($tickets->comments()->get() as $comment ){

                                    foreach ($comment->getMedia('comments') as $media) {
                                        $media->delete();
                                    }

                                    $comment->delete();

                                }

                                $tickets->delete();

                            }
                            $customer->custsetting()->delete();
                            $customer->customercustomsetting()->delete();

                            $customer->delete();
                        }
                    }
                }
            }
        }

    }
}
