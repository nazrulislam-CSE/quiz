<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\EPS\EPSPayment;
use Illuminate\Support\Facades\Log;

class EPSExampleController extends Controller
{
    /**
     * Payment Form Page
     */
    public function index()
    {
        return view('payment.form');
    }


    /**
     * Initialize EPS Payment
     */
    public function initializePayment(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $request->validate([
            'amount' => 'required|numeric|min:1'
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
        | Customer Order ID
        |--------------------------------------------------------------------------
        */

        $customerOrderId =
            'ORDER_' .
            now()->format('YmdHis') .
            '_' .
            random_int(1000, 9999);


        /*
        |--------------------------------------------------------------------------
        | Payload
        |--------------------------------------------------------------------------
        */

        $payload = [

            /*
            |--------------------------------------------------------------------------
            | Order Information
            |--------------------------------------------------------------------------
            */

            "CustomerOrderId" => $customerOrderId,

            "totalAmount" => (float) $request->amount,

            "ipAddress" => $request->ip(),


            /*
            |--------------------------------------------------------------------------
            | Callback URLs
            |--------------------------------------------------------------------------
            */

            "successUrl" => route('payment.success'),

            "failUrl" => route('payment.fail'),

            "cancelUrl" => route('payment.cancel'),


            /*
            |--------------------------------------------------------------------------
            | Customer Information
            |--------------------------------------------------------------------------
            */

            "customerName" => "Jone De",

            "customerEmail" => "JoneDe@gmail.com",

            "customerAddress" => "Looking up an address",

            "customerAddress2" => "",

            "customerCity" => "Dhaka",

            "customerState" => "Dhaka",

            "customerPostcode" => "1000",

            "customerCountry" => "BD",

            "customerPhone" => "01700000000",


            /*
            |--------------------------------------------------------------------------
            | Shipment Information
            |--------------------------------------------------------------------------
            */

            "shipmentName" => "Jone De",

            "shipmentAddress" => "Looking up an address",

            "shipmentAddress2" => "",

            "shipmentCity" => "Dhaka",

            "shipmentState" => "Dhaka",

            "shipmentPostcode" => "1000",

            "shipmentCountry" => "BD",


            /*
            |--------------------------------------------------------------------------
            | Custom Values
            |--------------------------------------------------------------------------
            */

            "valueA" => "customer_id",

            "valueB" => "local_transaction_id",

            "valueC" => "order_id_" . $customerOrderId,

            "valueD" => "",


            /*
            |--------------------------------------------------------------------------
            | Shipping & Product Information
            |--------------------------------------------------------------------------
            */

            "shippingMethod" => "Home Delivery",

            "noOfItem" => "3",

            "productName" => "T-Shirt, Shoes",

            "productProfile" => "General",

            "productCategory" => "Clothing",


            /*
            |--------------------------------------------------------------------------
            | Product List
            |--------------------------------------------------------------------------
            */

            "ProductList" => $productArr,

        ];


        /*
        |--------------------------------------------------------------------------
        | EPS Payment Initialize
        |--------------------------------------------------------------------------
        */

        try {

            $epsPayment = new EPSPayment();

            $data = $epsPayment->CreatePayment($payload);
       


            /*
            |--------------------------------------------------------------------------
            | Log Response
            |--------------------------------------------------------------------------
            */

            Log::info('EPS Payment Response', [
                'response' => $data
            ]);


            /*
            |--------------------------------------------------------------------------
            | Redirect to EPS Payment Gateway
            |--------------------------------------------------------------------------
            */

            if (
                isset($data['RedirectURL']) &&
                !empty($data['RedirectURL'])
            ) {

                return redirect()->away(
                    $data['RedirectURL']
                );
            }


            /*
            |--------------------------------------------------------------------------
            | EPS Error
            |--------------------------------------------------------------------------
            */

            return response()->json([
                'success' => false,
                'message' => 'EPS Payment Initialization Failed',
                'error' => $data['ErrorMessage']
                    ?? $data['errorMessage']
                    ?? 'Unknown EPS Error',
                'eps_response' => $data
            ], 500);

        } catch (\Exception $e) {

            Log::error('EPS Payment Controller Error', [
                'message' => $e->getMessage()
            ]);


            return response()->json([
                'success' => false,
                'message' => 'Payment Processing Error',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Payment Success Callback
     */
    public function success(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Get Query Parameters
        |--------------------------------------------------------------------------
        */

        $merchantTransactionId =
            $request->query('MerchantTransactionId')
            ?? $request->query('merchantTransactionId');

        $epsTransactionId =
            $request->query('EPSTransactionId')
            ?? $request->query('EpsTransactionId')
            ?? $request->query('epsTransactionId');

        $status =
            $request->query('Status')
            ?? $request->query('status');


        /*
        |--------------------------------------------------------------------------
        | Log Callback Data
        |--------------------------------------------------------------------------
        */

        Log::info('EPS Payment Success Callback', [
            'merchantTransactionId' => $merchantTransactionId,
            'epsTransactionId' => $epsTransactionId,
            'status' => $status,
            'all_data' => $request->all()
        ]);


        /*
        |--------------------------------------------------------------------------
        | Verify Payment from EPS
        |--------------------------------------------------------------------------
        */

        $verifyData = [];

        try {

            if ($merchantTransactionId) {

                $epsPayment = new EPSPayment();

                $verifyData =
                    $epsPayment->CheckPaymentStatus(
                        $merchantTransactionId
                    );

            }

        } catch (\Exception $e) {

            Log::error('EPS Verify Error', [
                'message' => $e->getMessage()
            ]);

        }


        /*
        |--------------------------------------------------------------------------
        | Check Payment Status
        |--------------------------------------------------------------------------
        */

        $paymentStatus =
            $verifyData['Status']
            ?? $status
            ?? 'Success';


        /*
        |--------------------------------------------------------------------------
        | এখানে Database Update করবেন
        |--------------------------------------------------------------------------
        */

        /*
        if ($paymentStatus === 'Success') {

            Transaction::updateOrCreate(
                [
                    'merchant_transaction_id' =>
                        $merchantTransactionId
                ],
                [
                    'eps_transaction_id' =>
                        $epsTransactionId,

                    'status' =>
                        $paymentStatus,

                    'amount' =>
                        $verifyData['TotalAmount'] ?? 0
                ]
            );
        }
        */


        /*
        |--------------------------------------------------------------------------
        | Return Success View
        |--------------------------------------------------------------------------
        */

        return view('payment.success', [

            'transactionId' =>
                $epsTransactionId,

            'merchantTransactionId' =>
                $merchantTransactionId,

            'status' =>
                $paymentStatus,

            'amount' =>
                $verifyData['TotalAmount']
                ?? 0,

            'paymentData' =>
                $verifyData

        ]);
    }


    /**
     * Payment Failed Callback
     */
    public function fail(Request $request)
    {
        $merchantTransactionId =
            $request->query('MerchantTransactionId')
            ?? $request->query('merchantTransactionId');

        $epsTransactionId =
            $request->query('EPSTransactionId')
            ?? $request->query('EpsTransactionId')
            ?? '';

        $status =
            $request->query('Status')
            ?? $request->query('status')
            ?? 'Failed';


        Log::warning('EPS Payment Failed', [

            'merchantTransactionId' =>
                $merchantTransactionId,

            'epsTransactionId' =>
                $epsTransactionId,

            'status' =>
                $status,

            'all_data' =>
                $request->all()

        ]);


        return view('payment.fail', [

            'transactionId' =>
                $epsTransactionId,

            'merchantTransactionId' =>
                $merchantTransactionId,

            'status' =>
                $status

        ]);
    }


    /**
     * Payment Cancel Callback
     */
    public function cancel(Request $request)
    {
        $merchantTransactionId =
            $request->query('MerchantTransactionId')
            ?? $request->query('merchantTransactionId');

        $epsTransactionId =
            $request->query('EPSTransactionId')
            ?? $request->query('EpsTransactionId')
            ?? '';

        $status =
            $request->query('Status')
            ?? $request->query('status')
            ?? 'Cancelled';


        Log::info('EPS Payment Cancelled', [

            'merchantTransactionId' =>
                $merchantTransactionId,

            'epsTransactionId' =>
                $epsTransactionId,

            'status' =>
                $status,

            'all_data' =>
                $request->all()

        ]);


        return view('payment.cancel', [

            'transactionId' =>
                $epsTransactionId,

            'merchantTransactionId' =>
                $merchantTransactionId,

            'status' =>
                $status

        ]);
    }
}