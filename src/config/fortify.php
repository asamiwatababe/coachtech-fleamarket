<?php

use Laravel\Fortify\Features;

return [

    // 'defaults' => [
    //     'guard' => 'web',
    //     'passwords' => 'users',
    // ],

    // 'guards' => [
    //     'web' => [
    //         'driver' => 'session',
    //         'provider' => 'users',
    //     ],
    // ],

    // 'providers' => [
    //     'users' => [
    //         'driver' => 'eloquent',
    //         'model' => App\Models\User::class,
    //     ],
    // ],

    'middleware' => ['web'],
    'auth_middleware' => 'auth',

    'passwords' => 'users',

    'username' => 'email',
    'email' => 'email',

    'views' => true,

    // ✅ ログイン後は商品一覧ページへ
    'home' => '/',

    'prefix' => '',
    'domain' => null,
    'lowercase_usernames' => false,

    // ✅ ログイン・会員登録後のリダイレクト先を明示
    'redirects' => [
        'login' => '/',
        'register' => '/mypage/profile',
    ],

    'limiters' => [
        'login' => null,
    ],

    'paths' => [
        'login' => null,
        'logout' => null,
        'register' => null,
        'password' => [
            'request' => null,
            'reset' => null,
            'email' => null,
            'update' => null,
            'confirm' => null,
            'confirmation' => null,
        ],
        'verification' => [
            'notice' => null,
            'verify' => null,
            'send' => null,
        ],
        'user-profile-information' => [
            'update' => null,
        ],
        'user-password' => [
            'update' => null,
        ],
        'two-factor' => [
            'login' => null,
            'enable' => null,
            'confirm' => null,
            'disable' => null,
            'qr-code' => null,
            'secret-key' => null,
            'recovery-codes' => null,
        ],
    ],

    'features' => [
        Features::registration(),
        Features::resetPasswords(),
        Features::updateProfileInformation(),
        Features::updatePasswords(),
        Features::twoFactorAuthentication(),
    ],
];
