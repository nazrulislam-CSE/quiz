<?php

namespace App\EPS;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class EPSPayment
{
    protected array $config = [];

    protected string $baseUrl;

    protected string $userName;

    protected string $password;

    protected $deviceTypeId;

    protected string $hashkey;

    protected string $merchantId;

    protected string $storeId;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->config = config('epsPayment');

        $this->baseUrl = rtrim(
            $this->config['EPSBaseURL'] ?? '',
            '/'
        );

        $this->userName =
            $this->config['apiCredentials']['EPSUserName'] ?? '';

        $this->password =
            $this->config['apiCredentials']['EPSPassword'] ?? '';

        $this->deviceTypeId =
            $this->config['apiCredentials']['EPSDeviceTypeID'] ?? 1;

        $this->hashkey =
            $this->config['apiCredentials']['EPSHashkey'] ?? '';

        $this->merchantId =
            $this->config['apiCredentials']['EPSMerchentID'] ?? '';

        $this->storeId =
            $this->config['apiCredentials']['EPSStoreID'] ?? '';
    }

    /**
     * Generate EPS HMAC SHA512 Hash
     *
     * HMAC SHA512
     * Payload = username / merchantTransactionId
     * Key = EPS hash key
     * Output = Base64
     */
    protected function generateHash(
        string $payload,
        string $hashkey
    ): string {

        return base64_encode(
            hash_hmac(
                'sha512',
                $payload,
                $hashkey,
                true
            )
        );
    }

    /**
     * Normalize API Response
     *
     * Always return Array
     */
    protected function normalizeResponse($response): array
    {
        try {

            $data = $response->json();

            /*
            |--------------------------------------------------------------------------
            | Already Array
            |--------------------------------------------------------------------------
            */

            if (is_array($data)) {
                return $data;
            }

            /*
            |--------------------------------------------------------------------------
            | JSON returned as String
            |--------------------------------------------------------------------------
            */

            if (is_string($data)) {

                $decoded = json_decode(
                    $data,
                    true
                );

                if (
                    json_last_error() === JSON_ERROR_NONE
                    && is_array($decoded)
                ) {
                    return $decoded;
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Try Raw Body JSON Decode
            |--------------------------------------------------------------------------
            */

            $rawBody = $response->body();

            $decoded = json_decode(
                $rawBody,
                true
            );

            if (
                json_last_error() === JSON_ERROR_NONE
                && is_array($decoded)
            ) {
                return $decoded;
            }

            /*
            |--------------------------------------------------------------------------
            | Invalid Response
            |--------------------------------------------------------------------------
            */

            return [
                'ErrorMessage' => 'Invalid EPS API Response',
                'raw_response' => $rawBody,
            ];

        } catch (\Throwable $e) {

            return [
                'ErrorMessage' => $e->getMessage(),
                'raw_response' => $response->body(),
            ];
        }
    }

    /**
     * Get EPS Token
     */
    protected function getToken(): array
    {
        try {

            $reqBody = [

                'userName' => $this->userName,

                'password' => $this->password,

            ];

            /*
            |--------------------------------------------------------------------------
            | Generate Hash Using Username
            |--------------------------------------------------------------------------
            */

            $xHash = $this->generateHash(
                $this->userName,
                $this->hashkey
            );

            /*
            |--------------------------------------------------------------------------
            | Token URL
            |--------------------------------------------------------------------------
            */

            $url =
                $this->baseUrl.
                $this->config['apiUrl']['GetToken'];

            /*
            |--------------------------------------------------------------------------
            | Call EPS Token API
            |--------------------------------------------------------------------------
            */

            $response = Http::timeout(30)
                ->acceptJson()
                ->withHeaders([

                    'x-hash' => $xHash,

                    'Content-Type' => 'application/json',

                ])
                ->post(
                    $url,
                    $reqBody
                );

            /*
            |--------------------------------------------------------------------------
            | Normalize Response
            |--------------------------------------------------------------------------
            */

            $data = $this->normalizeResponse(
                $response
            );

            /*
            |--------------------------------------------------------------------------
            | Log
            |--------------------------------------------------------------------------
            */

            Log::info(
                'EPS Get Token Response',
                [

                    'url' => $url,

                    'status' => $response->status(),

                    'response' => $data,

                    'raw_body' => $response->body(),

                ]
            );

            /*
            |--------------------------------------------------------------------------
            | Success
            |--------------------------------------------------------------------------
            */

            if (
                $response->successful()
                &&
                isset($data['token'])
                &&
                ! empty($data['token'])
            ) {

                return [

                    'success' => true,

                    'data' => $data,

                ];
            }

            /*
            |--------------------------------------------------------------------------
            | Failed
            |--------------------------------------------------------------------------
            */

            return [

                'success' => false,

                'data' => $data,

                'status' => $response->status(),

            ];

        } catch (\Throwable $e) {

            Log::error(
                'EPS Get Token Error',
                [

                    'message' => $e->getMessage(),

                ]
            );

            return [

                'success' => false,

                'data' => [

                    'ErrorMessage' => $e->getMessage(),

                ],

            ];
        }
    }

    /**
     * Create EPS Payment
     */
    public function createPayment(
        array $payload
    ): array {

        /*
        |--------------------------------------------------------------------------
        | STEP 1: Get Token
        |--------------------------------------------------------------------------
        */

        $tokenResponse = $this->getToken();

        if (! $tokenResponse['success']) {

            return [

                'isSuccess' => false,

                'ErrorMessage' => $tokenResponse['data']['ErrorMessage']
                    ??
                    $tokenResponse['data']['errorMessage']
                    ??
                    'EPS Token Access Denied',

                'debug' => $tokenResponse,

            ];
        }

        $token =
            $tokenResponse['data']['token'];

        /*
        |--------------------------------------------------------------------------
        | STEP 2: Unique Merchant Transaction ID
        |--------------------------------------------------------------------------
        */

        $merchantTransactionId =
            'EPS'.
            now()->format('YmdHis').
            random_int(100000, 999999);

        /*
        |--------------------------------------------------------------------------
        | STEP 3: EPS Required Request Body
        |--------------------------------------------------------------------------
        */

        $reqBody = [

            'deviceTypeId' => (int) $this->deviceTypeId,

            'merchantId' => $this->merchantId,

            'storeId' => $this->storeId,

            /*
            | 1 = Web
            */

            'transactionTypeId' => 1,

            'financialEntityId' => 0,

            'transitionStatusId' => 0,

            'version' => '1',

            'transactionDate' => now()->toIso8601String(),

            'merchantTransactionId' => $merchantTransactionId,

            'valueD' => '',

        ];

        /*
        |--------------------------------------------------------------------------
        | Merge User Payload
        |--------------------------------------------------------------------------
        */

        $reqBody = array_merge(
            $reqBody,
            $payload
        );

        /*
        |--------------------------------------------------------------------------
        | Important:
        | Prevent Payload From Overwriting Merchant Transaction ID
        |--------------------------------------------------------------------------
        */

        $reqBody['merchantTransactionId'] =
            $merchantTransactionId;

        /*
        |--------------------------------------------------------------------------
        | STEP 4: Generate Hash
        |--------------------------------------------------------------------------
        */

        $xHash = $this->generateHash(
            $merchantTransactionId,
            $this->hashkey
        );

        try {

            /*
            |--------------------------------------------------------------------------
            | Initialize URL
            |--------------------------------------------------------------------------
            */

            $url =
                $this->baseUrl.
                $this->config['apiUrl']['Initialize'];

            /*
            |--------------------------------------------------------------------------
            | STEP 5: Call EPS Initialize API
            |--------------------------------------------------------------------------
            */

            $response = Http::timeout(30)
                ->acceptJson()
                ->withHeaders([

                    'x-hash' => $xHash,

                    'Authorization' => 'Bearer '.$token,

                    'Content-Type' => 'application/json',

                ])
                ->post(
                    $url,
                    $reqBody
                );

            /*
            |--------------------------------------------------------------------------
            | Normalize Response
            |--------------------------------------------------------------------------
            */

            $data = $this->normalizeResponse(
                $response
            );

            /*
            |--------------------------------------------------------------------------
            | Safe Add Merchant Transaction ID
            |--------------------------------------------------------------------------
            */

            $data['MerchantTransactionId'] =
                $merchantTransactionId;

            /*
            |--------------------------------------------------------------------------
            | Add Status
            |--------------------------------------------------------------------------
            */

            $data['isSuccess'] =
                $response->successful()
                &&
                (
                    ! empty($data['RedirectURL'])
                    ||
                    ! empty($data['RedirectUrl'])
                );

            /*
            |--------------------------------------------------------------------------
            | Log
            |--------------------------------------------------------------------------
            */

            Log::info(
                'EPS Initialize Payment Response',
                [

                    'url' => $url,

                    'status' => $response->status(),

                    /*
                    | Password / Token log করবেন না
                    */

                    'request' => $reqBody,

                    'response' => $data,

                    'raw_body' => $response->body(),

                ]
            );

            return $data;

        } catch (\Throwable $e) {

            Log::error(
                'EPS Initialize Error',
                [

                    'message' => $e->getMessage(),

                ]
            );

            return [

                'isSuccess' => false,

                'ErrorMessage' => $e->getMessage(),

                'MerchantTransactionId' => $merchantTransactionId,

            ];
        }
    }

    /**
     * Check Payment Status
     *
     * Verify using Merchant Transaction ID
     */
    public function checkPaymentStatus(
        string $merchantTransactionId
    ): array {

        /*
        |--------------------------------------------------------------------------
        | STEP 1: Get Token
        |--------------------------------------------------------------------------
        */

        $tokenResponse =
            $this->getToken();

        if (! $tokenResponse['success']) {

            return [

                'isSuccess' => false,

                'ErrorMessage' => $tokenResponse['data']['ErrorMessage']
                    ??
                    $tokenResponse['data']['errorMessage']
                    ??
                    'Unable to get EPS token',

            ];
        }

        $token =
            $tokenResponse['data']['token'];

        /*
        |--------------------------------------------------------------------------
        | STEP 2: Generate Hash
        |--------------------------------------------------------------------------
        */

        $xHash = $this->generateHash(
            $merchantTransactionId,
            $this->hashkey
        );

        try {

            /*
            |--------------------------------------------------------------------------
            | API URL
            |--------------------------------------------------------------------------
            */

            $url =
                $this->baseUrl.
                $this->config['apiUrl']['CheckPaymentStatus'];

            /*
            |--------------------------------------------------------------------------
            | Call Verify API
            |--------------------------------------------------------------------------
            */

            $response = Http::timeout(30)
                ->acceptJson()
                ->withHeaders([

                    'x-hash' => $xHash,

                    'Authorization' => 'Bearer '.$token,

                ])
                ->get(
                    $url,
                    [

                        'merchantTransactionId' => $merchantTransactionId,

                    ]
                );

            /*
            |--------------------------------------------------------------------------
            | Normalize Response
            |--------------------------------------------------------------------------
            */

            $data = $this->normalizeResponse(
                $response
            );

            $data['isSuccess'] =
                $response->successful();

            /*
            |--------------------------------------------------------------------------
            | Log
            |--------------------------------------------------------------------------
            */

            Log::info(
                'EPS Payment Verify Response',
                [

                    'url' => $url,

                    'status' => $response->status(),

                    'response' => $data,

                    'raw_body' => $response->body(),

                ]
            );

            return $data;

        } catch (\Throwable $e) {

            Log::error(
                'EPS Payment Verify Error',
                [

                    'message' => $e->getMessage(),

                ]
            );

            return [

                'isSuccess' => false,

                'ErrorMessage' => $e->getMessage(),

            ];
        }
    }
}
