<?php

use App\Models\Core\Employee;
use App\Models\Core\Student;

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    |
    | This option defines the default authentication "guard" and password
    | reset "broker" for your application.
    |
    */

    'defaults' => [
        'guard' => env('AUTH_GUARD', 'employee'),
        'passwords' => env('AUTH_PASSWORD_BROKER', 'employees'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    |
    | Dua guard sesuai PRD §2.1 & §10:
    | - `employee`: Pegawai (dosen, petugas, pimpinan, admin unit, admin sistem)
    | - `student`: Taruna / peserta diklat
    |
    */

    'guards' => [
        'employee' => [
            'driver' => 'session',
            'provider' => 'employees',
        ],

        'student' => [
            'driver' => 'session',
            'provider' => 'students',
        ],

        'web' => [
            'driver' => 'session',
            'provider' => 'employees',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    */

    'providers' => [
        'employees' => [
            'driver' => 'eloquent',
            'model' => env('AUTH_EMPLOYEE_MODEL', Employee::class),
        ],

        'students' => [
            'driver' => 'eloquent',
            'model' => env('AUTH_STUDENT_MODEL', Student::class),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Resetting Passwords
    |--------------------------------------------------------------------------
    |
    | Tabel token reset kata sandi terpisah untuk employee dan student.
    |
    */

    'passwords' => [
        'employees' => [
            'provider' => 'employees',
            'table' => env('AUTH_EMPLOYEE_PASSWORD_RESET_TOKEN_TABLE', 'employee_password_reset_tokens'),
            'expire' => 60,
            'throttle' => 60,
        ],

        'students' => [
            'provider' => 'students',
            'table' => env('AUTH_STUDENT_PASSWORD_RESET_TOKEN_TABLE', 'student_password_reset_tokens'),
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Confirmation Timeout
    |--------------------------------------------------------------------------
    */

    'password_timeout' => env('AUTH_PASSWORD_TIMEOUT', 10800),

];
