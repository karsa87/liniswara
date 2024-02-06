<?php

return [
    'expedition' => [
        'url' => env('EXPEDITION_API_URL'),
    ],
    'whatsapp' => [
        'url' => env('WHATSAPP_API_URL'),
    ],
    'send_notif_wa' => env('ENABLE_SENT_NOTIF_WA', false),
];
