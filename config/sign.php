<?php

return [
    'salt'     => env('SIGN_SALT', ''),
    'lifetime' => env('SIGN_LIFETIME', 1000),
    'work'     => env('SIGN_CHECK', true),
];