<?php

return [
    "paystack" => [
        'timeout' => env('PAYSTACK_API_TIMEOUT', 2),
        'secretKey' => env('PAYSTACK_API_SECRET_KEY')
    ]
];