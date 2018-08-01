<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'ses' => [
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => 'us-east-1',
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],
	
	'facebook' => [
		'client_id' => "1991282584461211",
		'client_secret' => "4f9448de5e8f34f20f4693ca50961bbb",
		'redirect' => 'https://cmeshine.dev2.obtech.inet/handle-provider-callback/facebook',
	],

	'google' => [
		'client_id' => "889635299394-284iufuqfdpmdp7qc8jav634l3t63rak.apps.googleusercontent.com",
		'client_secret' => "jf4hCdvLIu9zNPAqn86qXCLR",
		'redirect' => 'http://cmeshine.stage02.obdemo.com/handle-provider-callback/google',
	],

	'twitter' => [
		'client_id' => "cq26GjLxXLo6oWrAuQ8gsTdpu",
		'client_secret' => "jSwPIVpOg4mH7hhjOCvhvNTQyUnKoSyVlNUoctKhEUPR9nLyNQ",
		'redirect' => 'https://cmeshine.dev2.obtech.inet/ccarbit/handle-provider-callback/twitter',
	],
];
