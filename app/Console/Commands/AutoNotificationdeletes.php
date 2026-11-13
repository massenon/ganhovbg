<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Auth;
use App\Models\User;
use App\Models\Customer;
use App\Models\tickethistory;
use App\Models\Setting;
use Carbon\Carbon;

class AutoNotificationdeletes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notification:autodelete';

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

        $cronset = Setting::where('key','cronjob_set')->first();
        $cronset->updated_at = now();
        $cronset->update();

        if(setting('AUTO_NOTIFICATION_DELETE_ENABLE') == 'on'){


            $users = User::get();
            foreach($users as $user){
                foreach($user->notifications as $del){
                    if($del->read_at != null){
                        $read = $del->read_at->adddays(setting('AUTO_NOTIFICATION_DELETE_DAYS'));
                        if($read <= now()->timezone(setting('default_timezone'))){

                            $del->delete();
                        }
                    }
                }

            }


            $custs = Customer::get();
            foreach($custs as $cust){
                foreach($cust->notifications as $custdel){
                    if($custdel->read_at != null){
                        $read = $custdel->read_at->adddays(setting('AUTO_NOTIFICATION_DELETE_DAYS'));
                        if($read <= now()->timezone(setting('default_timezone'))){

                            $custdel->delete();
                        }
                    }
                }
            }
        }

    }
}
