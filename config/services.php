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

    'facebook' => [
        'client_id' => '1042921669853642',
        'client_secret' => '9151beb86e6551b24c15b423a4c38048',
        'redirect' => 'http://localhost:8000/callback/facebook',
    ],

    'google' => [
        'client_id' => '1023052752583-i5qg7bdeeei3v29vvfucm64sjqpbdiri.apps.googleusercontent.com',
        'client_secret' => 'hejGMo_xQE0ka1AWhqbW_sK-',
        'redirect' => 'http://localhost:8000/callback/google',
    ],

    'twitter' => [
        'client_id' => 'MoJzEQW2041mTfEUWL5vhCwIC',
        'client_secret' => 'MniWhkcDR7AuJjmtc1z8uNM5cJoWKAb1G7uMMdEqt1VbvpLnzZ',
        'redirect' => 'http://localhost:8000/callback/twitter',
    ],

    'linkedin' => [
        'client_id' => '77sdrs9y2gru5f',
        'client_secret' => 'rH9ZCD4aWLmVS7ML',
        'redirect' => 'http://localhost:8000/callback/linkedin',
    ],

];
