<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Artisan;
use App\Models\Setting;
use Carbon\Carbon;

class UpdateData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Dataseed:updating';

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
        if(setting('newupdate') == 'version3.0'){
            Artisan::call('migrate');
            Artisan::call('db:seed LanguageSeeder');
            Artisan::call('db:seed SettingUpdateSeeder');
            Artisan::call('db:seed Permissiongroupupdate');
            Artisan::call('db:seed TimezoneSeeder');
            Artisan::call('db:seed TranslationSeeder');
            Artisan::call('db:seed NewUpdateSeederV3_1');
            Artisan::call('route:cache');
            Artisan::call('config:cache');
            Artisan::call('view:clear');
            Artisan::call('optimize');
            Artisan::call('optimize:clear');

            $user = Setting::where('key','newupdate')->first();
            $user->value = 'updated3.1.2';
            $user->update();

            $userset = Setting::where('key','envato_purchasecode')->first();
            $userset->key = 'update_setting';
            $userset->update();
        }

        if(setting('newupdate') == 'version3.1'){
            Artisan::call('migrate');
            Artisan::call('db:seed NewUpdateSeederV3_1');
            Artisan::call('route:cache');
            Artisan::call('config:cache');
            Artisan::call('view:clear');
            Artisan::call('optimize');
            Artisan::call('optimize:clear');

            $user = Setting::where('key','newupdate')->first();
            $user->value = 'updated3.1.2';
            $user->update();

            $userset = Setting::where('key','envato_purchasecode')->first();
            $userset->key = 'update_setting';
            $userset->update();
        }
    }
}
