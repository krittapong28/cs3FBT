<?php

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

    'env' => env('APP_ENV', 'production'),

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

    'url' => env('APP_URL', 'http://localhost'),

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

    'timezone' => 'Asia/Bangkok',

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

    'locale' => 'th',

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

        /*
         * Application Service Providers...
         */
        App\Providers\AppServiceProvider::class,
        App\Providers\AuthServiceProvider::class,
        // App\Providers\BroadcastServiceProvider::class,
        App\Providers\EventServiceProvider::class,
        App\Providers\RouteServiceProvider::class,

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
        'RateLimiter' => Illuminate\Support\Facades\RateLimiter::class,
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
        'Helper' => App\Helper\Helper::class

    ],

    //paginate
    'paginate' => 10,
    'paginate_worklist' => 5,

    // for Auth
    'subdomain'=> 'egat',
    'client_id' => '523ab4b0-507f-013c-3bad-0eb8180f05b238751',
    'client_secret' => '3b95c374cfe13da5a4c0ce599243417678a9460e72dbaeab2e03c6827fd90d0d',
    'redirect_uri' => 'http://127.0.0.1:8000/',
    'logout_redirect_uri' => 'http://127.0.0.1:8000/welcome',

    //Hr API
    'token_hr'=> 'Bearer '.'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiZTI5NjA0ZTU0Njg3MTVkYzU1NTAzNDE1MWZlY2Q1NzViNzI1OGFkNDhlNjcyMWVlMjllMDFhZWYxNzM2MTU2ODJkMmE2ZjA5OWQxYjMwNWMiLCJpYXQiOjE3MDEwNzI1NjcsIm5iZiI6MTcwMTA3MjU2NywiZXhwIjoxNzMyNjk0OTY3LCJzdWIiOiI0Iiwic2NvcGVzIjpbXX0.2ZPfE8TtqhvGoG8nNVlzUT9GOaZhqLrdbF95LkgFejVQtfnyBKLFO6zq6oRWWdzCaHees0IiTvRMmy9-eN0RLoE1v5xTN0UHj42JM-6MtDDha5n_IYOICcslnJMWGgT0ZviQo1tbch1er3KPBwngwzAAsIr_qqnDlyoLx-46Yiws082hMAJb5jlGsEJQwen4TBza3sTZSfJe4GnX754mxiU_3HhG_5k48Yk2u-RJB9lncu7l6BjVEL6vC0_o_H8BaDbJG9GVwo37moPYqcAYs9RunuNzPvLNnV_UT-JBh7QX__fhbe6w31TE2ecGkwYG_PSRisDRaAF7mmGpDtWhS_ujm8Z5HN4Vefv9yto6J4lg-MKBYMd-1Mv4F1lxor66Vv731Ud116KD0dk16MR--4VXj5V2-YhOG0C2AZqjJXiVx7lCT6_BW3amzF9f7AeJSZfHpTU8ltBI6zyNLH3wIVKAKWMSRYJOZHUalq2WNWMV1rjBbQuhDQmbWklTzx9tp3pLEiCkHbLN9mbrn-CvmnBmeeP7_A-qODYz5AGumt_G4Ojb5ycsQYW4sPUnQUqfdVmG1z-VB-MroA5w7mgSXHYK2IluLFd_k6pac-afYYS4-THL2atRgS-fN41j8bTk567Qd0BQhzRk_j28f29Ny3CGRwWcfI8Unm41dfg8KpE',
    'url_deputy'=> 'https://hrapi.egat.co.th/api/v1/organizations?include=manager&paginate=2000&filter[Level]=1',
    'url_assist'=> 'https://hrapi.egat.co.th/api/v1/organizations?include=manager&paginate=2000&filter[Level]=2',
    'url_division'=> 'https://hrapi.egat.co.th/api/v1/organizations?include=manager&paginate=2000&filter[Level]=3',
    'url_person'=> 'https://hrapi.egat.co.th/api/v1/persons?include=positions.organization.manager',

    // for Mail Sending
    'flag_enable_sendmail' => env('FLAG_ENABLE_SENDMAIL'),  // [Y] is has sending mail, [N] is no sending mail
    'flag_test_sendmail' => env('FLAG_TEST_SENDMAIL'),  // [Y] is testing and send mail to 'mail_receiver_test', [N] is not testing
    'mail_receiver_test' => env('MAIL_RECEIVER_TEST'), // email of receiver for test

    'mail_code_INNITIATOR' => 'M00001',
    'mail_code_REVIEWER' => 'M00002',
    'mail_code_APPROVER' => 'M00003',
    'mail_code_PSSRLEAD' => 'M00004',
    'mail_code_PSSRTEAM' => 'M00005',
    'mail_code_CLOSE' => 'M00006',
    'mail_code_REJECT' => 'M00007',

];
