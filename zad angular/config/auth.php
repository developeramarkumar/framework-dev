<?php

return [
    
    'guards' => [
        'admin' => [
            'driver' => 'session',
            'provider' => 'admins',
        ],
        'parent' => [
            'driver' => 'session',
            'provider' => 'parents',
        ],
        'student' => [
            'driver' => 'session',
            'provider' => 'students',
        ],

        'api' => [
            'driver' => 'token',
            'provider' => 'users',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    |
    | All authentication drivers have a user provider. This defines how the
    | users are actually retrieved out of your database or other storage
    | mechanisms used by this application to persist your user's data.
    |
    | If you have multiple user tables or models you may configure multiple
    | sources which represent each model / table. These sources may then
    | be assigned to any extra authentication guards you have defined.
    |
    | Supported: "database", "eloquent"
    |
    */

    'providers' => [
        'admins' => [
            'driver' => 'eloquent',
            'model' => App\Admin::class,
        ],
        'parents' => [
            'driver' => 'eloquent',
            'model' => App\Parent::class,
        ],
        'students' => [
            'driver' => 'eloquent',
            'model' => App\Student::class,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Resetting Passwords
    |--------------------------------------------------------------------------
    |
    | You may specify multiple password reset configurations if you have more
    | than one user table or model in the application and you want to have
    | separate password reset settings based on the specific user types.
    |
    | The expire time is the number of minutes that the reset token should be
    | considered valid. This security feature keeps tokens short-lived so
    | they have less time to be guessed. You may change this as needed.
    |
    */

    'passwords' => [
        'admins' => [
            'provider' => 'admins',
            'table' => 'admins_password_resets',
            'expire' => 60,
        ],
        'parents' => [
            'provider' => 'parents',
            'table' => 'parents_password_resets',
            'expire' => 60,
        ],
        'students' => [
            'provider' => 'students',
            'table' => 'students_password_resets',
            'expire' => 60,
        ],
    ],

];
