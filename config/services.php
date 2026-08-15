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
    | محرك البادية الذكي (Badyah Smart Engine)
    |--------------------------------------------------------------------------
    |
    | توكن مخصص فقط لبوت/n8n البادية لاستدعاء Ingest API. هذا التوكن مستقل
    | تمامًا عن أي مشروع أو نظام آخر (لا علاقة له بمشاريع ALYASI إطلاقًا)،
    | ويجب توليده وحفظه فقط في .env الخاص بهذا المشروع.
    |
    */
    'badyah_bot' => [
        'token' => env('BADYAH_BOT_TOKEN'),
    ],

    /*
    | مزوّد الذكاء الاصطناعي المستخدم في تصنيف/وصف مواقع وخدمات البادية.
    | مفتاح مستقل تمامًا، غير مستخدم في أي مشروع آخر.
    */
    'groq' => [
        'api_key' => env('GROQ_API_KEY'),
        'model' => env('GROQ_MODEL', 'llama-3.3-70b-versatile'),
        'base_url' => env('GROQ_BASE_URL', 'https://api.groq.com/openai/v1'),
    ],

];
