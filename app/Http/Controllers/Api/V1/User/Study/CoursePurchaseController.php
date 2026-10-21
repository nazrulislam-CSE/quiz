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
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class CoursePurchaseController extends Controller
{
    /* ============================================================
       CACHE CONFIG — Payment data survives across requests
    ============================================================ */
    private const CACHE_PREFIX    = 'course_payment_';
    private const CACHE_TTL_HOURS = 2;

    /* ============================================================
       BUY — Initialize EPS Payment (Self + Gift)
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

        /* ===== VALIDATION ===== */
        $validated = $request->validate([
            'purchase_for' => 'required|in:self,other',
            'student_id'   => 'nullable|required_if:purchase_for,other|exists:users,id',
        ], [
            'student_id.required_if' => 'অনুগ্রহ করে একজন Student select করুন।',
            'student_id.exists'      => 'Selected student পাওয়া যায়নি।',
        ]);

        if ($course->status !== 'active') {
            return response()->json(['success' => false, 'message' => 'This course is not available now.'], 400);
        }
        if ($course->is_free) {
            return response()->json(['success' => false, 'message' => 'This is a free course. No purchase required.'], 400);
        }

        $isGift = $validated['purchase_for'] === 'other';

        /* ===== DETERMINE STUDENT ===== */
        $studentId    = null;
        $studentName  = null;
        $studentEmail = null;
        $studentPhone = null;

        if (! $isGift) {
            $studentId    = $user->id;
            $studentName  = $user->full_name;
            $studentEmail = $user->email;
            $studentPhone = $user->phone;
        } else {
            $studentUser = User::where('id', $validated['student_id'])
                               ->where('status', '1')
                               ->first();

            if (! $studentUser) {
                return response()->json(['success' => false, 'message' => 'Selected student পাওয়া যায়নি।'], 404);
            }
            if ($studentUser->id === $user->id) {
                return response()->json(['success' => false, 'message' => 'নিজেকে gift করা যাবে না।'], 400);
            }

            $studentId    = $studentUser->id;
            $studentName  = $studentUser->full_name;
            $studentEmail = $studentUser->email;
            $studentPhone = $studentUser->phone;
        }

        /* ===== DUPLICATE BLOCK (student_id based) ===== */
        $duplicate = CourseOrder::where('course_id', $course->id)
            ->where('student_id', $studentId)
            ->where('payment_status', 'paid')
            ->exists();

        if ($duplicate) {
            return response()->json([
                'success' => false,
                'message' => $isGift
                    ? 'এই student ইতিমধ্যে এই কোর্সটি পেয়েছেন।'
                    : 'আপনি ইতিমধ্যে এই কোর্সটি কিনেছেন।',
            ], 400);
        }

        $amount = (float) $course->final_price;

        if ($amount <= 0) {
            return response()->json(['success' => false, 'message' => 'Invalid payment amount.'], 400);
        }

        $merchantTransactionId = null;
        $cacheKey = null;

        try {
            $customerOrderId       = 'CRS_' . now()->format('YmdHis') . '_' . rand(1000, 9999);
            $merchantTransactionId = 'EPS' . now()->format('YmdHis') . rand(10000, 99999);
            $orderNo               = 'ORD-' . strtoupper(Str::random(10));
            $cacheKey              = self::CACHE_PREFIX . $merchantTransactionId;

            /* ===== CACHE — persists across requests (no session needed) ===== */
            Cache::put($cacheKey, [
                'user_id'                 => $user->id,
                'student_id'              => $studentId,
                'student_name'            => $studentName,
                'student_email'           => $studentEmail,
                'student_phone'           => $studentPhone,
                'is_gift'                 => $isGift,
                'course_id'               => $course->id,
                'amount'                  => $amount,
                'order_no'                => $orderNo,
                'customer_order_id'       => $customerOrderId,
                'merchant_transaction_id' => $merchantTransactionId,
            ], now()->addHours(self::CACHE_TTL_HOURS));

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
                'customerName'       => $user->full_name ?? '',
                'customerEmail'      => $user->email ?? '',
                'customerAddress'    => $user->address ?? '',
                'customerAddress2'   => '',
                'customerCity'       => $user->city ?? 'Dhaka',
                'customerState'      => $user->state ?? 'Dhaka',
                'customerPostcode'   => $user->postcode ?? '1000',
                'customerCountry'    => 'BD',
                'customerPhone'      => $user->phone ?? '01700000000',
                'shipmentName'       => $user->full_name ?? '',
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
                Cache::forget($cacheKey);
                Log::error('API Course EPS Init Failed', ['data' => $data]);

                return response()->json([
                    'success' => false,
                    'message' => 'Payment gateway returned no response.',
                    'data'    => $data,
                ], 502);
            }

            $paymentUrl = $data['RedirectURL'] ?? $data['payment_url'] ?? null;

            if (! $paymentUrl) {
                Cache::forget($cacheKey);
                Log::error('API Course EPS URL Missing', ['response' => $data]);

                return response()->json([
                    'success' => false,
                    'message' => 'Payment URL not found.',
                ], 502);
            }

            /* ===== Update cache with EPS response details ===== */
            $cachedData = Cache::get($cacheKey, []);
            $cachedData['eps_transaction_id']     = $data['TransactionId'] ?? null;
            $cachedData['gateway_transaction_id'] = $data['MerchantTransactionId'] ?? null;
            $cachedData['gateway_response']       = $data;
            $cachedData['payment_url']            = $paymentUrl;

            Cache::put($cacheKey, $cachedData, now()->addHours(self::CACHE_TTL_HOURS));

            return response()->json([
                'success' => true,
                'message' => 'Payment initialized. Open payment_url in webview.',
                'data'    => [
                    'order_no'                => $orderNo,
                    'merchant_transaction_id' => $merchantTransactionId,
                    'customer_order_id'       => $customerOrderId,
                    'amount'                  => $amount,
                    'is_gift'                 => $isGift,
                    'student'                 => $isGift ? [
                        'id'        => $studentId,
                        'full_name' => $studentName,
                        'email'     => $studentEmail,
                        'phone'     => $studentPhone,
                    ] : null,
                    'payment_url'             => $paymentUrl,
                    'course'                  => [
                        'id'    => $course->id,
                        'title' => $course->title,
                        'slug'  => $course->slug,
                    ],
                ],
            ], 200);

        } catch (\Exception $e) {
            if ($cacheKey) {
                Cache::forget($cacheKey);
            }

            Log::error('API Course EPS Initialize Error', [
                'message'   => $e->getMessage(),
                'user_id'   => $user->id,
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
       ✅ Uses cache + EPS verify fallback
    ============================================================ */
    public function paymentSuccess(Request $request)
    {
        $merchantTransactionId = null;

        try {
            Log::info('API Course EPS Success Callback', ['request' => $request->all()]);

            $merchantTransactionId =
                $request->merchant_transaction_id ??
                $request->MerchantTransactionId ??
                $request->merchantTransactionId ??
                null;

            if (! $merchantTransactionId) {
                return $this->renderCallback('error', 'Transaction information not found.');
            }

            /* ===== DUPLICATE CHECK ===== */
            $existingOrder = CourseOrder::where('merchant_transaction_id', $merchantTransactionId)->first();
            if ($existingOrder) {
                Cache::forget(self::CACHE_PREFIX . $merchantTransactionId);
                return $this->renderCallback('success', 'This payment has already been processed.', $existingOrder);
            }

            /* ===== EPS VERIFY ===== */
            $epsPayment = new EPSPayment;
            $verifyResponse = $epsPayment->checkPaymentStatus($merchantTransactionId);

            Log::info('API Course EPS Verify Response', [
                'merchant_transaction_id' => $merchantTransactionId,
                'verify_response'         => $verifyResponse,
            ]);

            if (! $verifyResponse || ! isset($verifyResponse['isSuccess']) || $verifyResponse['isSuccess'] !== true) {
                Cache::forget(self::CACHE_PREFIX . $merchantTransactionId);
                return $this->renderCallback('error', 'Payment verify failed.');
            }

            $epsStatus = strtolower(trim($verifyResponse['Status'] ?? ''));
            if ($epsStatus !== 'success') {
                Cache::forget(self::CACHE_PREFIX . $merchantTransactionId);
                return $this->renderCallback('error', 'Payment was not successful. Status: ' . ($verifyResponse['Status'] ?? 'Unknown'));
            }

            $verifiedMerchantTransactionId = $verifyResponse['MerchantTransactionId'] ?? null;
            if ($verifiedMerchantTransactionId && $verifiedMerchantTransactionId !== $merchantTransactionId) {
                Cache::forget(self::CACHE_PREFIX . $merchantTransactionId);
                return $this->renderCallback('error', 'Transaction ID verification failed.');
            }

            $verifiedAmount = (float) ($verifyResponse['TotalAmount'] ?? 0);

            /* ===== CACHE + FALLBACK DATA ===== */
            $cachedData = Cache::get(self::CACHE_PREFIX . $merchantTransactionId, []);

            $fallbackUserId   = null;
            $fallbackCourseId = null;

            if (empty($cachedData)) {
                Log::warning('Cache miss — using EPS verify fallback', [
                    'merchant_transaction_id' => $merchantTransactionId,
                ]);

                if (! empty($verifyResponse['ValueA'])) {
                    $fallbackUserId = (int) $verifyResponse['ValueA'];
                }

                if (! empty($verifyResponse['ValueC']) && preg_match('/course_(\d+)/', $verifyResponse['ValueC'], $m)) {
                    $fallbackCourseId = (int) $m[1];
                }
            }

            $userId   = $cachedData['user_id']   ?? $fallbackUserId;
            $courseId = $cachedData['course_id'] ?? $fallbackCourseId;

            if (! $userId || ! $courseId) {
                Cache::forget(self::CACHE_PREFIX . $merchantTransactionId);
                Log::error('Cannot determine user/course for payment', [
                    'merchant_transaction_id' => $merchantTransactionId,
                    'cached_data'             => $cachedData,
                    'verify_response'         => $verifyResponse,
                ]);

                return $this->renderCallback(
                    'error',
                    'Payment data unavailable. Txn ID: ' . $merchantTransactionId . ' — please contact support.'
                );
            }

            /* ===== AMOUNT CHECK (only if cached amount exists) ===== */
            $originalAmount = (float) ($cachedData['amount'] ?? 0);
            if ($originalAmount > 0 && abs($originalAmount - $verifiedAmount) > 0.01) {
                Cache::forget(self::CACHE_PREFIX . $merchantTransactionId);
                return $this->renderCallback('error', 'Payment amount verification failed.');
            }

            $epsTransactionId = $verifyResponse['EpsTransactionId'] ?? $verifyResponse['EPSTransactionId'] ?? null;

            /* ===== DB TRANSACTION ===== */
            DB::beginTransaction();

            try {
                $buyer  = User::findOrFail($userId);
                $course = Course::findOrFail($courseId);

                $commissionBase = (float) ($course->discount_price ?? $course->price);

                $studentId    = $cachedData['student_id']    ?? $buyer->id;
                $studentName  = $cachedData['student_name']  ?? $buyer->full_name;
                $studentEmail = $cachedData['student_email'] ?? $buyer->email;
                $studentPhone = $cachedData['student_phone'] ?? $buyer->phone;
                $isGift       = (bool) ($cachedData['is_gift'] ?? false);

                $order = CourseOrder::create([
                    'user_id'                 => $buyer->id,
                    'student_id'              => $studentId,
                    'course_id'               => $course->id,
                    'order_no'                => $cachedData['order_no'] ?? ('ORD-' . strtoupper(Str::random(10))),
                    'is_gift'                 => $isGift,

                    'buyer_name'              => $buyer->full_name,
                    'buyer_email'             => $buyer->email,
                    'buyer_phone'             => $buyer->phone,

                    'student_name'            => $studentName,
                    'student_email'           => $studentEmail,
                    'student_phone'           => $studentPhone,

                    'price'                   => $course->price,
                    'discount_price'          => $course->discount_price,
                    'paid_amount'             => $verifiedAmount,

                    'payment_gateway'         => 'eps',
                    'merchant_transaction_id' => $verifiedMerchantTransactionId ?? $merchantTransactionId,
                    'eps_transaction_id'      => $epsTransactionId,
                    'gateway_transaction_id'  => $verifiedMerchantTransactionId ?? $merchantTransactionId,
                    'payment_method'          => $verifyResponse['FinancialEntity'] ?? 'eps',

                    'gateway_response' => [
                        'initialize_response' => $cachedData['gateway_response'] ?? [],
                        'success_callback'    => $request->all(),
                        'verify_response'     => $verifyResponse,
                        'cache_hit'           => ! empty($cachedData),
                    ],

                    'payment_status' => 'paid',
                    'status'         => 'completed',
                    'paid_at'        => now(),
                ]);

                Transaction::create([
                    'from_id'   => $buyer->id,
                    'user_id'   => $buyer->id,
                    'from_user' => $buyer->id,
                    'out'       => 'in',
                    'status'    => 'success',
                    'purpose'   => 'Course Purchase: ' . $course->title,
                    'amount'    => $verifiedAmount,
                ]);

                $this->referCourseCommission($buyer, $commissionBase, $course, $order);

                DB::commit();
                Cache::forget(self::CACHE_PREFIX . $merchantTransactionId);

                Log::info('API Course Order Created', [
                    'order_id'  => $order->id,
                    'order_no'  => $order->order_no,
                    'user_id'   => $buyer->id,
                    'amount'    => $order->paid_amount,
                    'cache_hit' => ! empty($cachedData),
                ]);

                $msg = $isGift
                    ? '🎉 ' . $studentName . '-এর জন্য কোর্স সফলভাবে কেনা হয়েছে।'
                    : '🎉 আপনার কোর্স সফলভাবে কেনা হয়েছে।';

                return $this->renderCallback('success', $msg, $order);

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }

        } catch (\Exception $e) {
            DB::rollBack();

            if ($merchantTransactionId) {
                // DO NOT forget cache here — keep it for retry
                // Cache::forget(self::CACHE_PREFIX . $merchantTransactionId);
            }

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

        $txnId = $request->MerchantTransactionId
              ?? $request->merchant_transaction_id
              ?? $request->merchantTransactionId
              ?? null;

        if ($txnId) {
            Cache::forget(self::CACHE_PREFIX . $txnId);
        }

        return $this->renderCallback('error', '❌ Payment ব্যর্থ হয়েছে। টাকা কাটা হয়নি।');
    }

    public function paymentCancel(Request $request)
    {
        Log::info('API Course EPS Cancel Callback', $request->all());

        $txnId = $request->MerchantTransactionId
              ?? $request->merchant_transaction_id
              ?? $request->merchantTransactionId
              ?? null;

        if ($txnId) {
            Cache::forget(self::CACHE_PREFIX . $txnId);
        }

        return $this->renderCallback('error', 'Payment বাতিল করা হয়েছে।');
    }

    /* ============================================================
       MY COURSES — API (My + Gifted)
    ============================================================ */
    public function myCourses(): JsonResponse
    {
        $user   = Auth::user();
        $userId = $user->id;

        $myOrders = CourseOrder::with([
            'course.courseType', 'course.courseClass', 'course.courseCategory', 'user'
        ])
        ->where(function ($q) use ($userId) {
            $q->where('student_id', $userId)
              ->orWhere(function ($q2) use ($userId) {
                  $q2->whereNull('student_id')->where('user_id', $userId);
              });
        })
        ->where('payment_status', 'paid')
        ->latest()
        ->get()
        ->map(fn ($o) => $this->transformOrder($o));

        $giftOrders = CourseOrder::with([
            'course.courseType', 'course.courseClass', 'course.courseCategory', 'student'
        ])
        ->where('user_id', $userId)
        ->where('is_gift', true)
        ->where('payment_status', 'paid')
        ->latest()
        ->get()
        ->map(fn ($o) => $this->transformOrder($o, true));

        return response()->json([
            'success'      => true,
            'message'      => 'My courses retrieved successfully.',
            'data'         => $myOrders,
            'gift_courses' => $giftOrders,
            'meta' => [
                'my_courses_count'   => $myOrders->count(),
                'gift_courses_count' => $giftOrders->count(),
            ],
        ], 200);
    }

    /* ============================================================
       ORDER DETAILS — API
    ============================================================ */
    public function orderDetails(string $order_no): JsonResponse
    {
        $user   = Auth::user();
        $userId = $user->id;

        $order = CourseOrder::with([
            'course.courseType', 'course.courseClass', 'course.courseCategory',
            'user', 'student'
        ])
        ->where('order_no', $order_no)
        ->where('payment_status', 'paid')
        ->where(function ($q) use ($userId) {
            $q->where('user_id', $userId)
              ->orWhere('student_id', $userId);
        })
        ->first();

        if (! $order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found.',
                'data'    => null,
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Order details retrieved successfully.',
            'data'    => $this->transformOrder($order, true),
        ], 200);
    }

    /* ============================================================
       VOUCHER — API (JSON + Web URL for print)
    ============================================================ */
    public function voucher(string $order_no): JsonResponse
    {
        $user   = Auth::user();
        $userId = $user->id;

        $order = CourseOrder::with([
            'course.courseType', 'course.courseClass', 'course.courseCategory',
            'user', 'student'
        ])
        ->where('order_no', $order_no)
        ->where('payment_status', 'paid')
        ->where(function ($q) use ($userId) {
            $q->where('user_id', $userId)
              ->orWhere('student_id', $userId);
        })
        ->first();

        if (! $order) {
            return response()->json([
                'success' => false,
                'message' => 'Voucher not found or payment is not completed.',
                'data'    => null,
            ], 404);
        }

        $voucherWebUrl = URL::temporarySignedRoute(
            'voucher.public',
            now()->addDays(30),
            ['order_no' => $order->order_no]
        );

        return response()->json([
            'success' => true,
            'message' => 'Course voucher retrieved successfully.',
            'data' => [
                'voucher_url'     => $voucherWebUrl,
                'voucher_expires' => now()->addDays(30)->toDateTimeString(),

                'voucher' => [
                    'voucher_no' => $order->order_no,
                    'issued_at'  => $order->paid_at?->toDateTimeString(),
                    'status'     => 'paid',
                    'is_gift'    => (bool) $order->is_gift,
                ],

                'payment' => [
                    'order_id'                => $order->id,
                    'order_no'                => $order->order_no,
                    'price'                   => (float) $order->price,
                    'discount'                => $order->discount_price !== null ? (float) $order->discount_price : null,
                    'paid_amount'             => (float) $order->paid_amount,
                    'payment_status'          => $order->payment_status,
                    'payment_method'          => $order->payment_method,
                    'merchant_transaction_id' => $order->merchant_transaction_id,
                    'eps_transaction_id'      => $order->eps_transaction_id,
                    'paid_at'                 => $order->paid_at?->toDateTimeString(),
                ],

                'buyer' => [
                    'id'        => $order->user_id,
                    'full_name' => $order->buyer_name ?? $order->user->full_name ?? null,
                    'email'     => $order->buyer_email ?? $order->user->email ?? null,
                    'phone'     => $order->buyer_phone ?? $order->user->phone ?? null,
                ],

                'student' => [
                    'id'        => $order->student_id,
                    'full_name' => $order->student_name ?? $order->student->full_name ?? null,
                    'email'     => $order->student_email ?? $order->student->email ?? null,
                    'phone'     => $order->student_phone ?? $order->student->phone ?? null,
                ],

                'course' => $this->transformCourse($order->course),
            ],
        ], 200);
    }

    /* ============================================================
       TRANSFORMERS
    ============================================================ */
    private function transformOrder(CourseOrder $order, bool $full = false): array
    {
        $course = $order->course;

        $base = [
            'order_id'    => $order->id,
            'order_no'    => $order->order_no,
            'paid_amount' => (float) $order->paid_amount,
            'paid_at'     => $order->paid_at?->toDateTimeString(),
            'is_gift'     => (bool) $order->is_gift,

            'buyer' => [
                'id'        => $order->user_id,
                'full_name' => $order->buyer_name ?? $order->user->full_name ?? null,
                'email'     => $order->buyer_email ?? $order->user->email ?? null,
                'phone'     => $order->buyer_phone ?? $order->user->phone ?? null,
            ],

            'student' => [
                'id'        => $order->student_id,
                'full_name' => $order->student_name ?? $order->student->full_name ?? null,
                'email'     => $order->student_email ?? $order->student->email ?? null,
                'phone'     => $order->student_phone ?? $order->student->phone ?? null,
            ],

            'course' => $this->transformCourse($course),
        ];

        if ($full) {
            $base['price']                   = (float) $order->price;
            $base['discount']                = $order->discount_price !== null ? (float) $order->discount_price : null;
            $base['payment_status']          = $order->payment_status;
            $base['payment_method']          = $order->payment_method;
            $base['merchant_transaction_id'] = $order->merchant_transaction_id;
            $base['eps_transaction_id']      = $order->eps_transaction_id;
            $base['created_at']              = $order->created_at?->toDateTimeString();
        }

        return $base;
    }

    private function transformCourse($course): ?array
    {
        if (! $course) {
            return null;
        }

        return [
            'id'          => $course->id,
            'title'       => $course->title,
            'slug'        => $course->slug,
            'description' => $course->description,
            'thumbnail'   => $course->thumbnail
                ? asset('storage/' . $course->thumbnail)
                : 'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?w=600&q=80',

            'teacher' => [
                'name'        => $course->teacher_name,
                'designation' => $course->teacher_designation,
                'photo'       => $course->teacher_photo
                    ? asset('storage/' . $course->teacher_photo)
                    : null,
                'experience'  => $course->teacher_experience,
            ],

            'price'            => (float) $course->price,
            'discount_price'   => $course->discount_price !== null ? (float) $course->discount_price : null,
            'final_price'      => (float) $course->final_price,
            'has_discount'     => $course->has_discount,
            'discount_percent' => $course->discount_percent,
            'is_free'          => (bool) $course->is_free,

            'total_class' => $course->total_class,
            'duration'    => $course->duration,
            'start_date'  => $course->start_date?->format('Y-m-d'),
            'end_date'    => $course->end_date?->format('Y-m-d'),
            'status'      => $course->status,
            'is_featured' => (bool) $course->is_featured,
            'view_count'  => (int) $course->view_count,

            'course_type'     => $course->courseType ? [
                'id'   => $course->courseType->id,
                'name' => $course->courseType->name,
            ] : null,

            'course_class'    => $course->courseClass ? [
                'id'   => $course->courseClass->id,
                'name' => $course->courseClass->name,
            ] : null,

            'course_category' => $course->courseCategory ? [
                'id'   => $course->courseCategory->id,
                'name' => $course->courseCategory->name,
            ] : null,

            'created_at' => $course->created_at?->toDateTimeString(),
        ];
    }

    /* ============================================================
       REFERRAL COURSE COMMISSION
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
       HTML CALLBACK PAGE
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