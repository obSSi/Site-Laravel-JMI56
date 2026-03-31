<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Admin login protection (fail2ban-like)
    |--------------------------------------------------------------------------
    |
    | max_attempts: number of failed attempts allowed before lockout.
    | lockout_seconds: temporary ban duration once threshold is reached.
    |
    */
    'admin_login' => [
        'max_attempts' => env('SECURITY_ADMIN_LOGIN_MAX_ATTEMPTS', 5),
        'lockout_seconds' => env('SECURITY_ADMIN_LOGIN_LOCKOUT_SECONDS', 300),
    ],
];

