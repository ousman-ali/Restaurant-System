<?php


return [

    'hasInstall' => env('HAS_INSTALL',0),

    'currency' => [
        'symbol' => env('RESTAURANT_CURRENCY_SYMBOL', '$'),
        'currency' => env('RESTAURANT_CURRENCY_CURRENCY', 'USD')
    ],

    'vat' => [
        'vat_number' => env('RESTAURANT_VAT_NUMBER', '0'),
        'vat_percentage' => env('RESTAURANT_VAT_PERCENTAGE', '0')
    ],

    'contact'=>[
        'phone' => env('RESTAURANT_PHONE',null),
        'address' => env('RESTAURANT_ADDRESS',null)
    ]

];