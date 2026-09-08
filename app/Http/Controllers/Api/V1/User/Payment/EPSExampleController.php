<?php

namespace App\Http\Controllers\Api\V1\User\Payment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\EPS\EPSPayment;
use Throwable;

class EPSExampleController extends Controller
{
    /**
     * EPS Payment API Info
     *
     * GET /api/eps-payment
     */
    public function index()
    {
        return response()->json([
            'success' => true,
            'message' => 'EPS Payment API is working.',
            'data' => [
                'initialize_url' => url('/api/eps-payment/initialize'),
                'method' => 'POST',
                'currency' => 'BDT',
            ]
        ]);
    }


    /**
     * Initialize EPS Payment
     *
     * POST /api/eps-payment/initialize
     */
    public function initializePayment(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
            'amount' => [
                'required',
                'numeric',
                'min:1'
            ],

            'customer_name' => [
                'required',
                'string',
                'max:100'
            ],

            'customer_email' => [
                'required',
                'email',
                'max:150'
            ],

            'customer_phone' => [
                'required',
                'string',
                'max:20'
            ],

            'customer_address' => [
                'required',
                'string',
                'max:255'
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Product List
        |--------------------------------------------------------------------------
        */

        $productArr = [

            [
                "ProductName" => "T-Shirt",
                "NoOfItem" => "2",
                "ProductProfile" => "101",
                "ProductCategory" => "Clothing",
                "ProductPrice" => "500"
            ],

            [
                "ProductName" => "Shoes",
                "NoOfItem" => "1",
                "ProductProfile" => "205",
                "ProductCategory" => "Footwear",
                "ProductPrice" => "1200"
            ]

        ];


        /*
        |--------------------------------------------------------------------------
        | Unique Customer Order ID
        |--------------------------------------------------------------------------
        */

        $customerOrderId =
            'ORDER_' .
            now()->format('YmdHis') .
            '_' .
            random_int(1000, 9999);


        /*
        |--------------------------------------------------------------------------
        | EPS Payload
        |--------------------------------------------------------------------------
        */

        $payload = [

            "CustomerOrderId" =>
                $customerOrderId,

            "totalAmount" =>
                (float) $validated['amount'],

            "ipAddress" =>
                $request->ip(),


            /*
            |--------------------------------------------------------------------------
            | Callback URLs
            |--------------------------------------------------------------------------
            */

            "successUrl" =>
                route('payment.success'),

            "failUrl" =>
                route('payment.fail'),

            "cancelUrl" =>
                route('payment.cancel'),


            /*
            |--------------------------------------------------------------------------
            | Customer Information
            |--------------------------------------------------------------------------
            */

            "customerName" =>
                $validated['customer_name'],

            "customerEmail" =>
                $validated['customer_email'],

            "customerAddress" =>
                $validated['customer_address'],

            "customerAddress2" =>
                "",

            "customerCity" =>
                $request->customer_city ?? "Dhaka",

            "customerState" =>
                $request->customer_state ?? "Dhaka",

            "customerPostcode" =>
                $request->customer_postcode ?? "1000",

            "customerCountry" =>
                "BD",

            "customerPhone" =>
                $validated['customer_phone'],


            /*
            |--------------------------------------------------------------------------
            | Shipment Information
            |--------------------------------------------------------------------------
            */

            "shipmentName" =>
                $validated['customer_name'],

            "shipmentAddress" =>
                $validated['customer_address'],

            "shipmentAddress2" =>
                "",

            "shipmentCity" =>
                $request->customer_city ?? "Dhaka",

            "shipmentState" =>
                $request->customer_state ?? "Dhaka",

            "shipmentPostcode" =>
                $request->customer_postcode ?? "1000",

            "shipmentCountry" =>
                "BD",


            /*
            |--------------------------------------------------------------------------
            | Custom Values
            |--------------------------------------------------------------------------
            */

            "valueA" =>
                $request->customer_id ?? "",

            "valueB" =>
                "",

            "valueC" =>
                $customerOrderId,

            "valueD" =>
                "",


            /*
            |--------------------------------------------------------------------------
            | Shipping & Product
            |--------------------------------------------------------------------------
            */

            "shippingMethod" =>
                "Home Delivery",

            "noOfItem" =>
                "3",

            "productName" =>
                "T-Shirt, Shoes",

            "productProfile" =>
                "General",

            "productCategory" =>
                "Clothing",


            /*
            |--------------------------------------------------------------------------
            | Product List
            |--------------------------------------------------------------------------
            */

            "ProductList" =>
                $productArr
        ];


        /*
        |--------------------------------------------------------------------------
        | Create EPS Payment
        |--------------------------------------------------------------------------
        */

        try {

            $epsPayment =
                new EPSPayment();

            $data =
                $epsPayment->createPayment($payload);


            /*
            |--------------------------------------------------------------------------
            | Log EPS Response
            |--------------------------------------------------------------------------
            */

            Log::info('EPS API Payment Initialize', [

                'customer_order_id' =>
                    $customerOrderId,

                'amount' =>
                    $validated['amount'],

                'response' =>
                    $data

            ]);


            /*
            |--------------------------------------------------------------------------
            | EPS Payment Success
            |--------------------------------------------------------------------------
            */

            if (
                isset($data['RedirectURL']) &&
                !empty($data['RedirectURL'])
            ) {

                return response()->json([

                    'success' => true,

                    'message' =>
                        'EPS payment initialized successfully.',

                    'data' => [

                        'customer_order_id' =>
                            $customerOrderId,

                        'merchant_transaction_id' =>
                            $data['MerchantTransactionId']
                            ?? null,

                        'transaction_id' =>
                            $data['TransactionId']
                            ?? null,

                        'amount' =>
                            $validated['amount'],

                        'payment_url' =>
                            $data['RedirectURL'],

                    ]

                ], 200);
            }


            /*
            |--------------------------------------------------------------------------
            | EPS Error
            |--------------------------------------------------------------------------
            */

            return response()->json([

                'success' => false,

                'message' =>
                    'EPS Payment Initialization Failed',

                'error' =>
                    $data['ErrorMessage']
                    ?? $data['errorMessage']
                    ?? 'Unknown EPS Error',

                'data' =>
                    $data

            ], 422);


        } catch (Throwable $e) {

            Log::error(
                'EPS Payment API Error',
                [
                    'message' =>
                        $e->getMessage(),

                    'trace' =>
                        $e->getTraceAsString()
                ]
            );


            return response()->json([

                'success' => false,

                'message' =>
                    'Payment Processing Error',

                'error' =>
                    $e->getMessage()

            ], 500);
        }
    }


    /**
     * EPS Success Callback
     *
     * GET /api/eps-payment/success
     */
    public function success(Request $request)
    {
        $merchantTransactionId =
            $request->query('MerchantTransactionId')
            ??
            $request->query('merchantTransactionId');


        $epsTransactionId =
            $request->query('EPSTransactionId')
            ??
            $request->query('EpsTransactionId')
            ??
            $request->query('epsTransactionId');


        $status =
            $request->query('Status')
            ??
            $request->query('status');


        /*
        |--------------------------------------------------------------------------
        | Log EPS Callback
        |--------------------------------------------------------------------------
        */

        Log::info(
            'EPS Payment Success Callback',
            [
                'merchantTransactionId' =>
                    $merchantTransactionId,

                'epsTransactionId' =>
                    $epsTransactionId,

                'status' =>
                    $status,

                'all_data' =>
                    $request->all()
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | Verify Payment From EPS
        |--------------------------------------------------------------------------
        */

        $verifyData = [];

        try {

            if ($merchantTransactionId) {

                $epsPayment =
                    new EPSPayment();

                $verifyData =
                    $epsPayment->checkPaymentStatus(
                        $merchantTransactionId
                    );
            }

        } catch (Throwable $e) {

            Log::error(
                'EPS Payment Verify Error',
                [
                    'message' =>
                        $e->getMessage(),

                    'merchantTransactionId' =>
                        $merchantTransactionId
                ]
            );

            return response()->json([

                'success' => false,

                'message' =>
                    'Unable to verify payment.',

                'error' =>
                    $e->getMessage()

            ], 500);
        }


        /*
        |--------------------------------------------------------------------------
        | IMPORTANT: Verify Actual EPS Status
        |--------------------------------------------------------------------------
        */

        $paymentStatus =
            $verifyData['Status']
            ?? $status
            ?? 'Unknown';


        /*
        |--------------------------------------------------------------------------
        | Payment Successful
        |--------------------------------------------------------------------------
        */

        if (
            strtolower($paymentStatus)
            ===
            'success'
        ) {

            /*
            |--------------------------------------------------------------------------
            | TODO:
            | Database Transaction Update
            |--------------------------------------------------------------------------
            |
            | Example:
            |
            | Transaction::updateOrCreate(...)
            |
            */

            return response()->json([

                'success' => true,

                'message' =>
                    'Payment verified successfully.',

                'data' => [

                    'merchant_transaction_id' =>
                        $merchantTransactionId,

                    'eps_transaction_id' =>
                        $epsTransactionId,

                    'status' =>
                        $paymentStatus,

                    'amount' =>
                        $verifyData['TotalAmount']
                        ?? 0,

                    'payment_method' =>
                        $verifyData['FinancialEntity']
                        ?? null,

                    'transaction_date' =>
                        $verifyData['TransactionDate']
                        ?? null,

                ]

            ], 200);
        }


        /*
        |--------------------------------------------------------------------------
        | Payment Verification Failed
        |--------------------------------------------------------------------------
        */

        return response()->json([

            'success' => false,

            'message' =>
                'Payment verification failed.',

            'data' => [

                'merchant_transaction_id' =>
                    $merchantTransactionId,

                'eps_transaction_id' =>
                    $epsTransactionId,

                'status' =>
                    $paymentStatus,

                'eps_response' =>
                    $verifyData

            ]

        ], 422);
    }


    /**
     * EPS Failed Callback
     *
     * GET /api/eps-payment/fail
     */
    public function fail(Request $request)
    {
        $merchantTransactionId =
            $request->query('MerchantTransactionId')
            ??
            $request->query('merchantTransactionId');


        $epsTransactionId =
            $request->query('EPSTransactionId')
            ??
            $request->query('EpsTransactionId')
            ??
            '';


        $status =
            $request->query('Status')
            ??
            $request->query('status')
            ??
            'Failed';


        Log::warning(
            'EPS Payment Failed Callback',
            [
                'merchantTransactionId' =>
                    $merchantTransactionId,

                'epsTransactionId' =>
                    $epsTransactionId,

                'status' =>
                    $status,

                'all_data' =>
                    $request->all()
            ]
        );


        return response()->json([

            'success' => false,

            'message' =>
                'EPS payment failed.',

            'data' => [

                'merchant_transaction_id' =>
                    $merchantTransactionId,

                'eps_transaction_id' =>
                    $epsTransactionId,

                'status' =>
                    $status

            ]

        ], 422);
    }


    /**
     * EPS Cancel Callback
     *
     * GET /api/eps-payment/cancel
     */
    public function cancel(Request $request)
    {
        $merchantTransactionId =
            $request->query('MerchantTransactionId')
            ??
            $request->query('merchantTransactionId');


        $epsTransactionId =
            $request->query('EPSTransactionId')
            ??
            $request->query('EpsTransactionId')
            ??
            '';


        $status =
            $request->query('Status')
            ??
            $request->query('status')
            ??
            'Cancelled';


        Log::info(
            'EPS Payment Cancelled Callback',
            [
                'merchantTransactionId' =>
                    $merchantTransactionId,

                'epsTransactionId' =>
                    $epsTransactionId,

                'status' =>
                    $status,

                'all_data' =>
                    $request->all()
            ]
        );


        return response()->json([

            'success' => false,

            'message' =>
                'EPS payment cancelled.',

            'data' => [

                'merchant_transaction_id' =>
                    $merchantTransactionId,

                'eps_transaction_id' =>
                    $epsTransactionId,

                'status' =>
                    $status

            ]

        ], 200);
    }
}