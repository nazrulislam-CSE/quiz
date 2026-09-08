<?php

namespace App\Http\Controllers\Api\V1\User\Wallet;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BalanceRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\EPS\EPSPayment;
use App\Models\User;
use App\Models\Commission;
use App\Models\Transaction;
use App\Models\Generation;

class BalanceRequestController extends Controller
{
    /**
     * ============================================================
     * Get all balance requests for authenticated user
     * ============================================================
     */
    public function index(Request $request)
    {
        $requests = BalanceRequest::where('user_id', auth()->id())
            ->latest()
            ->get()
            ->map(function ($item) {

                return [
                    'id' => $item->id,
                    'user_id' => $item->user_id,

                    'method' => $item->method,
                    'from_account' => $item->from_account,

                    'amount' => (float) $item->amount,

                    'trx_id' => $item->trx_id,
                    'screenshot' => $item->screenshot,

                    'payment_gateway' => $item->payment_gateway,

                    'merchant_transaction_id' =>
                        $item->merchant_transaction_id,

                    'eps_transaction_id' =>
                        $item->eps_transaction_id,

                    'gateway_transaction_id' =>
                        $item->gateway_transaction_id,

                    'payment_url' =>
                        $item->payment_url,

                    'gateway_response' =>
                        $item->gateway_response,

                    'status' =>
                        $item->status,

                    'payment_status' =>
                        $item->payment_status,

                    'paid_at' =>
                        $item->paid_at
                            ? $item->paid_at->format('d M Y h:i A')
                            : null,

                    'created_at' =>
                        $item->created_at
                            ? $item->created_at->format('d M Y h:i A')
                            : null,

                    'updated_at' =>
                        $item->updated_at
                            ? $item->updated_at->format('d M Y h:i A')
                            : null,
                ];
            });

        return response()->json([
            'success' => true,
            'message' =>
                'Balance requests retrieved successfully.',
            'total' => $requests->count(),
            'data' => $requests,
        ], 200);
    }


