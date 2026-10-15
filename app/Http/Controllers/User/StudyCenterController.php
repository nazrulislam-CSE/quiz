<?php

namespace App\Http\Controllers\User;

use App\EPS\EPSPayment;
use App\Http\Controllers\Controller;
use App\Models\Commission;
use App\Models\Course;
use App\Models\CourseOrder;
use App\Models\Generation;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class StudyCenterController extends Controller
{
    /* ============================================================
       Study Center — course list
    ============================================================ */
    public function index()
    {
        $pageTitle = 'স্টাডি সেন্টার';

        $courses = Course::with(['courseType', 'courseClass', 'courseCategory'])
                         ->where('status', 'active')
                         ->latest()
                         ->get();

        return view('user.study.center.index', compact('pageTitle', 'courses'));
    }

    /* ============================================================
       Single Course Details
    ============================================================ */
    public function show(string $slug)
    {
        $course = Course::with(['courseType', 'courseClass', 'courseCategory'])
                        ->where('slug', $slug)
                        ->where('status', 'active')
                        ->firstOrFail();

        $viewKey = 'viewed_course_' . $course->id;
        if (!session()->has($viewKey)) {
            $course->increment('view_count');
            session()->put($viewKey, true);
        }

        $pageTitle = $course->title;

        $relatedCourses = Course::with(['courseType', 'courseClass'])
                                ->where('course_category_id', $course->course_category_id)
                                ->where('id', '!=', $course->id)
                                ->where('status', 'active')
                                ->take(3)
                                ->get();

        $alreadyOwned = false;
        if (Auth::check()) {
            $alreadyOwned = CourseOrder::where('user_id', Auth::id())
                                       ->where('course_id', $course->id)
                                       ->where('payment_status', 'paid')
                                       ->exists();
        }

        return view('user.study.center.show', compact(
            'pageTitle',
            'course',
            'relatedCourses',
            'alreadyOwned'
        ));
    }

    /* ============================================================
       BUY — EPS Initialize
    ============================================================ */
    public function buy(Request $request, Course $course)
    {
        if ($course->status !== 'active') {
            return back()->with('error', 'এই কোর্সটি এখন পাওয়া যাচ্ছে না।');
        }

        if ($course->is_free) {
            return back()->with('error', 'ফ্রি কোর্স কেনার প্রয়োজন নেই।');
        }

        $alreadyOwned = CourseOrder::where('user_id', Auth::id())
                                   ->where('course_id', $course->id)
                                   ->where('payment_status', 'paid')
                                   ->exists();

        if ($alreadyOwned) {
            return redirect()
                ->route('study.center.course.show', $course->slug)
                ->with('success', 'আপনি ইতিমধ্যে এই কোর্সটি কিনেছেন।');
        }

        $amount = (float) $course->final_price;

        if ($amount <= 0) {
            return back()->with('error', 'Invalid payment amount.');
        }

        try {
            $customerOrderId       = 'CRS_' . now()->format('YmdHis') . '_' . rand(1000, 9999);
            $merchantTransactionId = 'EPS' . now()->format('YmdHis') . rand(10000, 99999);
            $orderNo               = 'ORD-' . strtoupper(Str::random(10));

            session([
                'course_payment_data' => [
                    'user_id'                 => Auth::id(),
                    'course_id'               => $course->id,
                    'amount'                  => $amount,
                    'order_no'                => $orderNo,
                    'merchant_transaction_id' => $merchantTransactionId,
                    'customer_order_id'       => $customerOrderId,
                ],
            ]);

            $productArr = [[
                'ProductName'     => $course->title,
                'NoOfItem'        => '1',
                'ProductProfile'  => '101',
                'ProductCategory' => 'Course',
                'ProductPrice'    => (string) $amount,
            ]];

            $payload = [
                'CustomerOrderId'    => $customerOrderId,
                'totalAmount'        => $amount,
                'ipAddress'          => $request->ip(),
                'successUrl'         => route('user.course.payment.success'),
                'failUrl'            => route('user.course.payment.fail'),
                'cancelUrl'          => route('user.course.payment.cancel'),
                'customerName'       => Auth::user()->full_name ?? '',
                'customerEmail'      => Auth::user()->email ?? '',
                'customerAddress'    => Auth::user()->address ?? '',
                'customerAddress2'   => '',
                'customerCity'       => Auth::user()->city ?? 'Dhaka',
                'customerState'      => Auth::user()->state ?? 'Dhaka',
                'customerPostcode'   => Auth::user()->postcode ?? '1000',
                'customerCountry'    => 'BD',
                'customerPhone'      => Auth::user()->phone ?? '01700000000',
                'shipmentName'       => Auth::user()->full_name ?? '',
                'shipmentAddress'    => Auth::user()->address ?? '',
                'shipmentAddress2'   => '',
                'shipmentCity'       => Auth::user()->city ?? 'Dhaka',
                'shipmentState'      => Auth::user()->state ?? 'Dhaka',
                'shipmentPostcode'   => Auth::user()->postcode ?? '1000',
                'shipmentCountry'    => 'BD',
                'valueA'             => (string) Auth::id(),
                'valueB'             => $merchantTransactionId,
                'valueC'             => 'course_' . $course->id,
                'valueD'             => '',
                'shippingMethod'     => 'Digital',
                'noOfItem'           => '1',
                'productName'        => $course->title,
                'productProfile'     => 'Course',
                'productCategory'    => 'Course',
                'ProductList'        => $productArr,
            ];

            $epsPayment = new EPSPayment;
            $data = $epsPayment->CreatePayment($payload);

            if (! $data || ! isset($data['isSuccess']) || $data['isSuccess'] !== true) {
                session()->forget('course_payment_data');
                Log::error('Course EPS Init Failed', ['data' => $data]);

                return back()->with('error', 'Payment gateway থেকে response পাওয়া যায়নি।');
            }

            $paymentUrl = $data['RedirectURL'] ?? $data['payment_url'] ?? null;

            if (! $paymentUrl) {
                session()->forget('course_payment_data');
                Log::error('Course EPS URL Missing', ['response' => $data]);

                return back()->with('error', 'Payment URL পাওয়া যায়নি।');
            }

            session([
                'course_payment_data' => array_merge(
                    session('course_payment_data', []),
                    [
                        'eps_transaction_id'     => $data['TransactionId'] ?? null,
                        'gateway_transaction_id' => $data['MerchantTransactionId'] ?? null,
                        'gateway_response'       => $data,
                        'payment_url'            => $paymentUrl,
                    ]
                ),
            ]);

            return redirect()->away($paymentUrl);

        } catch (\Exception $e) {
            session()->forget('course_payment_data');
            Log::error('Course EPS Initialize Error', [
                'message'   => $e->getMessage(),
                'user_id'   => Auth::id(),
                'course_id' => $course->id,
            ]);

            return back()->with('error', 'Payment processing এ সমস্যা হয়েছে।');
        }
    }

    /* ============================================================
       PAYMENT SUCCESS — Verify + Order Create + Commission
    ============================================================ */
    public function paymentSuccess(Request $request)
    {
        try {
            Log::info('Course EPS Success Callback', ['request' => $request->all()]);

            $sessionData = session('course_payment_data', []);

            /* ===== STEP 1: Merchant Transaction ID ===== */
            $merchantTransactionId =
                $request->merchant_transaction_id ??
                $request->MerchantTransactionId ??
                $request->merchantTransactionId ??
                $sessionData['merchant_transaction_id'] ??
                null;

            if (! $merchantTransactionId) {
                Log::error('Course EPS: Transaction ID not found');
                session()->forget('course_payment_data');

                return redirect()->route('study.center')
                    ->with('error', 'Transaction তথ্য পাওয়া যায়নি।');
            }

            /* ===== STEP 2: Duplicate Check ===== */
            $existingOrder = CourseOrder::where('merchant_transaction_id', $merchantTransactionId)->first();
            if ($existingOrder) {
                session()->forget('course_payment_data');

                return redirect()
                    ->route('study.center.course.show', $existingOrder->course->slug ?? '')
                    ->with('success', 'এই payment ইতিমধ্যে প্রক্রিয়াকৃত হয়েছে।');
            }

            /* ===== STEP 3: EPS Verify API ===== */
            $epsPayment = new EPSPayment;
            $verifyResponse = $epsPayment->checkPaymentStatus($merchantTransactionId);

            Log::info('Course EPS Verify Response', [
                'merchant_transaction_id' => $merchantTransactionId,
                'verify_response'         => $verifyResponse,
            ]);

            /* ===== STEP 4: Verify API Success ===== */
            if (
                ! $verifyResponse ||
                ! isset($verifyResponse['isSuccess']) ||
                $verifyResponse['isSuccess'] !== true
            ) {
                session()->forget('course_payment_data');

                return redirect()->route('study.center')
                    ->with('error', 'Payment Verify করা সম্ভব হয়নি।');
            }

            /* ===== STEP 5: EPS Status ===== */
            $epsStatus = strtolower(trim($verifyResponse['Status'] ?? ''));

            if ($epsStatus !== 'success') {
                session()->forget('course_payment_data');

                return redirect()->route('study.center')
                    ->with('error', 'Payment সফল হয়নি। Status: ' . ($verifyResponse['Status'] ?? 'Unknown'));
            }

            /* ===== STEP 6: Txn ID Match ===== */
            $verifiedMerchantTransactionId = $verifyResponse['MerchantTransactionId'] ?? null;
            if (
                $verifiedMerchantTransactionId &&
                $verifiedMerchantTransactionId !== $merchantTransactionId
            ) {
                session()->forget('course_payment_data');

                return redirect()->route('study.center')
                    ->with('error', 'Transaction ID verification ব্যর্থ হয়েছে।');
            }

            /* ===== STEP 7: Amount Match ===== */
            $originalAmount = (float) ($sessionData['amount'] ?? 0);
            $verifiedAmount = (float) ($verifyResponse['TotalAmount'] ?? 0);

            if ($originalAmount > 0 && abs($originalAmount - $verifiedAmount) > 0.01) {
                session()->forget('course_payment_data');

                return redirect()->route('study.center')
                    ->with('error', 'Payment amount verification ব্যর্থ হয়েছে।');
            }

            /* ===== STEP 8: EPS Txn ID ===== */
            $epsTransactionId =
                $verifyResponse['EpsTransactionId'] ??
                $verifyResponse['EPSTransactionId'] ??
                null;

            /* ===== STEP 9: DB Transaction ===== */
            DB::beginTransaction();

            try {
                $user = User::findOrFail($sessionData['user_id'] ?? Auth::id());
                $course = Course::findOrFail($sessionData['course_id']);

                /* ----- Commission Base Amount -----
                   discount_price থাকলে সেটাই, না থাকলে price */
                $commissionBase = (float) ($course->discount_price ?? $course->price);

                /* ----- Create Course Order ----- */
                $order = CourseOrder::create([
                    'user_id'                 => $user->id,
                    'course_id'               => $course->id,
                    'order_no'                => $sessionData['order_no'] ?? ('ORD-' . strtoupper(Str::random(10))),

                    'price'                   => $course->price,
                    'discount_price'          => $course->discount_price,
                    'paid_amount'             => $verifiedAmount,

                    'payment_gateway'         => 'eps',
                    'merchant_transaction_id' => $verifiedMerchantTransactionId ?? $merchantTransactionId,
                    'eps_transaction_id'      => $epsTransactionId,
                    'gateway_transaction_id'  => $verifiedMerchantTransactionId ?? $merchantTransactionId,
                    'payment_method'          => $verifyResponse['FinancialEntity'] ?? 'eps',

                    'gateway_response' => [
                        'initialize_response' => $sessionData['gateway_response'] ?? [],
                        'success_callback'    => $request->all(),
                        'verify_response'     => $verifyResponse,
                    ],

                    'payment_status' => 'paid',
                    'status'         => 'completed',
                    'paid_at'        => now(),
                ]);

                /* ----- Transaction: Course Purchase ----- */
                Transaction::create([
                    'from_id'   => $user->id,
                    'user_id'   => $user->id,
                    'from_user' => $user->id,
                    'out'       => 'in',
                    'status'    => 'success',
                    'purpose'   => 'Course Purchase: ' . $course->title,
                    'amount'    => $verifiedAmount,
                ]);

                /* ============================================================
                   REFERRAL COMMISSION DISTRIBUTE
                   Direct 20% | 1st Gen 10% | 2nd Gen 5%
                   Commission base: discount_price (fallback: price)
                ============================================================ */
                $this->referCourseCommission($user, $commissionBase, $course, $order);

                DB::commit();

                session()->forget('course_payment_data');

                Log::info('Course Order + Commission Created', [
                    'order_id'  => $order->id,
                    'order_no'  => $order->order_no,
                    'user_id'   => $user->id,
                    'course_id' => $course->id,
                    'amount'    => $order->paid_amount,
                    'commission_base' => $commissionBase,
                ]);

                return redirect()
                    ->route('study.center.course.show', $course->slug)
                    ->with('success', '🎉 অভিনন্দন! আপনার কোর্স সফলভাবে কেনা হয়েছে।');

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }

        } catch (\Exception $e) {
            DB::rollBack();
            session()->forget('course_payment_data');

            Log::error('Course EPS Success Error', [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
                'request' => $request->all(),
            ]);

            return redirect()->route('study.center')
                ->with('error', 'Payment verification এ সমস্যা হয়েছে।');
        }
    }

   /* ============================================================
   REFERRAL COURSE COMMISSION (FIXED)
   
   Buyer নিজেই      → 20% (Cashback)
   Direct Referrer  → 10%
   1st Gen Referrer → 5%
   
    Base amount = discount_price (fallback: price)
    ============================================================ */
    public function referCourseCommission(User $user, float $baseAmount, Course $course, CourseOrder $order): bool
    {
        $buyerPercent  = 20;
        $directPercent = 10;
        $firstPercent  = 5;

        if ($baseAmount <= 0) {
            return false;
        }

        /* ===== STEP 1: Buyer Cashback (20%) ===== */
        $buyerCommission = ($baseAmount * $buyerPercent) / 100;

        if ($buyerCommission > 0) {
            $user->increment('income_wallet', $buyerCommission);

            Transaction::create([
                'from_id'   => $user->id,
                'user_id'   => $user->id,
                'from_user' => $user->id,
                'out'       => 'referral',
                'status'    => 'success',
                'purpose'   => 'Course Purchase Cashback (' . $buyerPercent . '%) - ' . $course->title,
                'amount'    => $buyerCommission,
            ]);

            Generation::create([
                'from_user_id' => $user->id,
                'to_user_id'   => $user->id,
                'level'        => 0,
                'date'         => now(),
                'status'       => 1,
                'commission'   => $buyerCommission,
                'total_amount' => $baseAmount,
            ]);
        }

        /* ===== STEP 2: Direct Referrer (10%) ===== */
        $directReferrerId = $user->refer_by;

        if (! $directReferrerId) {
            Log::info('Course Commission: No direct referrer', [
                'user_id' => $user->id,
                'order_id' => $order->id,
            ]);

            return true;
        }

        $directReferrer = User::lockForUpdate()->find($directReferrerId);

        if (! $directReferrer) {
            Log::warning('Course Commission: Direct Referrer Not Found', [
                'user_id'  => $user->id,
                'refer_id' => $directReferrerId,
            ]);

            return true;
        }

        /* Self-commission protection */
        if ($directReferrer->id === $user->id) {
            Log::warning('Course Commission: Direct Referrer = Buyer (skipped)', [
                'user_id' => $user->id,
            ]);

            return true;
        }

        $directCommission = ($baseAmount * $directPercent) / 100;

        if ($directCommission > 0) {
            $directReferrer->increment('income_wallet', $directCommission);

            Transaction::create([
                'from_id'   => $user->id,
                'user_id'   => $directReferrer->id,
                'from_user' => $user->id,
                'out'       => 'referral',
                'status'    => 'success',
                'purpose'   => 'Direct Referral Course Commission (' . $directPercent . '%) - ' . $course->title,
                'amount'    => $directCommission,
            ]);

            Generation::create([
                'from_user_id' => $user->id,
                'to_user_id'   => $directReferrer->id,
                'level'        => 1,
                'date'         => now(),
                'status'       => 1,
                'commission'   => $directCommission,
                'total_amount' => $baseAmount,
            ]);
        }

        /* ===== STEP 3: 1st Generation Referrer (5%) ===== */
        $firstGenReferrerId = $directReferrer->refer_by;

        if (! $firstGenReferrerId) {
            return true;
        }

        $firstGenReferrer = User::lockForUpdate()->find($firstGenReferrerId);

        if (! $firstGenReferrer) {
            return true;
        }

        /* Self-commission protection */
        if (
            $firstGenReferrer->id === $user->id ||
            $firstGenReferrer->id === $directReferrer->id
        ) {
            return true;
        }

        $firstCommission = ($baseAmount * $firstPercent) / 100;

        if ($firstCommission > 0) {
            $firstGenReferrer->increment('income_wallet', $firstCommission);

            Transaction::create([
                'from_id'   => $user->id,
                'user_id'   => $firstGenReferrer->id,
                'from_user' => $user->id,
                'out'       => 'referral',
                'status'    => 'success',
                'purpose'   => '1st Generation Course Commission (' . $firstPercent . '%) - ' . $course->title,
                'amount'    => $firstCommission,
            ]);

            Generation::create([
                'from_user_id' => $user->id,
                'to_user_id'   => $firstGenReferrer->id,
                'level'        => 2,
                'date'         => now(),
                'status'       => 1,
                'commission'   => $firstCommission,
                'total_amount' => $baseAmount,
            ]);
        }

        /* ===== Log ===== */
        Log::info('Course Commission Distributed', [
            'buyer_id'          => $user->id,
            'order_id'          => $order->id,
            'course_id'         => $course->id,
            'base_amount'       => $baseAmount,
            'buyer_amount'      => $buyerCommission,
            'direct_referrer'   => $directReferrer->id,
            'direct_amount'     => $directCommission,
            'first_gen_id'      => $firstGenReferrer->id,
            'first_amount'      => $firstCommission,
        ]);

        return true;
    }

    /* ============================================================
       PAYMENT FAIL — No Order
    ============================================================ */
    public function paymentFail(Request $request)
    {
        Log::info('Course EPS Fail Callback', $request->all());
        session()->forget('course_payment_data');

        return redirect()->route('user.study.center')
            ->with('error', '❌ Payment ব্যর্থ হয়েছে। টাকা কাটা হয়নি। আবার চেষ্টা করুন।');
    }

    /* ============================================================
       PAYMENT CANCEL — No Order
    ============================================================ */
    public function paymentCancel(Request $request)
    {
        Log::info('Course EPS Cancel Callback', $request->all());
        session()->forget('course_payment_data');

        return redirect()->route('user.study.center')
            ->with('error', 'Payment বাতিল করা হয়েছে। টাকা কাটা হয়নি।');
    }

    /* ============================================================
       MY COURSES
    ============================================================ */
    public function myCourses()
    {
        $pageTitle = 'আমার কোর্সসমূহ';

        $orders = CourseOrder::with(['course.courseType', 'course.courseClass'])
                             ->where('user_id', Auth::id())
                             ->where('payment_status', 'paid')
                             ->latest()
                             ->get();

        return view('user.study.center.my-courses', compact('pageTitle', 'orders'));
    }

    /* ============================================================
       VOUCHER
    ============================================================ */
    public function voucher(string $order_no)
    {
        $order = CourseOrder::with(['course.courseType', 'course.courseClass'])
                            ->where('user_id', Auth::id())
                            ->where('order_no', $order_no)
                            ->where('payment_status', 'paid')
                            ->firstOrFail();

        $pageTitle = 'কোর্স ভাউচার';

        return view('user.study.center.voucher', compact('pageTitle', 'order'));
    }
}