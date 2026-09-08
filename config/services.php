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

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'banku' => [
        'base_url' => env('BANKU_BASE_URL', 'https://app.banku.co.in'),
        'client_id' => env('BANKU_CLIENT_ID'),
        'client_secret' => env('BANKU_CLIENT_SECRET'),
        // Verifies signed BankU webhook events. Never send it as an Idempotency-Key.
        'encryption_key' => env('BANKU_ENCRYPTION_KEY'),
        'timeout' => env('BANKU_TIMEOUT', 15),
        'connect_timeout' => env('BANKU_CONNECT_TIMEOUT', 5),
        'retry_times' => env('BANKU_RETRY_TIMES', 1),
        'retry_delay_ms' => env('BANKU_RETRY_DELAY_MS', 200),
    ],

    'whatsapp' => [
        // Pinbot / WhatsApp Business cloud API. Disabled unless fully configured.
        'enabled' => env('WHATSAPP_ENABLED', false),
        'base_url' => env('WHATSAPP_API_URL', 'https://partnersv1.pinbot.ai/v3'),
        'phone_number_id' => env('WHATSAPP_PHONE_NUMBER_ID'),
        'api_key' => env('WHATSAPP_API_KEY'),
        // Prepended to bare 10-digit local numbers before sending.
        'default_country_code' => env('WHATSAPP_DEFAULT_COUNTRY_CODE', '91'),
        'timeout' => env('WHATSAPP_TIMEOUT', 15),
    ],

];