    /**
     * ============================================================
     * Store Manual Balance Request
     * ============================================================
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'amount' =>
                'required|numeric|min:1',

            'method' =>
                'nullable|string|max:100',

            'from_account' =>
                'nullable|string|max:100',

            'trx_id' =>
                'nullable|string|max:255',
        ]);

        if ($validator->fails()) {

            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }


        $balanceRequest = BalanceRequest::create([

            'user_id' =>
                auth()->id(),

            'method' =>
                $request->method,

            'from_account' =>
                $request->from_account,

            'amount' =>
                $request->amount,

            'trx_id' =>
                $request->trx_id,

            'screenshot' =>
                null,

            'status' =>
                'pending',

            'payment_status' =>
                'pending',
        ]);


        return response()->json([

            'success' => true,

            'message' =>
                'Balance request submitted successfully.',

            'data' =>
                $balanceRequest,

        ], 201);
    }


    /**
     * ============================================================
     * Initialize EPS Payment
     *
     * Flutter App -> Laravel API -> EPS
     *
     * IMPORTANT:
     * Session ব্যবহার করা হয়নি।
     *
     * EPS response থেকে পাওয়া ACTUAL MerchantTransactionId
     * database-এ save করা হবে।
     * ============================================================
     */
    public function initializeEpsPayment(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'amount' =>
                'required|numeric|min:1',

        ]);

        if ($validator->fails()) {

            return response()->json([

                'success' => false,

                'errors' =>
                    $validator->errors(),

            ], 422);
        }


        try {

            $user = auth()->user();


            if (!$user) {

                return response()->json([

                    'success' => false,

                    'message' =>
                        'Unauthorized user.',

                ], 401);
            }


            // ========================================================
            // Generate Customer Order ID
            // ========================================================

            $customerOrderId =
                'ORDER_' .
                now()->format('YmdHis') .
                '_' .
                random_int(1000, 9999);


            // ========================================================
            // DO NOT use our generated MerchantTransactionId
            // as final transaction ID.
            //
            // EPS response থেকে actual MerchantTransactionId
            // নেওয়া হবে।
            // ========================================================


            // ========================================================
            // Product List
            // ========================================================

            $productArr = [

                [

                    "ProductName" =>
                        "Balance Recharge",

                    "NoOfItem" =>
                        "1",

                    "ProductProfile" =>
                        "101",

                    "ProductCategory" =>
                        "Service",

                    "ProductPrice" =>
                        (string) $request->amount,

                ]

            ];


            // ========================================================
            // EPS Payload
            // ========================================================

            $payload = [

                "CustomerOrderId" =>
                    $customerOrderId,

                "totalAmount" =>
                    (float) $request->amount,

                "ipAddress" =>
                    $request->ip(),

                "successUrl" =>
                    route('payment.eps.success'),

                "failUrl" =>
                    route('payment.eps.fail'),

                "cancelUrl" =>
                    route('payment.eps.cancel'),


                // ====================================================
                // Customer Information
                // ====================================================

                "customerName" =>
                    $user->full_name
                    ?? $user->name
                    ?? "",

                "customerEmail" =>
                    $user->email
                    ?? "",

                "customerAddress" =>
                    $user->present_address
                    ?? $user->address
                    ?? "",

                "customerAddress2" =>
                    "",

                "customerCity" =>
                    $user->city
                    ?? "Dhaka",

                "customerState" =>
                    $user->state
                    ?? "Dhaka",

                "customerPostcode" =>
                    $user->postcode
                    ?? "1000",

                "customerCountry" =>
                    "BD",

                "customerPhone" =>
                    $user->phone
                    ?? "01700000000",


                // ====================================================
                // Shipment Information
                // ====================================================

                "shipmentName" =>
                    $user->full_name
                    ?? $user->name
                    ?? "",

                "shipmentAddress" =>
                    $user->present_address
                    ?? $user->address
                    ?? "",

                "shipmentAddress2" =>
                    "",

                "shipmentCity" =>
                    $user->city
                    ?? "Dhaka",

                "shipmentState" =>
                    $user->state
                    ?? "Dhaka",

                "shipmentPostcode" =>
                    $user->postcode
                    ?? "1000",

                "shipmentCountry" =>
                    "BD",


                // ====================================================
                // Custom Values
                //
                // valueA = User ID
                // valueB = Customer Order ID
                // valueC = Can be used for local reference
                // ====================================================

                "valueA" =>
                    (string) $user->id,

                "valueB" =>
                    (string) $customerOrderId,

                "valueC" =>
                    (string) $user->id,

                "valueD" =>
                    "",


                // ====================================================
                // Product
                // ====================================================

                "shippingMethod" =>
                    "Home Delivery",

                "noOfItem" =>
                    "1",

                "productName" =>
                    "Balance Recharge",

                "productProfile" =>
                    "General",

                "productCategory" =>
                    "Service",

                "ProductList" =>
                    $productArr,
            ];


            // ========================================================
            // Call EPS
            // ========================================================

            $epsPayment = new EPSPayment();

            $data =
                $epsPayment->CreatePayment($payload);


            // ========================================================
            // Log complete EPS initialize response
            // ========================================================

            Log::info(
                'EPS Initialize Payment Response',
                [

                    'user_id' =>
                        $user->id,

                    'customer_order_id' =>
                        $customerOrderId,

                    'amount' =>
                        $request->amount,

                    'eps_response' =>
                        $data,

                ]
            );


            // ========================================================
            // EPS Initialization Failed
            // ========================================================

            if (
                !$data ||
                !isset($data['isSuccess']) ||
                $data['isSuccess'] !== true
            ) {

                Log::error(
                    'EPS Payment Initialization Failed',
                    [

                        'user_id' =>
                            $user->id,

                        'customer_order_id' =>
                            $customerOrderId,

                        'response' =>
                            $data,

                    ]
                );


                return response()->json([

                    'success' => false,

                    'message' =>
                        'EPS Payment Gateway থেকে response পাওয়া যায়নি।',

                    'eps_response' =>
                        $data,

                ], 400);
            }


            // ========================================================
            // IMPORTANT:
            //
            // EPS response থেকে ACTUAL MerchantTransactionId
            // নিতে হবে।
            // ========================================================

            $merchantTransactionId =

                $data['MerchantTransactionId']
                ?? $data['merchantTransactionId']
                ?? $data['merchant_transaction_id']
                ?? null;


            if (!$merchantTransactionId) {

                Log::error(
                    'EPS Merchant Transaction ID Missing',
                    [

                        'user_id' =>
                            $user->id,

                        'customer_order_id' =>
                            $customerOrderId,

                        'eps_response' =>
                            $data,

                    ]
                );


                return response()->json([

                    'success' => false,

                    'message' =>
                        'EPS response থেকে Merchant Transaction ID পাওয়া যায়নি।',

                    'eps_response' =>
                        $data,

                ], 400);
            }


            // ========================================================
            // Payment URL
            // ========================================================

            $paymentUrl =

                $data['RedirectURL']
                ?? $data['payment_url']
                ?? null;


            if (!$paymentUrl) {

                Log::error(
                    'EPS Payment URL Not Found',
                    [

                        'merchant_transaction_id' =>
                            $merchantTransactionId,

                        'eps_response' =>
                            $data,

                    ]
                );


                return response()->json([

                    'success' => false,

                    'message' =>
                        'EPS Payment URL পাওয়া যায়নি।',

                    'eps_response' =>
                        $data,

                ], 400);
            }


            // ========================================================
            // EPS Transaction ID
            // ========================================================

            $epsTransactionId =

                $data['EPSTransactionId']
                ?? $data['EpsTransactionId']
                ?? $data['TransactionId']
                ?? null;


            // ========================================================
            // NOW CREATE DATABASE RECORD
            //
            // এখানে EPS-এর ACTUAL MerchantTransactionId save হচ্ছে।
            //
            // Callback-এ EPS একই ID পাঠালে সরাসরি DB match হবে।
            // ========================================================

            $balanceRequest =
                BalanceRequest::create([

                    'user_id' =>
                        $user->id,

                    'amount' =>
                        $request->amount,

                    'method' =>
                        'eps',

                    'payment_gateway' =>
                        'eps',

                    'merchant_transaction_id' =>
                        $merchantTransactionId,

                    'eps_transaction_id' =>
                        $epsTransactionId,

                    'gateway_transaction_id' =>
                        $merchantTransactionId,

                    'payment_url' =>
                        $paymentUrl,

                    'gateway_response' =>
                        [

                            'initialize_response' =>
                                $data,

                            'customer_order_id' =>
                                $customerOrderId,

                        ],

                    'status' =>
                        'pending',

                    'payment_status' =>
                        'pending',

                ]);


            // ========================================================
            // Final Initialize Log
            // ========================================================

            Log::info(
                'EPS Payment Initialized Successfully',
                [

                    'balance_request_id' =>
                        $balanceRequest->id,

                    'user_id' =>
                        $user->id,

                    'amount' =>
                        $balanceRequest->amount,

                    'customer_order_id' =>
                        $customerOrderId,

                    'merchant_transaction_id' =>
                        $merchantTransactionId,

                    'eps_transaction_id' =>
                        $epsTransactionId,

                ]
            );


            // ========================================================
            // Return To Flutter
            // ========================================================

            return response()->json([

                'success' => true,

                'message' =>
                    'EPS Payment initialized successfully.',

                'data' => [

                    'balance_request_id' =>
                        $balanceRequest->id,

                    'payment_url' =>
                        $paymentUrl,

                    'merchant_transaction_id' =>
                        $merchantTransactionId,

                    'customer_order_id' =>
                        $customerOrderId,

                    'amount' =>
                        (float) $balanceRequest->amount,

                ],

            ], 200);


        } catch (\Throwable $e) {

            Log::error(
                'EPS Payment Initialize Error',
                [

                    'message' =>
                        $e->getMessage(),

                    'user_id' =>
                        auth()->id(),

                    'trace' =>
                        $e->getTraceAsString(),

                ]
            );


            return response()->json([

                'success' => false,

                'message' =>
                    'Payment processing করতে সমস্যা হয়েছে।',

            ], 500);
        }
    }


    /**
 * ============================================================
 * EPS SUCCESS CALLBACK
 *
 * EPS -> Laravel
 *
 * No Session
 * No Flutter authentication required
 * Database based tracking
 *
 * SUCCESS FLOW:
 *
 * EPS Success
 *      ↓
 * Verify EPS Payment
 *      ↓
 * Verify Transaction ID
 *      ↓
 * Verify Amount
 *      ↓
 * DB Transaction
 *      ↓
 * Lock Balance Request
 *      ↓
 * Check Already Paid
 *      ↓
 * User Wallet Update
 *      ↓
 * Referral Commission
 *      ↓
 * Balance Request Approved
 *      ↓
 * Payment Status Paid
 * ============================================================
 */
