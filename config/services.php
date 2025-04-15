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

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

       'keycloak' => [
       'client_id'     => env('KEYCLOAK_CLIENT_ID'),
       'client_secret' => env('KEYCLOAK_CLIENT_SECRET'),
       'redirect'      => env('KEYCLOAK_REDIRECT_URI'),
       'base_url'      => env('KEYCLOAK_BASE_URL'), // e.g. http://localhost:8080
       'realms'        => env('KEYCLOAK_REALM'),    // e.g. myrealm
       'client_secret' => env('KEYCLOAK_CLIENT_SECRET', null),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

];
