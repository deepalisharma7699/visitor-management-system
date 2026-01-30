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

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'whatsapp' => [
        'provider' => env('WHATSAPP_PROVIDER', 'twilio'), // 'twilio' or 'interakt'
    ],

    'twilio' => [
        'account_sid' => env('TWILIO_ACCOUNT_SID'),
        'auth_token' => env('TWILIO_AUTH_TOKEN'),
        'whatsapp_from' => env('TWILIO_WHATSAPP_FROM'),
    ],

    'interakt' => [
        'api_key' => env('INTERAKT_API_KEY'),
        'base_url' => env('INTERAKT_BASE_URL', 'https://api.interakt.ai/v1'),
    ],

    'google' => [
        'credentials_path' => env('GOOGLE_APPLICATION_CREDENTIALS'),
        'sheet_id' => env('GOOGLE_SHEET_ID'),
        'sheet_name' => env('GOOGLE_SHEET_NAME', 'Visitors'),
    ],

];
