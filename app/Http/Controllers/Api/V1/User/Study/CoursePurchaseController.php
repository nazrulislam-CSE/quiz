<?php

namespace App\Http\Controllers\Api\V1\User\Study;

use App\EPS\EPSPayment;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseOrder;
use App\Models\Generation;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CoursePurchaseController extends Controller
{
    /* ============================================================
       BUY — Initialize EPS Payment
    ============================================================ */
    public function buy(Request $request, Course $course): JsonResponse
    {
        $user = Auth::user();

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
            ], 401);
        }

        if ($course->status !== 'active') {
            return response()->json([
                'success' => false,
                'message' => 'This course is not available now.',
            ], 400);
        }

        if ($course->is_free) {
            return response()->json([
                'success' => false,
                'message' => 'This is a free course. No purchase required.',
            ], 400);
        }

        // Already owned?
        $alreadyOwned = CourseOrder::where('user_id', $user->id)
                                   ->where('course_id', $course->id)
                                   ->where('payment_status', 'paid')
                                   ->exists();

        if ($alreadyOwned) {
            return response()->json([
                'success' => false,
                'message' => 'You already own this course.',
            ], 400);
        }

        $amount = (float) $course->final_price;

        if ($amount <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid payment amount.',
            ], 400);
        }

        try {
            $customerOrderId       = 'CRS_' . now()->format('YmdHis') . '_' . rand(1000, 9999);
            $merchantTransactionId = 'EPS' . now()->format('YmdHis') . rand(10000, 99999);
            $orderNo               = 'ORD-' . strtoupper(Str::random(10));

            // Save in session for callback
            session([
                'api_course_payment_data' => [
                    'user_id'                 => $user->id,
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
                'successUrl'         => route('study.center.payment.success'),
                'failUrl'            => route('study.center.payment.fail'),
                'cancelUrl'          => route('study.center.payment.cancel'),
                'customerName'       => $user->name ?? '',
                'customerEmail'      => $user->email ?? '',
                'customerAddress'    => $user->address ?? '',
                'customerAddress2'   => '',
                'customerCity'       => $user->city ?? 'Dhaka',
                'customerState'      => $user->state ?? 'Dhaka',
                'customerPostcode'   => $user->postcode ?? '1000',
                'customerCountry'    => 'BD',
                'customerPhone'      => $user->phone ?? '01700000000',
                'shipmentName'       => $user->name ?? '',
                'shipmentAddress'    => $user->address ?? '',
                'shipmentAddress2'   => '',
                'shipmentCity'       => $user->city ?? 'Dhaka',
                'shipmentState'      => $user->state ?? 'Dhaka',
                'shipmentPostcode'   => $user->postcode ?? '1000',
                'shipmentCountry'    => 'BD',
                'valueA'             => (string) $user->id,
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
                session()->forget('api_course_payment_data');
                Log::error('API Course EPS Init Failed', ['data' => $data]);

                return response()->json([
                    'success' => false,
                    'message' => 'Payment gateway returned no response.',
                    'data'    => $data,
                ], 502);
            }

            $paymentUrl = $data['RedirectURL'] ?? $data['payment_url'] ?? null;

            if (! $paymentUrl) {
                session()->forget('api_course_payment_data');
                Log::error('API Course EPS URL Missing', ['response' => $data]);

                return response()->json([
                    'success' => false,
                    'message' => 'Payment URL not found.',
                ], 502);
            }

            session([
                'api_course_payment_data' => array_merge(
                    session('api_course_payment_data', []),
                    [
                        'eps_transaction_id'     => $data['TransactionId'] ?? null,
                        'gateway_transaction_id' => $data['MerchantTransactionId'] ?? null,
                        'gateway_response'       => $data,
                        'payment_url'            => $paymentUrl,
                    ]
                ),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Payment initialized. Open payment_url in webview.',
                'data'    => [
                    'order_no'                => $orderNo,
                    'merchant_transaction_id' => $merchantTransactionId,
                    'customer_order_id'       => $customerOrderId,
                    'amount'                  => $amount,
                    'payment_url'             => $paymentUrl,
                    'course'                  => [
                        'id'    => $course->id,
                        'title' => $course->title,
                        'slug'  => $course->slug,
                    ],
                ],
            ], 200);

        } catch (\Exception $e) {
            session()->forget('api_course_payment_data');
            Log::error('API Course EPS Initialize Error', [
                'message' => $e->getMessage(),
                'user_id' => $user->id,
                'course_id' => $course->id,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Payment processing error.',
            ], 500);
        }
    }

    /* ============================================================
       PAYMENT SUCCESS — Verify + Order Create + Commission
       EPS hits this URL — returns HTML page (webview will show)
    ============================================================ */
    public function paymentSuccess(Request $request)
    {
        try {
            Log::info('API Course EPS Success Callback', ['request' => $request->all()]);

            $sessionData = session('api_course_payment_data', []);

            $merchantTransactionId =
                $request->merchant_transaction_id ??
                $request->MerchantTransactionId ??
                $request->merchantTransactionId ??
                $sessionData['merchant_transaction_id'] ??
                null;

            if (! $merchantTransactionId) {
                session()->forget('api_course_payment_data');
                return $this->renderCallback('error', 'Transaction information not found.');
            }

            // Duplicate check
            $existingOrder = CourseOrder::where('merchant_transaction_id', $merchantTransactionId)->first();
            if ($existingOrder) {
                session()->forget('api_course_payment_data');
                return $this->renderCallback('success', 'This payment has already been processed.', $existingOrder);
            }

            // EPS Verify
            $epsPayment = new EPSPayment;
            $verifyResponse = $epsPayment->checkPaymentStatus($merchantTransactionId);

            Log::info('API Course EPS Verify Response', [
                'merchant_transaction_id' => $merchantTransactionId,
                'verify_response'         => $verifyResponse,
            ]);

            if (! $verifyResponse || ! isset($verifyResponse['isSuccess']) || $verifyResponse['isSuccess'] !== true) {
                session()->forget('api_course_payment_data');
                return $this->renderCallback('error', 'Payment verify failed.');
            }

            $epsStatus = strtolower(trim($verifyResponse['Status'] ?? ''));
            if ($epsStatus !== 'success') {
                session()->forget('api_course_payment_data');
                return $this->renderCallback('error', 'Payment was not successful. Status: ' . ($verifyResponse['Status'] ?? 'Unknown'));
            }

            $verifiedMerchantTransactionId = $verifyResponse['MerchantTransactionId'] ?? null;
            if ($verifiedMerchantTransactionId && $verifiedMerchantTransactionId !== $merchantTransactionId) {
                session()->forget('api_course_payment_data');
                return $this->renderCallback('error', 'Transaction ID verification failed.');
            }

            $originalAmount = (float) ($sessionData['amount'] ?? 0);
            $verifiedAmount = (float) ($verifyResponse['TotalAmount'] ?? 0);

            if ($originalAmount > 0 && abs($originalAmount - $verifiedAmount) > 0.01) {
                session()->forget('api_course_payment_data');
                return $this->renderCallback('error', 'Payment amount verification failed.');
            }

            $epsTransactionId =
                $verifyResponse['EpsTransactionId'] ??
                $verifyResponse['EPSTransactionId'] ??
                null;

            DB::beginTransaction();

            try {
                $user   = User::findOrFail($sessionData['user_id'] ?? null);
                $course = Course::findOrFail($sessionData['course_id']);

                $commissionBase = (float) ($course->discount_price ?? $course->price);

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

                Transaction::create([
                    'from_id'   => $user->id,
                    'user_id'   => $user->id,
                    'from_user' => $user->id,
                    'out'       => 'in',
                    'status'    => 'success',
                    'purpose'   => 'Course Purchase: ' . $course->title,
                    'amount'    => $verifiedAmount,
                ]);

                $this->referCourseCommission($user, $commissionBase, $course, $order);

                DB::commit();

                session()->forget('api_course_payment_data');

                Log::info('API Course Order Created', [
                    'order_id' => $order->id,
                    'order_no' => $order->order_no,
                    'user_id'  => $user->id,
                    'amount'   => $order->paid_amount,
                ]);

                return $this->renderCallback('success', '🎉 আপনার কোর্স সফলভাবে কেনা হয়েছে।', $order);

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }

        } catch (\Exception $e) {
            DB::rollBack();
            session()->forget('api_course_payment_data');

            Log::error('API Course EPS Success Error', [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);

            return $this->renderCallback('error', 'Payment verification error.');
        }
    }

    /* ============================================================
       PAYMENT FAIL / CANCEL
    ============================================================ */
    public function paymentFail(Request $request)
    {
        Log::info('API Course EPS Fail Callback', $request->all());
        session()->forget('api_course_payment_data');
        return $this->renderCallback('error', '❌ Payment ব্যর্থ হয়েছে। টাকা কাটা হয়নি।');
    }

    public function paymentCancel(Request $request)
    {
        Log::info('API Course EPS Cancel Callback', $request->all());
        session()->forget('api_course_payment_data');
        return $this->renderCallback('error', 'Payment বাতিল করা হয়েছে।');
    }

    /* ============================================================
       MY COURSES — API
    ============================================================ */
    public function myCourses(): JsonResponse
    {
        $user = Auth::user();

        $orders = CourseOrder::with(['course.courseType', 'course.courseClass', 'course.courseCategory'])
                             ->where('user_id', $user->id)
                             ->where('payment_status', 'paid')
                             ->latest()
                             ->get()
                             ->map(function ($order) {
                                 return [
                                     'order_id'    => $order->id,
                                     'order_no'    => $order->order_no,
                                     'paid_amount' => (float) $order->paid_amount,
                                     'paid_at'     => $order->paid_at?->toDateTimeString(),
                                     'course'      => $order->course ? [
                                         'id'             => $order->course->id,
                                         'title'          => $order->course->title,
                                         'slug'           => $order->course->slug,
                                         'thumbnail'      => $order->course->thumbnail
                                                                ? asset('storage/' . $order->course->thumbnail)
                                                                : 'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?w=600&q=80',
                                         'course_type'    => $order->course->courseType->name ?? null,
                                         'course_class'   => $order->course->courseClass->name ?? null,
                                         'course_category' => $order->course->courseCategory->name ?? null,
                                     ] : null,
                                 ];
                             });

        return response()->json([
            'success' => true,
            'message' => 'My courses retrieved successfully.',
            'data'    => $orders,
        ], 200);
    }

    /* ============================================================
       ORDER DETAILS — API
    ============================================================ */
    public function orderDetails(string $id): JsonResponse
    {
        $user = Auth::user();

        $order = CourseOrder::with(['course.courseType', 'course.courseClass', 'course.courseCategory'])
                            ->where('user_id', $user->id)
                            ->where('id', $id)
                            ->first();

        if (! $order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Order details retrieved.',
            'data'    => [
                'order_id'      => $order->id,
                'order_no'      => $order->order_no,
                'paid_amount'   => (float) $order->paid_amount,
                'price'         => (float) $order->price,
                'discount'      => (float) $order->discount_price,
                'payment_status' => $order->payment_status,
                'payment_method' => $order->payment_method,
                'merchant_transaction_id' => $order->merchant_transaction_id,
                'eps_transaction_id'      => $order->eps_transaction_id,
                'paid_at'       => $order->paid_at?->toDateTimeString(),
                'course'        => $order->course,
            ],
        ], 200);
    }

    /* ============================================================
       REFERRAL COURSE COMMISSION
       Buyer → 20% | Direct → 10% | 1st Gen → 5%
    ============================================================ */
    public function referCourseCommission(User $user, float $baseAmount, Course $course, CourseOrder $order): bool
    {
        $buyerPercent  = 20;
        $directPercent = 10;
        $firstPercent  = 5;

        if ($baseAmount <= 0) {
            return false;
        }

        /* ===== Buyer Cashback (20%) ===== */
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

        /* ===== Direct Referrer (10%) ===== */
        $directReferrerId = $user->refer_by;
        if (! $directReferrerId) {
            return true;
        }

        $directReferrer = User::lockForUpdate()->find($directReferrerId);
        if (! $directReferrer || $directReferrer->id === $user->id) {
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

        /* ===== 1st Generation (5%) ===== */
        $firstGenReferrerId = $directReferrer->refer_by;
        if (! $firstGenReferrerId) {
            return true;
        }

        $firstGenReferrer = User::lockForUpdate()->find($firstGenReferrerId);
        if (! $firstGenReferrer
            || $firstGenReferrer->id === $user->id
            || $firstGenReferrer->id === $directReferrer->id
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

        return true;
    }

    /* ============================================================
       HTML CALLBACK PAGE (for EPS webview)
    ============================================================ */
    private function renderCallback(string $type, string $message, $order = null)
    {
        $isSuccess = $type === 'success';
        $bgColor   = $isSuccess ? '#10b981' : '#ef4444';
        $icon      = $isSuccess ? '✅' : '❌';
        $title     = $isSuccess ? 'Payment Successful' : 'Payment Failed';

        $html = '<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>' . $title . '</title>
</head>
<body style="margin:0;padding:0;font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',Roboto,sans-serif;background:#f1f5f9;min-height:100vh;display:flex;align-items:center;justify-content:center;padding:20px;">

    <div style="background:#fff;border-radius:16px;box-shadow:0 10px 40px rgba(0,0,0,.1);max-width:400px;width:100%;padding:32px 24px;text-align:center;">

        <div style="width:80px;height:80px;margin:0 auto 20px;background:' . $bgColor . ';border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:40px;">
            ' . $icon . '
        </div>

        <h2 style="margin:0 0 10px;color:#1e293b;font-size:20px;font-weight:700;">' . $title . '</h2>
        <p style="margin:0 0 24px;color:#64748b;font-size:14px;line-height:1.6;">' . htmlspecialchars($message) . '</p>';

        if ($order) {
            $html .= '
        <div style="background:#f8fafc;border-radius:10px;padding:14px;margin-bottom:20px;text-align:left;">
            <div style="display:flex;justify-content:space-between;padding:6px 0;font-size:13px;">
                <span style="color:#64748b;">Order No</span>
                <span style="color:#1e293b;font-weight:600;">' . htmlspecialchars($order->order_no) . '</span>
            </div>
            <div style="display:flex;justify-content:space-between;padding:6px 0;font-size:13px;border-top:1px dashed #e2e8f0;">
                <span style="color:#64748b;">Amount</span>
                <span style="color:#1e293b;font-weight:600;">৳' . number_format($order->paid_amount, 0) . '</span>
            </div>
        </div>';
        }

        $html .= '
        <p style="margin:0;color:#94a3b8;font-size:12px;">You can close this window now.</p>

    </div>

</body>
</html>';

        return response($html, 200);
    }
}