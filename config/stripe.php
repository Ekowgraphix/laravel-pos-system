<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Paystack API Keys
    |--------------------------------------------------------------------------
    |
    | Your Paystack public key and secret key give you access to Paystack's
    | API. The "public" key is typically used client-side, while the
    | "secret" key should be kept confidential.
    |
    */

    'public_key' => env('PAYSTACK_PUBLIC_KEY'),

    'secret_key' => env('PAYSTACK_SECRET_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Paystack Base URL
    |--------------------------------------------------------------------------
    |
    | The base URL for Paystack API requests.
    |
    */

    'base_url' => env('PAYSTACK_BASE_URL', 'https://api.paystack.co'),

    /*
    |--------------------------------------------------------------------------
    | Merchant Email
    |--------------------------------------------------------------------------
    |
    | Your default merchant email for transactions.
    |
    */

    'merchant_email' => env('PAYSTACK_MERCHANT_EMAIL'),

    /*
    |--------------------------------------------------------------------------
    | Currency
    |--------------------------------------------------------------------------
    |
    | The default currency for Paystack transactions.
    | Supported: NGN, GHS, ZAR, USD
    |
    */

    'currency' => env('PAYSTACK_CURRENCY', 'GHS'),

];
