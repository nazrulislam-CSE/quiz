<?php

namespace App\Http\Controllers\User;

use App\EPS\EPSPayment;
use App\Http\Controllers\Controller;
use App\Models\BalanceRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Rank;
use App\Models\Commission;
use App\Models\Transaction;
use App\Models\Generation;

class BalanceRequestController extends Controller
{
    /**
     * Balance Recharge Page
     */
    public function create()
    {
        $pageTitle = 'Balance Request';

        return view('user.balance.request', compact('pageTitle'));
    }

    /**
     * EPS Payment Page
     */
    public function index()
    {
        $pageTitle = 'Online Balance Recharge';

        return view('user.balance.request', compact('pageTitle'));
    }

    /**
     * Initialize EPS Payment
     */
    public function initializePayment(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:100',
        ]);

        try {
            // Customer Order ID তৈরি করুন
            $customerOrderId = 'ORDER_'.now()->format('YmdHis').'_'.rand(1000, 9999);

            // Merchant Transaction ID তৈরি করুন
            $merchantTransactionId = 'EPS'.now()->format('YmdHis').rand(10000, 99999);

            // Session এ temporary data রাখুন
            session([
                'eps_payment_data' => [
                    'user_id' => Auth::id(),
                    'amount' => $request->amount,
                    'merchant_transaction_id' => $merchantTransactionId,
                    'customer_order_id' => $customerOrderId,
                    'payment_gateway' => 'eps',
                ],
            ]);

            // Product List তৈরি করুন (Balance recharge এর জন্য)
            $productArr = [
                [
                    'ProductName' => 'Balance Recharge',
                    'NoOfItem' => '1',
                    'ProductProfile' => '101',
                    'ProductCategory' => 'Service',
                    'ProductPrice' => (string) $request->amount,
                ],
            ];

            // EPS Payload তৈরি করুন
            $payload = [
                'CustomerOrderId' => $customerOrderId,
                'totalAmount' => (float) $request->amount,
                'ipAddress' => $request->ip(),
                'successUrl' => route('user.payment.success'),
                'failUrl' => route('user.payment.fail'),
                'cancelUrl' => route('user.payment.cancel'),
                'customerName' => Auth::user()->full_name ?? '',
                'customerEmail' => Auth::user()->email ?? '',
                'customerAddress' => Auth::user()->present_address ?? '',
                'customerAddress2' => '',
                'customerCity' => Auth::user()->city ?? 'Dhaka',
                'customerState' => Auth::user()->state ?? 'Dhaka',
                'customerPostcode' => Auth::user()->postcode ?? '1000',
                'customerCountry' => 'BD',
                'customerPhone' => Auth::user()->phone ?? '01700000000',
                'shipmentName' => Auth::user()->name ?? '',
                'shipmentAddress' => Auth::user()->address ?? '',
                'shipmentAddress2' => '',
                'shipmentCity' => Auth::user()->city ?? 'Dhaka',
                'shipmentState' => Auth::user()->state ?? 'Dhaka',
                'shipmentPostcode' => Auth::user()->postcode ?? '1000',
                'shipmentCountry' => 'BD',
                'valueA' => (string) Auth::id(),
                'valueB' => $merchantTransactionId,
                'valueC' => 'order_id_'.$customerOrderId,
                'valueD' => '',
                'shippingMethod' => 'Home Delivery',
                'noOfItem' => '1',
                'productName' => 'Balance Recharge',
                'productProfile' => 'General',
                'productCategory' => 'Service',
                'ProductList' => $productArr,
            ];

            $epsPayment = new EPSPayment;
            $data = $epsPayment->CreatePayment($payload);

            if (! $data || ! isset($data['isSuccess']) || $data['isSuccess'] !== true) {
                session()->forget('eps_payment_data');
                Log::error('EPS Payment Init Failed', ['data' => $data]);

                return back()
                    ->withInput()
                    ->with('error', 'EPS Payment Gateway থেকে কোনো response পাওয়া যায়নি।');
            }

            $paymentUrl = $data['RedirectURL'] ?? $data['payment_url'] ?? null;

            if (! $paymentUrl) {
                session()->forget('eps_payment_data');
                Log::error('EPS Payment URL Not Found', ['response' => $data]);

                return back()->with('error', 'EPS Payment URL পাওয়া যায়নি।');
            }

            // Session update with EPS response
            session([
                'eps_payment_data' => array_merge(
                    session('eps_payment_data', []),
                    [
                        'eps_transaction_id' => $data['TransactionId'] ?? null,
                        'gateway_transaction_id' => $data['MerchantTransactionId'] ?? null,
                        'gateway_response' => $data,
                        'payment_url' => $paymentUrl,
                    ]
                ),
            ]);

            return redirect()->away($paymentUrl);

        } catch (\Exception $e) {
            session()->forget('eps_payment_data');
            Log::error('EPS Payment Initialize Error', [
                'message' => $e->getMessage(),
                'user_id' => Auth::id(),
            ]);

            return back()
                ->withInput()
                ->with('error', 'Payment processing করতে সমস্যা হয়েছে।');
        }
    }

    public function success(Request $request)
    {
        try {

            Log::info('EPS Payment Success Callback', [
                'request' => $request->all(),
            ]);

            $sessionData = session('eps_payment_data', []);

            // ============================================================
            // STEP 1: Merchant Transaction ID বের করুন
            // ============================================================

            $merchantTransactionId =
                $request->merchant_transaction_id ??
                $request->MerchantTransactionId ??
                $request->merchantTransactionId ??
                $sessionData['merchant_transaction_id'] ??
                null;

            if (! $merchantTransactionId) {

                Log::error('EPS Payment Success: Transaction ID not found', [
                    'request' => $request->all(),
                    'session' => $sessionData,
                ]);

                session()->forget('eps_payment_data');

                return redirect()
                    ->route('user.eps.payment')
                    ->with('error', 'Transaction তথ্য পাওয়া যায়নি।');
            }

            // ============================================================
            // STEP 2: Duplicate Transaction Check
            // ============================================================

            $existingRequest = BalanceRequest::where(
                'merchant_transaction_id',
                $merchantTransactionId
            )->first();

            if ($existingRequest) {

                session()->forget('eps_payment_data');

                return redirect()
                    ->route('user.balance.request.report')
                    ->with(
                        'success',
                        'এই payment ইতিমধ্যে প্রক্রিয়াকৃত হয়েছে।'
                    );
            }

            // ============================================================
            // STEP 3: EPS VERIFY TRANSACTION API CALL
            // ============================================================

            $epsPayment = new EPSPayment;

            $verifyResponse = $epsPayment->checkPaymentStatus(
                $merchantTransactionId
            );

            // ============================================================
            // STEP 4: Verify API Response Log
            // ============================================================

            Log::info('EPS Final Transaction Verify Response', [
                'merchant_transaction_id' => $merchantTransactionId,
                'verify_response' => $verifyResponse,
            ]);

            // ============================================================
            // STEP 5: Verify API HTTP/API Success Check
            // ============================================================

            if (
                ! $verifyResponse ||
                ! isset($verifyResponse['isSuccess']) ||
                $verifyResponse['isSuccess'] !== true
            ) {

                Log::warning('EPS Verify API Failed', [
                    'merchant_transaction_id' => $merchantTransactionId,
                    'verify_response' => $verifyResponse,
                ]);

                session()->forget('eps_payment_data');

                return redirect()
                    ->route('user.eps.payment')
                    ->with(
                        'error',
                        'EPS থেকে Payment Verify করা সম্ভব হয়নি।'
                    );
            }

            // ============================================================
            // STEP 6: EPS Transaction Status Check
            // ============================================================

            $epsStatus = strtolower(
                trim(
                    $verifyResponse['Status'] ?? ''
                )
            );

            if ($epsStatus !== 'success') {

                Log::warning('EPS Payment Status Not Success', [
                    'merchant_transaction_id' => $merchantTransactionId,
                    'eps_status' => $verifyResponse['Status'] ?? null,
                    'verify_response' => $verifyResponse,
                ]);

                session()->forget('eps_payment_data');

                return redirect()
                    ->route('user.eps.payment')
                    ->with(
                        'error',
                        'Payment সফল হয়নি। EPS থেকে Transaction Status: '.
                        ($verifyResponse['Status'] ?? 'Unknown')
                    );
            }

            // ============================================================
            // STEP 7: Verify Merchant Transaction ID Match
            // ============================================================

            $verifiedMerchantTransactionId =
                $verifyResponse['MerchantTransactionId']
                ?? null;

            if (
                $verifiedMerchantTransactionId &&
                $verifiedMerchantTransactionId !== $merchantTransactionId
            ) {

                Log::error('EPS Merchant Transaction ID Mismatch', [
                    'expected' => $merchantTransactionId,
                    'received' => $verifiedMerchantTransactionId,
                    'verify_response' => $verifyResponse,
                ]);

                session()->forget('eps_payment_data');

                return redirect()
                    ->route('user.eps.payment')
                    ->with(
                        'error',
                        'Transaction ID verification ব্যর্থ হয়েছে।'
                    );
            }

            // ============================================================
            // STEP 8: Verify Amount Match
            // ============================================================

            $originalAmount = (float) (
                $sessionData['amount'] ?? 0
            );

            $verifiedAmount = (float) (
                $verifyResponse['TotalAmount'] ?? 0
            );

            if (
                $originalAmount > 0 &&
                abs($originalAmount - $verifiedAmount) > 0.01
            ) {

                Log::error('EPS Payment Amount Mismatch', [
                    'merchant_transaction_id' => $merchantTransactionId,
                    'expected_amount' => $originalAmount,
                    'received_amount' => $verifiedAmount,
                    'verify_response' => $verifyResponse,
                ]);

                session()->forget('eps_payment_data');

                return redirect()
                    ->route('user.eps.payment')
                    ->with(
                        'error',
                        'Payment Amount verification ব্যর্থ হয়েছে।'
                    );
            }

            // ============================================================
            // STEP 9: Get EPS Transaction ID
            // ============================================================

            $epsTransactionId =
                $verifyResponse['EpsTransactionId']
                ?? $verifyResponse['EPSTransactionId']
                ?? null;

            // ============================================================
            // STEP 10: Database Transaction Start
            // ============================================================

            DB::beginTransaction();

            try {


                // ====================================================
                // PAYMENT TRANSACTION
                //
                // User যে payment করেছে তার জন্য মূল transaction
                // আগে create হবে।
                // ====================================================

                Transaction::create([

                    'from_id' =>
                    $sessionData['user_id']
                            ?? Auth::id(),

                    'user_id' =>
                        $sessionData['user_id']
                            ?? Auth::id(),

                    'from_user' =>
                        $sessionData['user_id']
                            ?? Auth::id(),

                    'out' =>
                        'in',

                    'status' =>
                        'success',

                    'purpose' =>
                        'EPS Balance Deposit',

                    'amount' =>
                        $verifiedAmount,
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

                $user = User::find(
                    $sessionData['user_id']
                        ?? Auth::id()
                );

                if (
                    $user->refer_by &&
                    $commission
                ) {

                    // -----------------------------------------------
                    // Referral commission distribute
                    // -----------------------------------------------

                    $this->referCommission(
                        $user,
                        $verifiedAmount,
                    );


                    // -----------------------------------------------
                    // User's own referral bonus
                    //
                    // amount × refer1 / 100
                    // -----------------------------------------------

                    $commissionBonus =
                        (
                            $verifiedAmount *
                            (float) $commission->refer1
                        ) / 100;
                }

                // ====================================================
                // COMMISSION TRANSACTION
                // ====================================================

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
                    $verifiedAmount +
                    $commissionBonus;


                // ====================================================
                // UPDATE USER MAIN WALLET
                // ====================================================

                $user->increment(
                    'main_wallet',
                    $totalAdd
                );


                // ========================================================
                // Create Balance Request
                // ========================================================

                $balanceRequest = BalanceRequest::create([

                    'user_id' => $sessionData['user_id']
                        ?? Auth::id(),

                    // Amount EPS Verify API থেকে
                    'amount' => $verifiedAmount,

                    'payment_gateway' => 'eps',

                    // Merchant Transaction ID
                    'merchant_transaction_id' => $verifiedMerchantTransactionId
                        ?? $merchantTransactionId,

                    // EPS Transaction ID
                    'eps_transaction_id' => $epsTransactionId,

                    'gateway_transaction_id' => $verifiedMerchantTransactionId
                        ?? $merchantTransactionId,

                    'payment_url' => $sessionData['payment_url']
                        ?? null,

                    // সব Gateway Response Save
                    'gateway_response' => [

                        // Initialize Response
                        'initialize_response' => $sessionData['gateway_response']
                            ?? [],

                        // Success Callback Response
                        'success_callback' => $request->all(),

                        // Final EPS Verify API Response
                        'verify_response' => $verifyResponse,
                    ],

                    'status' => 'approved',

                    'payment_status' => 'paid',

                    'trx_id' => $epsTransactionId,

                    // EPS Verify API থেকে Financial Entity
                    // যেমন: bKash, Nagad, OKWallet
                    'method' => $verifyResponse['FinancialEntity']
                        ?? 'eps',

                    'paid_at' => now(),

                ]);

                // ========================================================
                // Commit Database
                // ========================================================

                DB::commit();

                // ========================================================
                // UPDATE USER RANK
                // ========================================================

               try {

                    $this->updateUserRank(
                        $balanceRequest->user_id
                    );

                    Log::info('User rank updated successfully', [
                        'user_id' => $balanceRequest->user_id,
                        'balance_request_id' => $balanceRequest->id,
                    ]);

                } catch (\Throwable $e) {

                    Log::error('User Rank Update Failed', [
                        'user_id' => $balanceRequest->user_id,
                        'balance_request_id' => $balanceRequest->id,
                        'message' => $e->getMessage(),
                        'trace' => $e->getTraceAsString(),
                    ]);

                }

                // ========================================================
                // Clear Session
                // ============================================================

                session()->forget('eps_payment_data');

                // ========================================================
                // Success Log
                // ============================================================

                Log::info(
                    'EPS Payment Successfully Verified And Saved',
                    [

                        'balance_request_id' => $balanceRequest->id,

                        'user_id' => $balanceRequest->user_id,

                        'amount' => $balanceRequest->amount,

                        'merchant_transaction_id' => $merchantTransactionId,

                        'eps_transaction_id' => $epsTransactionId,

                        'financial_entity' => $verifyResponse['FinancialEntity']
                            ?? null,
                    ]
                );

                return redirect()
                    ->route('user.balance.request.report')
                    ->with(
                        'success',
                        'Payment সফল হয়েছে। আপনার ব্যালান্স রিচার্জ করা হয়েছে।'
                    );

            } catch (\Exception $e) {

                DB::rollBack();

                throw $e;
            }

        } catch (\Exception $e) {

            DB::rollBack();

            session()->forget('eps_payment_data');

            Log::error('EPS Payment Success Verify Error', [

                'message' => $e->getMessage(),

                'trace' => $e->getTraceAsString(),

                'request' => $request->all(),

            ]);

            return redirect()
                ->route('user.eps.payment')
                ->with(
                    'error',
                    'Payment verification করতে সমস্যা হয়েছে।'
                );
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

    public function updateUserRank($userId)
    {
        $user = User::find($userId);

        if (!$user) {
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | STEP 1: User এর নিজের মোট paid deposit
        |--------------------------------------------------------------------------
        */

        $ownDeposit = BalanceRequest::where('user_id', $user->id)
            ->where('status', 'approved')
            ->where('payment_status', 'paid')
            ->sum('amount');


        /*
        |--------------------------------------------------------------------------
        | STEP 2: পুরো Team বের করা
        |--------------------------------------------------------------------------
        */

        $teamUserIds = $this->getTeamUserIds($user->id);


        /*
        |--------------------------------------------------------------------------
        | STEP 3: Team Deposit Turnover
        |--------------------------------------------------------------------------
        */

        $teamDepositTurnover = 0;

        if (!empty($teamUserIds)) {
            $teamDepositTurnover = BalanceRequest::whereIn(
                    'user_id',
                    $teamUserIds
                )
                ->where('status', 'approved')
                ->where('payment_status', 'paid')
                ->sum('amount');
        }


        /*
        |--------------------------------------------------------------------------
        | STEP 4: Direct Paid Customer
        |--------------------------------------------------------------------------
        */

        $directUsers = User::where('refer_by', $user->id)->get();

        $directPaidCustomer = 0;

        foreach ($directUsers as $directUser) {

            $deposit = BalanceRequest::where('user_id', $directUser->id)
                ->where('status', 'approved')
                ->where('payment_status', 'paid')
                ->sum('amount');

            if ($deposit >= 100) {
                $directPaidCustomer++;
            }
        }


        /*
        |--------------------------------------------------------------------------
        | STEP 5: Team Rank Count
        |--------------------------------------------------------------------------
        */

        $teamRanks = Rank::whereIn('user_id', $teamUserIds)
            ->where('status', 1)
            ->get()
            ->groupBy('rank_name');


        /*
        |--------------------------------------------------------------------------
        | STEP 6: Rank Eligibility
        |--------------------------------------------------------------------------
        */

        $earnedRank = null;


        // ---------------------------------------------------------
        // 1. LEARNER
        // Minimum own deposit = 100
        // ---------------------------------------------------------

        if ($ownDeposit >= 100) {
            $earnedRank = [
                'rank_name'    => 'LEARNER',
                'rank_deposit' => $ownDeposit,
                'rank_reward'  => 0,
                'reward_text'  => null,
            ];
        }


        // ---------------------------------------------------------
        // 2. DREAMER
        // Direct Paid Customer = 50
        // Team Turnover = 50,000
        // ---------------------------------------------------------

        if (
            $directPaidCustomer >= 50 &&
            $teamDepositTurnover >= 50000
        ) {
            $earnedRank = [
                'rank_name'    => 'DREAMER',
                'rank_deposit' => $teamDepositTurnover,
                'rank_reward'  => 5000,
                'reward_text'  => '৳5,000 Cash অথবা Cox’s Bazar Tour',
            ];
        }


        // ---------------------------------------------------------
        // 3. EDTECH ENTREPRENEUR
        // 5 DREAMER
        // Team Turnover = 250,000
        // ---------------------------------------------------------

        $dreamerCount = $teamRanks
            ->get('DREAMER', collect())
            ->count();

        if (
            $dreamerCount >= 5 &&
            $teamDepositTurnover >= 250000
        ) {
            $earnedRank = [
                'rank_name'    => 'EDTECH ENTREPRENEUR',
                'rank_deposit' => $teamDepositTurnover,
                'rank_reward'  => 15000,
                'reward_text'  => '৳15,000 Cash অথবা India Tour',
            ];
        }


        // ---------------------------------------------------------
        // 4. PLAN MASTER
        // 4 EDTECH ENTREPRENEUR
        // Team Turnover = 1,000,000
        // ---------------------------------------------------------

        $entrepreneurCount = $teamRanks
            ->get('EDTECH ENTREPRENEUR', collect())
            ->count();

        if (
            $entrepreneurCount >= 4 &&
            $teamDepositTurnover >= 1000000
        ) {
            $earnedRank = [
                'rank_name'    => 'PLAN MASTER',
                'rank_deposit' => $teamDepositTurnover,
                'rank_reward'  => 50000,
                'reward_text'  => '৳50,000 Cash অথবা Nepal Tour',
            ];
        }


        // ---------------------------------------------------------
        // 5. MERIT STAR
        // 3 PLAN MASTER
        // Team Turnover = 3,000,000
        // ---------------------------------------------------------

        $planMasterCount = $teamRanks
            ->get('PLAN MASTER', collect())
            ->count();

        if (
            $planMasterCount >= 3 &&
            $teamDepositTurnover >= 3000000
        ) {
            $earnedRank = [
                'rank_name'    => 'MERIT STAR',
                'rank_deposit' => $teamDepositTurnover,
                'rank_reward'  => 100000,
                'reward_text'  => '৳1,00,000 Cash অথবা Thailand Tour',
            ];
        }


        // ---------------------------------------------------------
        // 6. CAMPUS CHAMPION
        // 2 MERIT STAR
        // Team Turnover = 6,000,000
        // ---------------------------------------------------------

        $meritStarCount = $teamRanks
            ->get('MERIT STAR', collect())
            ->count();

        if (
            $meritStarCount >= 2 &&
            $teamDepositTurnover >= 6000000
        ) {
            $earnedRank = [
                'rank_name'    => 'CAMPUS CHAMPION',
                'rank_deposit' => $teamDepositTurnover,
                'rank_reward'  => 200000,
                'reward_text'  => '৳2,00,000 Cash অথবা Motorcycle',
            ];
        }


        // ---------------------------------------------------------
        // 7. LEADER
        // 2 CAMPUS CHAMPION
        // Team Turnover = 12,000,000
        // ---------------------------------------------------------

        $campusChampionCount = $teamRanks
            ->get('CAMPUS CHAMPION', collect())
            ->count();

        if (
            $campusChampionCount >= 2 &&
            $teamDepositTurnover >= 12000000
        ) {
            $earnedRank = [
                'rank_name'    => 'LEADER',
                'rank_deposit' => $teamDepositTurnover,
                'rank_reward'  => 500000,
                'reward_text'  => '৳5,00,000 Cash অথবা Couple Umrah + Bike',
            ];
        }


        // ---------------------------------------------------------
        // 8. EDTECH BRAND BUILDER
        // 2 LEADER
        // Team Turnover = 24,000,000
        // ---------------------------------------------------------

        $leaderCount = $teamRanks
            ->get('LEADER', collect())
            ->count();

        if (
            $leaderCount >= 2 &&
            $teamDepositTurnover >= 24000000
        ) {
            $earnedRank = [
                'rank_name'    => 'EDTECH BRAND BUILDER',
                'rank_deposit' => $teamDepositTurnover,
                'rank_reward'  => 1500000,
                'reward_text'  => '৳15,00,000 Cash অথবা Couple Hajj',
            ];
        }


        // ---------------------------------------------------------
        // 9. TOP EDTECH CONSULT
        // 2 EDTECH BRAND BUILDER
        // Team Turnover = 48,000,000
        // ---------------------------------------------------------

        $brandBuilderCount = $teamRanks
            ->get('EDTECH BRAND BUILDER', collect())
            ->count();

        if (
            $brandBuilderCount >= 2 &&
            $teamDepositTurnover >= 48000000
        ) {
            $earnedRank = [
                'rank_name'    => 'TOP EDTECH CONSULT',
                'rank_deposit' => $teamDepositTurnover,
                'rank_reward'  => 3500000,
                'reward_text'  => '৳35,00,000 Cash অথবা Private Car | Monthly Salary: ৳40,000',
            ];
        }


        // ---------------------------------------------------------
        // 10. NATIONAL RANK ACHIEVER
        // 2 TOP EDTECH CONSULT
        // Team Turnover = 96,000,000
        // ---------------------------------------------------------

        $topConsultCount = $teamRanks
            ->get('TOP EDTECH CONSULT', collect())
            ->count();

        if (
            $topConsultCount >= 2 &&
            $teamDepositTurnover >= 96000000
        ) {
            $earnedRank = [
                'rank_name'    => 'NATIONAL RANK ACHIEVER',
                'rank_deposit' => $teamDepositTurnover,
                'rank_reward'  => 10000000,
                'reward_text'  => '৳1,00,00,000 অথবা Luxury Apartment | Company Profit Share: 1%',
            ];
        }


        /*
        |--------------------------------------------------------------------------
        | STEP 7: Rank Insert / Update
        |--------------------------------------------------------------------------
        */

        if ($earnedRank) {

            Rank::updateOrCreate(
                [
                    'user_id'   => $user->id,
                    'rank_name' => $earnedRank['rank_name'],
                ],
                [
                    'rank_deposit' => $earnedRank['rank_deposit'],
                    'rank_reward'  => $earnedRank['rank_reward'],
                    'reward_text'  => $earnedRank['reward_text'],
                    'status'       => 1,
                ]
            );
        }
    }

    private function getTeamUserIds($userId)
    {
        $teamIds = [];

        $children = User::where('refer_by', $userId)->pluck('id');

        foreach ($children as $childId) {

            $teamIds[] = $childId;

            $teamIds = array_merge(
                $teamIds,
                $this->getTeamUserIds($childId)
            );
        }

        return array_unique($teamIds);
    }


    /**
     * Payment Failed - কোনো record তৈরি করবেন না
     */
    public function fail(Request $request)
    {
        try {
            Log::info('EPS Payment Fail Callback', $request->all());
            session()->forget('eps_payment_data');

            return redirect()
                ->route('user.eps.payment')
                ->with('error', 'Payment ব্যর্থ হয়েছে। আবার চেষ্টা করুন।');

        } catch (\Exception $e) {
            Log::error('Payment Fail Error', [
                'message' => $e->getMessage(),
                'request' => $request->all(),
            ]);
            session()->forget('eps_payment_data');

            return redirect()
                ->route('user.eps.payment')
                ->with('error', 'Payment processing এ সমস্যা হয়েছে।');
        }
    }

    /**
     * Payment Cancel - কোনো record তৈরি করবেন না
     */
    public function cancel(Request $request)
    {
        try {
            Log::info('EPS Payment Cancel Callback', $request->all());
            session()->forget('eps_payment_data');

            return redirect()
                ->route('user.eps.payment')
                ->with('error', 'Payment Cancel করা হয়েছে।');

        } catch (\Exception $e) {
            Log::error('Payment Cancel Error', [
                'message' => $e->getMessage(),
                'request' => $request->all(),
            ]);
            session()->forget('eps_payment_data');

            return redirect()
                ->route('user.eps.payment')
                ->with('error', 'Payment cancel করতে সমস্যা হয়েছে।');
        }
    }

    /**
     * Balance Request Report
     */
    public function report()
    {
        $pageTitle = 'Balance Request Report';
        $requests = BalanceRequest::where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('user.balance.report', compact('pageTitle', 'requests'));
    }
}
