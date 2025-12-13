<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Signed URL Encryption Key
    |--------------------------------------------------------------------------
    |
    | This key is used for encrypting the payload in signed URLs.
    | Must be exactly 32 bytes (32 characters) for AES-256-CBC.
    |
    */
    'enc_key' => env('SIGNED_URL_ENC_KEY', '12345678901234567890123456789012'),

    /*
    |--------------------------------------------------------------------------
    | Signed URL HMAC Key
    |--------------------------------------------------------------------------
    |
    | This key is used for signing the encrypted token to prevent tampering.
    | Should be a strong random string.
    |
    */
    'hmac_key' => env('SIGNED_URL_HMAC_KEY', 'super_secret_hmac_key_change_this'),

    /*
    |--------------------------------------------------------------------------
    | Signed URL Time To Live (TTL)
    |--------------------------------------------------------------------------
    |
    | The number of seconds the signed URL is valid for.
    | Default is 600 seconds (10 minutes).
    |
    */
    'ttl' => env('SIGNED_URL_TTL', 600),
];

