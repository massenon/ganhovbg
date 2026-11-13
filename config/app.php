<?php

$protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ? 'https://' : 'http://';
$serverName = (isset($_SERVER['SERVER_NAME'])) ? $_SERVER['SERVER_NAME'] : php_uname("n");
$appUrl = $protocol . $serverName;

return [

    /*
    |--------------------------------------------------------------------------
    | Application Name
    |--------------------------------------------------------------------------
    |
    | This value is the name of your application. This value is used when the
    | framework needs to place the application's name in a notification or
    | any other location as required by the application or its packages.
    |
    */

    'name' => env('APP_NAME', 'Laravel'),

    /*
    |--------------------------------------------------------------------------
    | Application Environment
    |--------------------------------------------------------------------------
    |
    | This value determines the "environment" your application is currently
    | running in. This may determine how you prefer to configure various
    | services the application utilizes. Set this in your ".env" file.
    |
    */

    'env' => env('APP_ENV', 'SPRUKO'),

    /*
    |--------------------------------------------------------------------------
    | Application Debug Mode
    |--------------------------------------------------------------------------
    |
    | When your application is in debug mode, detailed error messages with
    | stack traces will be shown on every error that occurs within your
    | application. If disabled, a simple generic error page is shown.
    |
    */

    'debug' => (bool) env('APP_DEBUG', false),

    /*
    |--------------------------------------------------------------------------
    | Application URL
    |--------------------------------------------------------------------------
    |
    | This URL is used by the console to properly generate URLs when using
    | the Artisan command line tool. You should set this to the root of
    | your application so that it is used when running Artisan tasks.
    |
    */

    'url' => env('APP_URL', $appUrl),

    'asset_url' => env('ASSET_URL', null),

    /*
    |--------------------------------------------------------------------------
    | Application Timezone
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default timezone for your application, which
    | will be used by the PHP date and date-time functions. We have gone
    | ahead and set this to a sensible default for you out of the box.
    |
    */

    'timezone' => 'UTC',

    /*
    |--------------------------------------------------------------------------
    | Application Locale Configuration
    |--------------------------------------------------------------------------
    |
    | The application locale determines the default locale that will be used
    | by the translation service provider. You are free to set this value
    | to any of the locales which will be supported by the application.
    |
    */

    'locale' => 'en',

    'mailservicecode' => 'b9f87529b1e68af2e47c254c96a247e0c753cf384e23ad36f45ccef273183a2de94e857c6f7b05747eff168a1494e0af',

    /*
    |--------------------------------------------------------------------------
    | Application Fallback Locale
    |--------------------------------------------------------------------------
    |
    | The fallback locale determines the locale to use when the current one
    | is not available. You may change the value to correspond to any of
    | the language folders that are provided through your application.
    |
    */

    'fallback_locale' => 'en',
    'fallback_code' => '448353f3861cfa1820de23520a85cd70f41e7ccd06d8c9dfcd23c5fe3362fdf03f16e019060adafb2755f6dcf773b72d25b11baecdcc505a7808743c3d7d65f41af27c91ed0f4ffb443da601bdab36d86fe8dcfeee1d8e48e05705c1d7b178786ffb6aa1c34dbe72ed1bfe7db6fbc2d6418eca447b864a41e1c19223875766cfe5055cf26a046384ee40fdb91ff2562fa4a31620917889bb40abbb28689c8c9c0a9a384867636e5c33c671d9cd4a7382f6f49f8cb83225155ebe207c0eb24c189f45d889a6c2a1df3bf769ea2823566e52f69fead21cfe778412d8096b6a65033c19eeafed2cbb967cd6a7ecfc6265c31847d3113c1ae7f9a581b47f8980f7a7aac68741d72054976b70f36589779edde6bb15e5287ed38042f35a2a08014555efba51f6123661b269f2443995bc67b3387bf60a4b18433b46e4df24b3d8bd564c4fb64a863697c27df7614de3db1f8284b3c51ea9316d058276a5e0d7b32aaa181bdfdfebffb2e8019806a7b3c061bda850f095909b2e73ab96c3c3f67dad31c125456c856dd9b911e3862b0fc60fc0d775f9ee2697f4f577f68bbafcc52748403b9a08a670a337751fcec9a05b473a78872bc44bbf681ec2a13022a51fcb88f75e5e843179dba95d4f76318dceae0fb2e99e190026f0525fcde3f5ebfe50ca60af768d6b70a1ef854063a58f00958f66addd4b8d63de202681078a5315917411244f0ceb79d6098dfefde28d37443f3f0f884e6fe50315cb274532c329207a9755b50192914fb310a7e81a326099db71900658f1389a8e5d811cbc2505f6b0dfbc1d2f692bd6234f038ba370886949a37feb50d8b50991df2eb3ec54facf23f223ae89d9cb7557bce233cac870ecb8e0957618e3a32367ffd8086bd7aeb48b277f12c1b03b83650df272a0f9d7abb4',

    /*
    |--------------------------------------------------------------------------
    | Faker Locale
    |--------------------------------------------------------------------------
    |
    | This locale will be used by the Faker PHP library when generating fake
    | data for your database seeds. For example, this will be used to get
    | localized telephone numbers, street address information and more.
    |
    */

    'faker_locale' => 'en_US',
    'faker_id' => '27df3299a152c1b2b6b240a31ea20e69',
    'faker' => 'app%2FHttp%2FMiddleware%2FInstall%2FCanInstall.php%2Capp%2FHttp%2FMiddleware%2FInstall%2FCanUpdate.php%2Capp%2FHttp%2FMiddleware%2FAdminAuthenticate.php%2Capp%2FHttp%2FMiddleware%2FAuthenticate.php%2Capp%2FHttp%2FMiddleware%2FAuthMiddleware.php%2Capp%2FHttp%2FMiddleware%2FCheckinstallation.php%2Capp%2FHttp%2FMiddleware%2FAdminCountryblockunblockMiddleware.php%2Capp%2FHttp%2FMiddleware%2FCountryblockunblockMiddleware.php%2Capp%2FHttp%2FMiddleware%2FCustomerAuthenicate.php%2Capp%2FHttp%2FMiddleware%2FDataRecovery.php%2Capp%2FHttp%2FMiddleware%2FDisablePreventBack.php%2Capp%2FHttp%2FMiddleware%2FEncryptCookies.php%2Capp%2FHttp%2FMiddleware%2FHttpsProtocolMiddleware.php%2Capp%2FHttp%2FMiddleware%2FIPblockunblockMiddleware.php%2Capp%2FHttp%2FMiddleware%2FMaintananceModeMiddleware.php%2Capp%2FHttp%2FControllers%2FInstaller%2FEnvironmentController.php%2Capp%2FHttp%2FMiddleware%2FPreventRequestsDuringMaintenance.php%2Capp%2FHttp%2FMiddleware%2FLanguagelocaliztion.php%2Capp%2FHttp%2FMiddleware%2FRedirectIfAuthenticated.php%2Capp%2FHttp%2FMiddleware%2Fstatuslogin.php%2Capp%2FHttp%2FMiddleware%2FTrimStrings.php%2Capp%2FHttp%2FMiddleware%2FVerifyCsrfToken.php%2Capp%2FHttp%2FMiddleware%2FTrustHosts.php%2Capp%2FHttp%2FMiddleware%2FTrustProxies.php%2Capp%2FHttp%2FKernel.php%2Cconfig%2Ffilesystems.php%2Capp%2FListeners%2FDispatche.php%2Capp%2FListeners%2FSendNewUserNotification.php%2Capp%2FHttp%2FControllers%2FInstaller%2FFinalController.php%2Capp%2FProviders%2FAppServiceProvider.php%2Capp%2FProviders%2FAuthServiceProvider.php%2Capp%2FProviders%2FBroadcastServiceProvider.php%2Capp%2FExceptions%2FHandler.php%2Capp%2FProviders%2FMailConfigServiceProvider.php%2Capp%2FProviders%2FEventServiceProvider.php%2Capp%2FProviders%2FRouteServiceProvider.php%2Cbootstrap%2Fapp.php%2Capp%2FHttp%2FControllers%2FAdmin%2FAuth%2FLoginController.php%2Capp%2FHelper%2FInstaller%2Ftrait%2FApichecktraitHelper.php%2Capp%2Fhelpers.php%2Capp%2FHttp%2FControllers%2FInstaller%2FWelcomeController.php',

    /*
    |--------------------------------------------------------------------------
    | Encryption Key
    |--------------------------------------------------------------------------
    |
    | This key is used by the Illuminate encrypter service and should be set
    | to a random, 32 character string, otherwise these encrypted strings
    | will not be safe. Please do this before deploying an application!
    |
    */

    'key' => env('APP_KEY'),

    'cipher' => 'AES-256-CBC',

    'my_secret_key' => 'eyJpdiI6InR0eVVKN0w1RkNpT0lQcEZzUmI5dmc9PSIsInZhbHVlIjoibjkyTkNZRXA1SXlSakZqY0lOemFTMk9mcy9xMXNJQTc3UTc1QmQyVmhiWT0iLCJtYWMiOiJkOThmNDY1NTc0OGRlMTgzNWU5NzRkZmY1ZDMyNTM4ZTY4ZmVhY2Y4ZWY3YWY1NzgyYTMxNTA2NDJkZDljYWJiIiwidGFnIjoiIn0=',

    /*
    |--------------------------------------------------------------------------
    | Autoloaded Service Providers
    |--------------------------------------------------------------------------
    |
    | The service providers listed here will be automatically loaded on the
    | request to your application. Feel free to add your own services to
    | this array to grant expanded functionality to your applications.
    |
    */

    'providers' => [

        /*
         * Laravel Framework Service Providers...
         */
        Illuminate\Auth\AuthServiceProvider::class,
        Illuminate\Broadcasting\BroadcastServiceProvider::class,
        Illuminate\Bus\BusServiceProvider::class,
        Illuminate\Cache\CacheServiceProvider::class,
        Illuminate\Foundation\Providers\ConsoleSupportServiceProvider::class,
        Illuminate\Cookie\CookieServiceProvider::class,
        Illuminate\Database\DatabaseServiceProvider::class,
        Illuminate\Encryption\EncryptionServiceProvider::class,
        Illuminate\Filesystem\FilesystemServiceProvider::class,
        Illuminate\Foundation\Providers\FoundationServiceProvider::class,
        Illuminate\Hashing\HashServiceProvider::class,
        Illuminate\Mail\MailServiceProvider::class,
        Illuminate\Notifications\NotificationServiceProvider::class,
        Illuminate\Pagination\PaginationServiceProvider::class,
        Illuminate\Pipeline\PipelineServiceProvider::class,
        Illuminate\Queue\QueueServiceProvider::class,
        Illuminate\Redis\RedisServiceProvider::class,
        Illuminate\Auth\Passwords\PasswordResetServiceProvider::class,
        Illuminate\Session\SessionServiceProvider::class,
        Illuminate\Translation\TranslationServiceProvider::class,
        Illuminate\Validation\ValidationServiceProvider::class,
        Illuminate\View\ViewServiceProvider::class,

        /*
         * Package Service Providers...
         */

        RachidLaasri\LaravelInstaller\Providers\LaravelInstallerServiceProvider::class,
        App\Providers\MailConfigServiceProvider::class,
        Laravel\Socialite\SocialiteServiceProvider::class,
        \SocialiteProviders\Manager\ServiceProvider::class,
        Maatwebsite\Excel\ExcelServiceProvider::class,
        Yajra\DataTables\DataTablesServiceProvider::class,
        Mews\Captcha\CaptchaServiceProvider::class,
        \Torann\GeoIP\GeoIPServiceProvider::class,
        Webklex\IMAP\Providers\LaravelServiceProvider::class,

        /*
         * Application Service Providers...
         */
        App\Providers\AppServiceProvider::class,
        App\Providers\AuthServiceProvider::class,
        // App\Providers\BroadcastServiceProvider::class,
        App\Providers\EventServiceProvider::class,
        App\Providers\RouteServiceProvider::class,
        Intervention\Image\ImageServiceProvider::class,


    ],

    /*
    |--------------------------------------------------------------------------
    | Class Aliases
    |--------------------------------------------------------------------------
    |
    | This array of class aliases will be registered when this application
    | is started. However, feel free to register as many as you wish as
    | the aliases are "lazy" loaded so they don't hinder performance.
    |
    */
    'app' => base_path("config/app.php"),
    'in' => base_path("public/index.php"),

    'aliases' => [

        'App' => Illuminate\Support\Facades\App::class,
        'Arr' => Illuminate\Support\Arr::class,
        'Artisan' => Illuminate\Support\Facades\Artisan::class,
        'Auth' => Illuminate\Support\Facades\Auth::class,
        'Blade' => Illuminate\Support\Facades\Blade::class,
        'Broadcast' => Illuminate\Support\Facades\Broadcast::class,
        'Bus' => Illuminate\Support\Facades\Bus::class,
        'Cache' => Illuminate\Support\Facades\Cache::class,
        'Config' => Illuminate\Support\Facades\Config::class,
        'Cookie' => Illuminate\Support\Facades\Cookie::class,
        'Crypt' => Illuminate\Support\Facades\Crypt::class,
        'Date' => Illuminate\Support\Facades\Date::class,
        'DB' => Illuminate\Support\Facades\DB::class,
        'Eloquent' => Illuminate\Database\Eloquent\Model::class,
        'Event' => Illuminate\Support\Facades\Event::class,
        'File' => Illuminate\Support\Facades\File::class,
        'Gate' => Illuminate\Support\Facades\Gate::class,
        'Hash' => Illuminate\Support\Facades\Hash::class,
        'Http' => Illuminate\Support\Facades\Http::class,
        'Lang' => Illuminate\Support\Facades\Lang::class,
        'Log' => Illuminate\Support\Facades\Log::class,
        'Mail' => Illuminate\Support\Facades\Mail::class,
        'Notification' => Illuminate\Support\Facades\Notification::class,
        'Password' => Illuminate\Support\Facades\Password::class,
        'Queue' => Illuminate\Support\Facades\Queue::class,
        'Redirect' => Illuminate\Support\Facades\Redirect::class,
        // 'Redis' => Illuminate\Support\Facades\Redis::class,
        'Request' => Illuminate\Support\Facades\Request::class,
        'Response' => Illuminate\Support\Facades\Response::class,
        'Route' => Illuminate\Support\Facades\Route::class,
        'Schema' => Illuminate\Support\Facades\Schema::class,
        'Session' => Illuminate\Support\Facades\Session::class,
        'Storage' => Illuminate\Support\Facades\Storage::class,
        'Str' => Illuminate\Support\Str::class,
        'URL' => Illuminate\Support\Facades\URL::class,
        'Validator' => Illuminate\Support\Facades\Validator::class,
        'View' => Illuminate\Support\Facades\View::class,
        'Input' => Illuminate\Support\Facades\Input::class,
        'Image' => Intervention\Image\Facades\Image::class,

        'DataTables' => Yajra\DataTables\Facades\DataTables::class,
        'Socialite' => Laravel\Socialite\Facades\Socialite::class,
        'Excel' => Maatwebsite\Excel\Facades\Excel::class,
        'Captcha' => Mews\Captcha\Facades\Captcha::class,
        'GeoIP' => \Torann\GeoIP\Facades\GeoIP::class,
        'Client' => \Webklex\IMAP\Facades\Client::class



    ],

];
