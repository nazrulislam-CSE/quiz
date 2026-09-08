<?php

return [

    'EPSBaseURL' => env('EPS_BASE_URL'),

    'apiCredentials' => [

        'EPSUserName' =>
            env('EPS_USERNAME'),

        'EPSPassword' =>
            env('EPS_PASSWORD'),

        'EPSDeviceTypeID' =>
            env('EPS_DEVICE_TYPE_ID', 1),

        'EPSHashkey' =>
            env('EPS_HASH_KEY'),

        'EPSMerchentID' =>
            env('EPS_MERCHANT_ID'),

        'EPSStoreID' =>
            env('EPS_STORE_ID'),

    ],

    'apiUrl' => [

        'GetToken' =>
            '/v1/Auth/GetToken',

        'Initialize' =>
            '/v1/EPSEngine/InitializeEPS',

        'CheckPaymentStatus' =>
            '/v1/EPSEngine/CheckMerchantTransactionStatus',

    ]

];