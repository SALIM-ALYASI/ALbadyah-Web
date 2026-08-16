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

    'resend' => [
        'key' => env('RESEND_KEY'),
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

    'visits' => [
        'duplicate_window_minutes' => (int) env('VISIT_DUPLICATE_WINDOW_MINUTES', 60 * 24),
    ],

    /*
    |--------------------------------------------------------------------------
    | بوت البادية المستقل (تلجرام + OSM) — API خاص فقط
    |--------------------------------------------------------------------------
    |
    | توكن مستقل تمامًا لا علاقة له بـ Sanctum ولا بلوحة الإدمن. يُستخدم فقط
    | من طرف بوت تلجرام المستقل (يعمل خارج هذا المشروع بالكامل) لاستدعاء
    | POST /api/badyah-bot/items.
    |
    */
    'badyah_bot_api' => [
        'token' => env('BADYAH_BOT_API_TOKEN'),
    ],

];
