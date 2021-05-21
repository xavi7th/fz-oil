<?php

return [

  /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

  'mailgun' => [
    'domain' => env('MAILGUN_DOMAIN'),
    'secret' => env('MAILGUN_SECRET'),
    'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
  ],

  'postmark' => [
    'token' => env('POSTMARK_TOKEN'),
  ],

  'ses' => [
    'key' => env('AWS_ACCESS_KEY_ID'),
    'secret' => env('AWS_SECRET_ACCESS_KEY'),
    'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
  ],
  'sms_solutions' => [
    'url' => 'https://smartsmssolutions.com/api/json.php',
    'sms_sender' => 'Capital X',
    'token' => env('SMS_SOLUTIONS_TOKEN', '')
  ],
  'twilio' => [
    'username' => env('TWILIO_USERNAME'), // optional when using auth token
    'password' => env('TWILIO_PASSWORD'), // optional when using auth token
    'auth_token' => env('TWILIO_AUTH_TOKEN'), // optional when using username and password
    'account_sid' => env('TWILIO_ACCOUNT_SID'),
    'from' => env('TWILIO_FROM'), // optional
  ],
  'termii_sms' => [
    'endpoint' => 'https://termii.com/api/sms/send', // optional when using auth token
    'username' => env('TERMII_EMAIL'), // optional when using auth token
    'password' => env('TERMII_PASSWORD'), // optional when using auth token
    'api_key' => env('TERMII_API_KEY'), // optional when using username and password
    'from' => env('TERMII_SENDER_ID'), // optional
  ],
  'bulk_sms' => [
    'endpoint' => 'https://www.bulksmsnigeria.com/api/v1/sms/create',
    'api_token' => env('BULK_SMS_API_TOKEN'),
    'from' => env('BULK_SMS_SENDER_ID'),
  ],
  'telegram' => [
    'office_number' => env('TELEGRAM_OFFICE_NUMBER_CHAT_ID', null),
    'error_alert_group' => env('TELEGRAM_ERROR_ALERT_CHAT_ID', null),
    'admin_notifications_group' => env('TELEGRAM_ADMIN_NOTIFICATIONS_CHAT_ID', null),
    'sales_alert_group' => env('TELEGRAM_SALES_ALERT_CHAT_ID', null),
    'accessories_stock_group' => env('TELEGRAM_ACCESSORY_STOCK_CHAT_ID', null),
    'bot_id' => env('TELEGRAM_ADMIN_BOT_ID', null),
  ],
  'dropbox' => [
    'token' => env('DROPBOX_ACCESS_TOKEN')
  ]

];
