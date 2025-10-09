<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    |
    | This option defines the default authentication "guard" and password
    | reset "broker" for your application. You may change these values
    | as required, but they're a perfect start for most applications.
    |
    */

    'defaults' => [
        'guard' => 'web', // Guard default untuk aplikasi (misalnya siswa)
        'passwords' => 'users',
    ],

    'guards' => [
        'web' => [ // Guard untuk siswa/user umum
            'driver' => 'session',
            'provider' => 'users',
        ],
        'admin' => [ // Guard KHUSUS untuk admin
            'driver' => 'session',
            'provider' => 'admins', // Kita akan definisikan provider 'admins'
        ],
        // Tambahkan guard untuk kepala sekolah
        'kepala_sekolah' => [
            'driver' => 'session',
            'provider' => 'kepala_sekolahs',
        ],
    ],

    'providers' => [
        'users' => [ // Provider untuk user umum/siswa (model User)
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],
        'admins' => [ // Provider untuk admin, bisa menggunakan model User yang sama tapi difilter
            'driver' => 'eloquent',
            'model' => App\Models\User::class, // Menggunakan model User yang sama
        ],
        // Tambahkan provider untuk kepala sekolah
        'kepala_sekolahs' => [
            'driver' => 'eloquent',
            'model' => App\Models\KepalaSekolah::class,
        ],
    ],

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
        'admins' => [ // Untuk reset password admin jika perlu
            'provider' => 'admins',
            'table' => 'password_reset_tokens', // Bisa pakai tabel yang sama
            'expire' => 60,
            'throttle' => 60,
        ],
        // Tambahkan password reset untuk kepala sekolah
        'kepala_sekolahs' => [
            'provider' => 'kepala_sekolahs',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Confirmation Timeout
    |--------------------------------------------------------------------------
    |
    | Here you may define the amount of seconds before a password confirmation
    | window expires and users are asked to re-enter their password via the
    | confirmation screen. By default, the timeout lasts for three hours.
    |
    */

    'password_timeout' => env('AUTH_PASSWORD_TIMEOUT', 10800),

];
