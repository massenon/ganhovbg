<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\Ticket\Ticket;
use App\Models\Setting;

class TrashedTicketsAutoDelete extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'trashedticket:autodelete';

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
        if(setting('trashed_ticket_autodelete') == 'on'){
            $trashedttt = Ticket::onlyTrashed()->get();
            foreach($trashedttt as $trashedtt)
            {
                if ($trashedtt != null) {
                    if($trashedtt->deleted_at->timezone(setting('default_timezone'))->adddays(setting('trashed_ticket_delete_time')) < now()->timezone(setting('default_timezone')))
                    {
                        $trashedtt->forceDelete();
                    }
                }
            }
        }
    }
}
