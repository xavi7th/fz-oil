<?php

use App\Modules\Admin\Models\Admin;
use App\Modules\AppUser\Models\AppUser;
use App\Modules\SalesRep\Models\SalesRep;
use App\Modules\WebAdmin\Models\WebAdmin;
use App\Modules\Accountant\Models\Accountant;
use App\Modules\SuperAdmin\Models\SuperAdmin;
use App\Modules\StockKeeper\Models\StockKeeper;
use App\Modules\DispatchAdmin\Models\DispatchAdmin;
use App\Modules\QualityControl\Models\QualityControl;

return [

  /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    |
    | This option controls the default authentication "guard" and password
    | reset options for your application. You may change these defaults
    | as required, but they're a perfect start for most applications.
    |
    */

  'defaults' => [
    'guard' => 'app_user',
    'passwords' => 'app_users',
  ],

  /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    |
    | Next, you may define every authentication guard for your application.
    | Of course, a great default configuration has been defined for you
    | here which uses session storage and the Eloquent user provider.
    |
    | All authentication drivers have a user provider. This defines how the
    | users are actually retrieved out of your database or other storage
    | mechanisms used by this application to persist your user's data.
    |
    | Supported: "session", "token"
    |
    */

  'guards' => [
    'app_user' => [
      'driver' => 'session',
      'provider' => 'app_users',
    ],
    'app_user_api' => [
      'driver' => 'jwt',
      'provider' => 'app_users',
    ],
    'accountant' => [
      'driver' => 'session',
      'provider' => 'accountants',
    ],
    'admin' => [
      'driver' => 'session',
      'provider' => 'admins',
    ],
    'dispatch_admin' => [
      'driver' => 'session',
      'provider' => 'dispatch_admins',
    ],
    'quality_control' => [
      'driver' => 'session',
      'provider' => 'quality_controls',
    ],
    'sales_rep' => [
      'driver' => 'session',
      'provider' => 'sales_reps',
    ],
    'stock_keeper' => [
      'driver' => 'session',
      'provider' => 'stock_keepers',
    ],
    'super_admin' => [
      'driver' => 'session',
      'provider' => 'super_admins',
    ],
    'web_admin' => [
      'driver' => 'session',
      'provider' => 'web_admins',
    ],
  ],

  /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    |
    | All authentication drivers have a user provider. This defines how the
    | users are actually retrieved out of your database or other storage
    | mechanisms used by this application to persist your user's data.
    |
    | If you have multiple user tables or models you may configure multiple
    | sources which represent each model / table. These sources may then
    | be assigned to any extra authentication guards you have defined.
    |
    | Supported: "database", "eloquent"
    |
    */

  'providers' => [
    'app_users' => [
      'driver' => 'eloquent',
      'model' => AppUser::class,
    ],
    'accountants' => [
      'driver' => 'eloquent',
      'model' => Accountant::class,
    ],
    'admins' => [
      'driver' => 'eloquent',
      'model' => Admin::class,
    ],
    'dispatch_admins' => [
      'driver' => 'eloquent',
      'model' => DispatchAdmin::class,
    ],
    'quality_controls' => [
      'driver' => 'eloquent',
      'model' => QualityControl::class,
    ],
    'sales_reps' => [
      'driver' => 'eloquent',
      'model' => SalesRep::class,
    ],
    'stock_keepers' => [
      'driver' => 'eloquent',
      'model' => StockKeeper::class,
    ],
    'super_admins' => [
      'driver' => 'eloquent',
      'model' => SuperAdmin::class,
    ],
    'web_admins' => [
      'driver' => 'eloquent',
      'model' => WebAdmin::class,
    ],
  ],

  /*
    |--------------------------------------------------------------------------
    | Resetting Passwords
    |--------------------------------------------------------------------------
    |
    | You may specify multiple password reset configurations if you have more
    | than one user table or model in the application and you want to have
    | separate password reset settings based on the specific user types.
    |
    | The expire time is the number of minutes that the reset token should be
    | considered valid. This security feature keeps tokens short-lived so
    | they have less time to be guessed. You may change this as needed.
    |
    */

  'passwords' => [
    'app_users' => [
      'provider' => 'app_users',
      'table' => 'password_resets',
      'expire' => 60,
      'throttle' => 60,
    ],
  ],

  /*
    |--------------------------------------------------------------------------
    | Password Confirmation Timeout
    |--------------------------------------------------------------------------
    |
    | Here you may define the amount of seconds before a password confirmation
    | times out and the user is prompted to re-enter their password via the
    | confirmation screen. By default, the timeout lasts for three hours.
    |
    */

  'password_timeout' => 10800,

];