public function epsSuccess(Request $request)
{
    try {

        // ========================================================
        // Log callback
        // ========================================================

        Log::info(
            'EPS Payment Success Callback',
            [
                'request' => $request->all(),
            ]
        );


        // ========================================================
        // STEP 1:
        // Get Merchant Transaction ID
        // ========================================================

        $merchantTransactionId =
            $request->input('MerchantTransactionId')
            ?? $request->input('merchantTransactionId')
            ?? $request->input('merchant_transaction_id')
            ?? $request->input('valueB')
            ?? $request->input('ValueB');


        if (!$merchantTransactionId) {

            Log::error(
                'EPS Merchant Transaction ID Not Found',
                [
                    'request' => $request->all(),
                ]
            );

            return response()->json([
                'success' => false,
                'message' => 'Merchant Transaction ID পাওয়া যায়নি।',
            ], 400);
        }


        // ========================================================
        // STEP 2:
        // Find DB record
        // ========================================================

        $balanceRequest =
            BalanceRequest::where(
                'merchant_transaction_id',
                $merchantTransactionId
            )->first();


        // ========================================================
        // FALLBACK:
        // gateway_transaction_id দিয়েও search
        // ========================================================

        if (!$balanceRequest) {

            $balanceRequest =
                BalanceRequest::where(
                    'gateway_transaction_id',
                    $merchantTransactionId
                )->first();
        }


        if (!$balanceRequest) {

            Log::error(
                'EPS Balance Request Not Found',
                [
                    'merchant_transaction_id' =>
                        $merchantTransactionId,

                    'request' =>
                        $request->all(),
                ]
            );

            return response()->json([
                'success' => false,
                'message' => 'Payment record পাওয়া যায়নি।',
            ], 404);
        }


        // ========================================================
        // STEP 3:
        // Already Paid?
        //
        // Important:
        // Already paid হলে wallet / commission দ্বিতীয়বার হবে না।
        // ========================================================

        if (
            strtolower(
                (string) $balanceRequest->payment_status
            ) === 'paid'
        ) {

            return response()->json([
                'success' => true,
                'message' => 'Payment already processed.',
                'data' => $balanceRequest,
            ], 200);
        }


        // ========================================================
        // STEP 4:
        // Verify Payment From EPS
        // ========================================================

        $epsPayment = new EPSPayment();


        $verifyResponse =
            $epsPayment->checkPaymentStatus(
                $merchantTransactionId
            );


        Log::info(
            'EPS Payment Verify Response',
            [
                'merchant_transaction_id' =>
                    $merchantTransactionId,

                'balance_request_id' =>
                    $balanceRequest->id,

                'verify_response' =>
                    $verifyResponse,
            ]
        );


        // ========================================================
        // STEP 5:
        // Verify API response
        // ========================================================

        if (
            empty($verifyResponse) ||
            !isset($verifyResponse['isSuccess']) ||
            $verifyResponse['isSuccess'] !== true
        ) {

            Log::warning(
                'EPS Verify API Failed',
                [
                    'merchant_transaction_id' =>
                        $merchantTransactionId,

                    'verify_response' =>
                        $verifyResponse,
                ]
            );

            return response()->json([
                'success' => false,
                'message' =>
                    'EPS থেকে Payment Verify করা সম্ভব হয়নি।',

                'eps_response' =>
                    $verifyResponse,
            ], 400);
        }


        // ========================================================
        // STEP 6:
        // Verify Payment Status
        // ========================================================

        $epsStatus = strtolower(
            trim(
                $verifyResponse['Status']
                ?? ''
            )
        );


        // ========================================================
        // Payment NOT Successful
        // ========================================================

        if ($epsStatus !== 'success') {

            $paymentStatus = match ($epsStatus) {

                'aborted',
                'cancelled',
                'canceled' =>
                    'cancelled',

                'failed',
                'failure' =>
                    'failed',

                default =>
                    $epsStatus ?: 'failed',
            };


            $balanceRequest->update([
                'payment_status' =>
                    $paymentStatus,

                'gateway_response' => [
                    'success_callback' =>
                        $request->all(),

                    'verify_response' =>
                        $verifyResponse,
                ],
            ]);


            Log::warning(
                'EPS Payment Not Successful',
                [
                    'balance_request_id' =>
                        $balanceRequest->id,

                    'merchant_transaction_id' =>
                        $merchantTransactionId,

                    'eps_status' =>
                        $epsStatus,
                ]
            );


            return response()->json([
                'success' => false,

                'message' =>
                    'Payment সফল হয়নি।',

                'payment_status' =>
                    $paymentStatus,
            ], 400);
        }


        // ========================================================
        // STEP 7:
        // Verify Merchant Transaction ID
        // ========================================================

        $verifiedMerchantTransactionId =
            $verifyResponse['MerchantTransactionId']
            ?? $verifyResponse['merchantTransactionId']
            ?? $verifyResponse['merchant_transaction_id']
            ?? null;


        if (
            $verifiedMerchantTransactionId &&
            (string) $verifiedMerchantTransactionId !==
            (string) $merchantTransactionId
        ) {

            Log::error(
                'EPS Merchant Transaction ID Mismatch',
                [
                    'callback_id' =>
                        $merchantTransactionId,

                    'verify_id' =>
                        $verifiedMerchantTransactionId,

                    'balance_request_id' =>
                        $balanceRequest->id,

                    'verify_response' =>
                        $verifyResponse,
                ]
            );


            return response()->json([
                'success' => false,

                'message' =>
                    'Transaction ID verification ব্যর্থ হয়েছে.',
            ], 400);
        }


        // ========================================================
        // STEP 8:
        // Verify Amount
        // ========================================================

        $originalAmount =
            (float) $balanceRequest->amount;


        $verifiedAmount =
            (float) (
                $verifyResponse['TotalAmount']
                ?? $verifyResponse['totalAmount']
                ?? 0
            );


        if (
            $originalAmount <= 0 ||
            $verifiedAmount <= 0 ||
            abs(
                $originalAmount -
                $verifiedAmount
            ) > 0.01
        ) {

            Log::error(
                'EPS Payment Amount Mismatch',
                [
                    'balance_request_id' =>
                        $balanceRequest->id,

                    'merchant_transaction_id' =>
                        $merchantTransactionId,

                    'expected_amount' =>
                        $originalAmount,

                    'verified_amount' =>
                        $verifiedAmount,
                ]
            );


            return response()->json([
                'success' => false,

                'message' =>
                    'Payment Amount verification ব্যর্থ হয়েছে.',
            ], 400);
        }


        // ========================================================
        // STEP 9:
        // EPS Transaction ID
        // ========================================================

        $epsTransactionId =
            $verifyResponse['EPSTransactionId']
            ?? $verifyResponse['EpsTransactionId']
            ?? $verifyResponse['TransactionId']
            ?? $request->input('EPSTransactionId')
            ?? null;


        // ========================================================
        // STEP 10:
        // Financial Entity
        // ========================================================

        $financialEntity =
            $verifyResponse['FinancialEntity']
            ?? 'eps';


        // ========================================================
        // STEP 11:
        // COMPLETE DATABASE TRANSACTION
        // ========================================================

        DB::transaction(function () use (

            $balanceRequest,
            $request,
            $verifyResponse,
            $merchantTransactionId,
            $verifiedMerchantTransactionId,
            $epsTransactionId,
            $financialEntity,
            $verifiedAmount

        ) {

            // ====================================================
            // Lock Balance Request
            // ====================================================

            $lockedBalanceRequest =
                BalanceRequest::lockForUpdate()
                    ->findOrFail(
                        $balanceRequest->id
                    );


            // ====================================================
            // DOUBLE CALLBACK PROTECTION
            //
            // একই payment আবার callback করলে
            // wallet / commission দ্বিতীয়বার হবে না।
            // ====================================================

            if (
                strtolower(
                    (string)
                    $lockedBalanceRequest->payment_status
                ) === 'paid'
            ) {

                return;
            }


            // ====================================================
            // FINAL EPS STATUS CHECK
            // ====================================================

            $finalEpsStatus = strtolower(
                trim(
                    $verifyResponse['Status']
                    ?? ''
                )
            );


            if ($finalEpsStatus !== 'success') {

                throw new \RuntimeException(
                    'EPS payment status is not successful.'
                );
            }


            // ====================================================
            // GET USER
            // ====================================================

            $user =
                User::lockForUpdate()
                    ->find(
                        $lockedBalanceRequest->user_id
                    );


            if (!$user) {

                throw new \RuntimeException(
                    'Payment user not found.'
                );
            }


            // ====================================================
            // PAYMENT AMOUNT
            // ====================================================

            $amount =
                (float) $verifiedAmount;


            if ($amount <= 0) {

                throw new \RuntimeException(
                    'Invalid payment amount.'
                );
            }


            // ====================================================
            // PAYMENT TRANSACTION
            //
            // User যে payment করেছে তার জন্য মূল transaction
            // আগে create হবে।
            // ====================================================

            Transaction::create([

                'from_id' =>
                    $user->id,

                'user_id' =>
                    $user->id,

                'from_user' =>
                    $user->id,

                'out' =>
                    'in',

                'status' =>
                    'success',

                'purpose' =>
                    'EPS Balance Deposit',

                'amount' =>
                    $amount,
            ]);


            // ====================================================
            // COMMISSION SETTINGS
            // ====================================================

            $commission =
                Commission::find(1);


            $commissionBonus = 0;


            // ====================================================
            // REFERRAL COMMISSION
            //
            // User-এর refer_by থাকলে:
            //
            // 1. Direct Referrer commission
            // 2. 1st Generation commission
            // 3. 2nd Generation commission
            // 4. User main wallet-এ refer1 bonus
            // ====================================================

            if (
                $user->refer_by &&
                $commission
            ) {

                // -----------------------------------------------
                // Referral commission distribute
                // -----------------------------------------------

                $this->referCommission(
                    $user,
                    $amount
                );


                // -----------------------------------------------
                // User's own referral bonus
                //
                // amount × refer1 / 100
                // -----------------------------------------------

                $commissionBonus =
                    (
                        $amount *
                        (float) $commission->refer1
                    ) / 100;
            }


            if ($commissionBonus > 0) {

                Transaction::create([

                    'from_id' =>
                        $user->id,

                    'user_id' =>
                        $user->id,

                    'from_user' =>
                        $user->id,

                    'out' =>
                        'in',

                    'status' =>
                        'success',

                    'purpose' =>
                        'Deposit Commission',

                    'amount' =>
                        $commissionBonus,
                ]);
            }
            // ====================================================
            // TOTAL USER BALANCE
            //
            // Example:
            //
            // Payment = 1000
            // refer1 = 20%
            //
            // Commission Bonus = 200
            //
            // Main Wallet = 1200
            // ====================================================

            $totalAdd =
                $amount +
                $commissionBonus;


            // ====================================================
            // UPDATE USER MAIN WALLET
            // ====================================================

            $user->increment(
                'main_wallet',
                $totalAdd
            );


            // ====================================================
            // UPDATE BALANCE REQUEST
            //
            // IMPORTANT:
            // এখানে status = approved হবে।
            // ====================================================

            $lockedBalanceRequest->update([

                'method' =>
                    $financialEntity,

                'payment_gateway' =>
                    'eps',

                'merchant_transaction_id' =>
                    $verifiedMerchantTransactionId
                    ?? $merchantTransactionId,

                'eps_transaction_id' =>
                    $epsTransactionId,

                'gateway_transaction_id' =>
                    $verifiedMerchantTransactionId
                    ?? $merchantTransactionId,

                // EPS verified amount
                'amount' =>
                    $verifiedAmount,

                // Payment successful
                'payment_status' =>
                    'paid',

                // Balance request approved
                'status' =>
                    'approved',

                'trx_id' =>
                    $epsTransactionId,

                'paid_at' =>
                    now(),

                'gateway_response' => [

                    'success_callback' =>
                        $request->all(),

                    'verify_response' =>
                        $verifyResponse,

                    'wallet_update' => [

                        'payment_amount' =>
                            $amount,

                        'commission_bonus' =>
                            $commissionBonus,

                        'total_added_to_main_wallet' =>
                            $totalAdd,
                    ],
                ],
            ]);


            // ====================================================
            // LOG WALLET UPDATE
            // ====================================================

            Log::info(
                'EPS User Wallet Updated',
                [
                    'user_id' =>
                        $user->id,

                    'payment_amount' =>
                        $amount,

                    'commission_bonus' =>
                        $commissionBonus,

                    'total_added' =>
                        $totalAdd,

                    'new_main_wallet' =>
                        $user->fresh()->main_wallet,

                    'balance_request_id' =>
                        $lockedBalanceRequest->id,
                ]
            );

        });


        // ========================================================
        // Refresh Balance Request
        // ========================================================

        $balanceRequest->refresh();


        // ========================================================
        // Get Updated User
        // ========================================================

        $updatedUser =
            User::find(
                $balanceRequest->user_id
            );


        // ========================================================
        // Final Log
        // ========================================================

        Log::info(
            'EPS Payment Verified, Wallet Updated And Approved',
            [
                'balance_request_id' =>
                    $balanceRequest->id,

                'user_id' =>
                    $balanceRequest->user_id,

                'amount' =>
                    $balanceRequest->amount,

                'merchant_transaction_id' =>
                    $balanceRequest->merchant_transaction_id,

                'eps_transaction_id' =>
                    $balanceRequest->eps_transaction_id,

                'payment_status' =>
                    $balanceRequest->payment_status,

                'status' =>
                    $balanceRequest->status,

                'main_wallet' =>
                    $updatedUser
                        ? $updatedUser->main_wallet
                        : null,
            ]
        );


        // ========================================================
        // SUCCESS RESPONSE
        // ========================================================

        return response()->json([

            'success' =>
                true,

            'message' =>
                'Payment সফল হয়েছে, user balance updated এবং balance request approved হয়েছে।',

            'data' => [

                'balance_request' =>
                    $balanceRequest,

                'user' => [

                    'id' =>
                        $updatedUser?->id,

                    'main_wallet' =>
                        $updatedUser
                            ? (float) $updatedUser->main_wallet
                            : null,

                ],
            ],

        ], 200);


    } catch (\Throwable $e) {

        Log::error(
            'EPS Payment Success Callback Error',
            [

                'message' =>
                    $e->getMessage(),

                'trace' =>
                    $e->getTraceAsString(),

                'request' =>
                    $request->all(),
            ]
        );


        return response()->json([

            'success' =>
                false,

            'message' =>
                'Payment verification করতে সমস্যা হয়েছে।',

        ], 500);
    }
}


