<?php

return [
    'admin' => [
        'name' => env('ADMIN_NAME'),
        'email' => env('ADMIN_EMAIL'),
        'password' => env('ADMIN_PASSWORD'),
    ],
    'upload_disk' => env('FILESYSTEM_DISK', 'public'),
    'upload_max_size_kb' => (int) env('UPLOAD_MAX_SIZE_KB', 2048),
];