/**
 * ============================================================
 * REFERRAL COMMISSION
 *
 * Level 0 = Direct Referrer
 * Level 1 = 1st Generation
 * Level 2 = 2nd Generation
 *
 * Commission income_wallet-এ যাবে।
 * ============================================================
 */
public function referCommission($user, $amount)
{
    // ========================================================
    // Commission Settings
    // ========================================================

    $commission =
        Commission::find(1);


    if (!$commission) {

        Log::warning(
            'Referral Commission Setting Not Found',
            [
                'user_id' =>
                    $user->id,

                'amount' =>
                    $amount,
            ]
        );

        return false;
    }


    // ========================================================
    // Direct Referrer ID
    // ========================================================

    $refer_id =
        $user->refer_by;


    if (!$refer_id) {

        return false;
    }


    // ========================================================
    // STEP 1:
    // Direct Referrer
    // ========================================================

    $directReferrer =
        User::lockForUpdate()
            ->find($refer_id);


    if (!$directReferrer) {

        Log::warning(
            'Direct Referrer Not Found',
            [
                'user_id' =>
                    $user->id,

                'refer_id' =>
                    $refer_id,
            ]
        );

        return false;
    }


    // ========================================================
    // Direct Commission
    //
    // amount × refer1 / 100
    // ========================================================

    $directCommission =
        (
            (float) $amount *
            (float) $commission->refer1
        ) / 100;


    if ($directCommission > 0) {

        // ----------------------------------------------------
        // Direct Referrer Income Wallet
        // ----------------------------------------------------

        $directReferrer->increment(
            'income_wallet',
            $directCommission
        );


        // ----------------------------------------------------
        // Transaction
        // ----------------------------------------------------

        Transaction::create([

            'from_id' =>
                $user->id,

            'user_id' =>
                $directReferrer->id,

            'from_user' =>
                $refer_id,

            'out' =>
                'referral',

            'status' =>
                'success',

            'purpose' =>
                'Direct Referral Commission',

            'amount' =>
                $directCommission,
        ]);


        // ----------------------------------------------------
        // Generation
        // ----------------------------------------------------

        Generation::create([

            'from_user_id' =>
                $user->id,

            'to_user_id' =>
                $directReferrer->id,

            'level' =>
                0,

            'date' =>
                now(),

            'status' =>
                1,

            'commission' =>
                $directCommission,

            'total_amount' =>
                $amount,
        ]);
    }


    // ========================================================
    // STEP 2:
    // 1st Generation Referrer
    // ========================================================

    $firstGenReferrer =
        $directReferrer->refer_by
            ? User::lockForUpdate()
                ->find(
                    $directReferrer->refer_by
                )
            : null;


    if ($firstGenReferrer) {

        // ----------------------------------------------------
        // Prevent accidental self commission
        // ----------------------------------------------------

        if (
            $firstGenReferrer->id !==
            $user->id
        ) {

            // ------------------------------------------------
            // 1st Generation Commission
            // amount × refer2 / 100
            // ------------------------------------------------

            $firstGenCommission =
                (
                    (float) $amount *
                    (float) $commission->refer2
                ) / 100;


            if ($firstGenCommission > 0) {

                // --------------------------------------------
                // Income Wallet
                // --------------------------------------------

                $firstGenReferrer->increment(
                    'income_wallet',
                    $firstGenCommission
                );


                // --------------------------------------------
                // Transaction
                // --------------------------------------------

                Transaction::create([

                    'from_id' =>
                        $user->id,

                    'user_id' =>
                        $firstGenReferrer->id,

                    'from_user' =>
                        $refer_id,

                    'out' =>
                        'referral',

                    'status' =>
                        'success',

                    'purpose' =>
                        '1st Generation Referral Commission',

                    'amount' =>
                        $firstGenCommission,
                ]);


                // --------------------------------------------
                // Generation
                // --------------------------------------------

                Generation::create([

                    'from_user_id' =>
                        $user->id,

                    'to_user_id' =>
                        $firstGenReferrer->id,

                    'level' =>
                        1,

                    'date' =>
                        now(),

                    'status' =>
                        1,

                    'commission' =>
                        $firstGenCommission,

                    'total_amount' =>
                        $amount,
                ]);
            }


            // =================================================
            // STEP 3:
            // 2nd Generation Referrer
            // =================================================

            $secondGenReferrer =
                $firstGenReferrer->refer_by
                    ? User::lockForUpdate()
                        ->find(
                            $firstGenReferrer->refer_by
                        )
                    : null;


            if ($secondGenReferrer) {

                // --------------------------------------------
                // Prevent accidental self commission
                // --------------------------------------------

                if (
                    $secondGenReferrer->id !==
                    $user->id &&
                    $secondGenReferrer->id !==
                    $directReferrer->id
                ) {

                    // ----------------------------------------
                    // 2nd Generation Commission
                    // amount × refer3 / 100
                    // ----------------------------------------

                    $secondGenCommission =
                        (
                            (float) $amount *
                            (float) $commission->refer3
                        ) / 100;


                    if ($secondGenCommission > 0) {

                        // ------------------------------------
                        // Income Wallet
                        // ------------------------------------

                        $secondGenReferrer->increment(
                            'income_wallet',
                            $secondGenCommission
                        );


                        // ------------------------------------
                        // Transaction
                        // ------------------------------------

                        Transaction::create([

                            'from_id' =>
                                $user->id,

                            'user_id' =>
                                $secondGenReferrer->id,

                            'from_user' =>
                                $refer_id,

                            'out' =>
                                'referral',

                            'status' =>
                                'success',

                            'purpose' =>
                                '2nd Generation Referral Commission',

                            'amount' =>
                                $secondGenCommission,
                        ]);


                        // ------------------------------------
                        // Generation
                        // ------------------------------------

                        Generation::create([

                            'from_user_id' =>
                                $user->id,

                            'to_user_id' =>
                                $secondGenReferrer->id,

                            'level' =>
                                2,

                            'date' =>
                                now(),

                            'status' =>
                                1,

                            'commission' =>
                                $secondGenCommission,

                            'total_amount' =>
                                $amount,
                        ]);
                    }
                }
            }
        }
    }


    // ========================================================
    // Log Referral Commission
    // ========================================================

    Log::info(
        'Referral Commission Processed',
        [
            'user_id' =>
                $user->id,

            'amount' =>
                $amount,

            'direct_referrer_id' =>
                $directReferrer->id,

            'direct_commission' =>
                $directCommission,

            'refer1_percent' =>
                $commission->refer1,

            'refer2_percent' =>
                $commission->refer2,

            'refer3_percent' =>
                $commission->refer3,
        ]
    );


    return true;
}


    /**
     * ============================================================
     * EPS FAILED CALLBACK
     * ============================================================
     */
    public function epsFail(Request $request)
    {
        try {

            Log::info(
                'EPS Payment Fail Callback',
                [

                    'request' =>
                        $request->all(),

                ]
            );


            $merchantTransactionId =

                $request->input('MerchantTransactionId')
                ?? $request->input('merchantTransactionId')
                ?? $request->input('merchant_transaction_id')
                ?? $request->input('valueB')
                ?? $request->input('ValueB');


            if ($merchantTransactionId) {

                $balanceRequest =

                    BalanceRequest::where(
                        'merchant_transaction_id',
                        $merchantTransactionId
                    )->first();


                if (!$balanceRequest) {

                    $balanceRequest =

                        BalanceRequest::where(
                            'gateway_transaction_id',
                            $merchantTransactionId
                        )->first();
                }


                if ($balanceRequest) {

                    // Paid হয়ে গেলে failed করা যাবে না
                    if (
                        $balanceRequest->payment_status !==
                        'paid'
                    ) {

                        $balanceRequest->update([

                            'payment_status' =>
                                'failed',

                            'gateway_response' => [

                                'fail_callback' =>
                                    $request->all(),

                            ],

                        ]);
                    }
                }
            }


            return response()->json([

                'success' => false,

                'message' =>
                    'Payment ব্যর্থ হয়েছে। আবার চেষ্টা করুন।',

            ], 400);


        } catch (\Throwable $e) {

            Log::error(
                'EPS Payment Fail Error',
                [

                    'message' =>
                        $e->getMessage(),

                    'request' =>
                        $request->all(),

                ]
            );


            return response()->json([

                'success' => false,

                'message' =>
                    'Payment processing এ সমস্যা হয়েছে।',

            ], 500);
        }
    }


    /**
     * ============================================================
     * EPS CANCEL CALLBACK
     * ============================================================
     */
    public function epsCancel(Request $request)
    {
        try {

            Log::info(
                'EPS Payment Cancel Callback',
                [

                    'request' =>
                        $request->all(),

                ]
            );


            $merchantTransactionId =

                $request->input('MerchantTransactionId')
                ?? $request->input('merchantTransactionId')
                ?? $request->input('merchant_transaction_id')
                ?? $request->input('valueB')
                ?? $request->input('ValueB');


            if ($merchantTransactionId) {

                $balanceRequest =

                    BalanceRequest::where(
                        'merchant_transaction_id',
                        $merchantTransactionId
                    )->first();


                if (!$balanceRequest) {

                    $balanceRequest =

                        BalanceRequest::where(
                            'gateway_transaction_id',
                            $merchantTransactionId
                        )->first();
                }


                if ($balanceRequest) {

                    // Paid payment কখনো cancelled হবে না
                    if (
                        $balanceRequest->payment_status !==
                        'paid'
                    ) {

                        $balanceRequest->update([

                            'payment_status' =>
                                'cancelled',

                            'gateway_response' => [

                                'cancel_callback' =>
                                    $request->all(),

                            ],

                        ]);
                    }
                }
            }


            return response()->json([

                'success' => false,

                'message' =>
                    'Payment Cancel করা হয়েছে।',
 
            ], 400);


        } catch (\Throwable $e) {

            Log::error(
                'EPS Payment Cancel Error',
                [

                    'message' =>
                        $e->getMessage(),

                    'request' =>
                        $request->all(),

                ]
            );


            return response()->json([

                'success' => false,

                'message' =>
                    'Payment cancel করতে সমস্যা হয়েছে।',

            ], 500);
        }
    }
}
