<?php

namespace App\Http\Controllers\Api\V1\User\Eshop;

use App\EPS\EPSPayment;
use App\Http\Controllers\Controller;
use App\Models\BookOrder;
use App\Models\Ebook;
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

class BookShopController extends Controller
{
    /* ============================================================
       CACHE CONFIG — Payment data survives across requests
    ============================================================ */
    private const CACHE_PREFIX    = 'book_payment_';
    private const CACHE_TTL_HOURS = 2;

    /* ============================================================
       INDEX — All active books
    ============================================================ */
    public function index(): JsonResponse
    {
        $books = Ebook::where('status', 'active')->latest()->paginate(12);

        return response()->json([
            'success' => true,
            'message' => 'বইয়ের তালিকা পাওয়া গেছে।',
            'data'    => $books->items(),
            'pagination' => [
                'current_page'  => $books->currentPage(),
                'per_page'      => $books->perPage(),
                'total'         => $books->total(),
                'last_page'     => $books->lastPage(),
                'next_page_url' => $books->nextPageUrl(),
                'prev_page_url' => $books->previousPageUrl(),
            ],
        ], 200);
    }

    /* ============================================================
       SHOW — Book details by slug
    ============================================================ */
    public function show(string $slug): JsonResponse
    {
        $book = Ebook::where('slug', $slug)->where('status', 'active')->first();

        if (! $book) {
            return response()->json([
                'success' => false,
                'message' => 'বইটি খুঁজে পাওয়া যায়নি।',
                'data'    => null,
            ], 404);
        }

        $book->increment('view_count');
        $book->refresh();

        $relatedBooks = Ebook::where('status', 'active')
            ->where('id', '!=', $book->id)
            ->where('author_name', $book->author_name)
            ->latest()
            ->take(4)
            ->get();

        /* ===== Already purchased? ===== */
        $alreadyPurchased = false;
        if (Auth::check()) {
            $alreadyPurchased = BookOrder::where('user_id', Auth::id())
                ->where('ebook_id', $book->id)
                ->where('payment_status', 'paid')
                ->exists();
        }

        return response()->json([
            'success' => true,
            'message' => 'বইয়ের বিবরণ পাওয়া গেছে।',
            'data'    => [
                'book'              => $book,
                'related_books'     => $relatedBooks,
                'already_purchased' => $alreadyPurchased,
                'can_buy'           => $book->quantity > 0 && ! $alreadyPurchased,
            ],
        ], 200);
    }

    /* ============================================================
       BUY — Initialize EPS Payment (Cache-based, no session)
    ============================================================ */
    public function buy(Request $request, string $slug): JsonResponse
    {
        $user = Auth::user();

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
            ], 401);
        }

        $book = Ebook::where('slug', $slug)->where('status', 'active')->first();

        if (! $book) {
            return response()->json(['success' => false, 'message' => 'বইটি খুঁজে পাওয়া যায়নি।'], 404);
        }

        if ($book->quantity <= 0) {
            return response()->json(['success' => false, 'message' => 'এই বইটি স্টকে নেই।'], 422);
        }

        /* ===== Already purchased ===== */
        $duplicate = BookOrder::where('ebook_id', $book->id)
            ->where('user_id', $user->id)
            ->where('payment_status', 'paid')
            ->exists();

        if ($duplicate) {
            return response()->json([
                'success' => false,
                'message' => 'আপনি ইতিমধ্যে এই বইটি কিনেছেন।',
                'data'    => ['already_purchased' => true],
            ], 422);
        }

        $amount = (float) ($book->sell_price > 0 ? $book->sell_price : $book->price);

        if ($amount <= 0) {
            return response()->json(['success' => false, 'message' => 'Invalid payment amount.'], 422);
        }

        $merchantTransactionId = null;
        $cacheKey = null;

        try {
            $customerOrderId       = 'BOK_' . now()->format('YmdHis') . '_' . rand(1000, 9999);
            $merchantTransactionId = 'EBS' . now()->format('YmdHis') . rand(10000, 99999);
            $orderNo               = 'BORD-' . strtoupper(Str::random(10));
            $cacheKey              = self::CACHE_PREFIX . $merchantTransactionId;

            /* ===== CACHE — persists across requests (no session needed) ===== */
            Cache::put($cacheKey, [
                'user_id'                 => $user->id,
                'ebook_id'                => $book->id,
                'amount'                  => $amount,
                'order_no'                => $orderNo,
                'customer_order_id'       => $customerOrderId,
                'merchant_transaction_id' => $merchantTransactionId,
            ], now()->addHours(self::CACHE_TTL_HOURS));

            /* ===== EPS payload ===== */
            $productArr = [[
                'ProductName'     => $book->book_name,
                'NoOfItem'        => '1',
                'ProductProfile'  => '102',
                'ProductCategory' => 'Ebook',
                'ProductPrice'    => (string) $amount,
            ]];

            $payload = [
                'CustomerOrderId'  => $customerOrderId,
                'totalAmount'      => $amount,
                'ipAddress'        => $request->ip() ?? '127.0.0.1',
                'successUrl'       => route('book.shop.payment.success'),
                'failUrl'          => route('book.shop.payment.fail'),
                'cancelUrl'        => route('book.shop.payment.cancel'),
                'customerName'     => $user->full_name ?? '',
                'customerEmail'    => $user->email ?? '',
                'customerAddress'  => $user->address ?? '',
                'customerAddress2' => '',
                'customerCity'     => $user->city ?? 'Dhaka',
                'customerState'    => $user->state ?? 'Dhaka',
                'customerPostcode' => $user->postcode ?? '1000',
                'customerCountry'  => 'BD',
                'customerPhone'    => $user->phone ?? '01700000000',
                'shipmentName'     => $user->full_name ?? '',
                'shipmentAddress'  => $user->address ?? '',
                'shipmentAddress2' => '',
                'shipmentCity'     => $user->city ?? 'Dhaka',
                'shipmentState'    => $user->state ?? 'Dhaka',
                'shipmentPostcode' => $user->postcode ?? '1000',
                'shipmentCountry'  => 'BD',
                'valueA'           => (string) $user->id,
                'valueB'           => $merchantTransactionId,
                'valueC'           => 'ebook_' . $book->id,
                'valueD'           => '',
                'shippingMethod'   => 'Digital',
                'noOfItem'         => '1',
                'productName'      => $book->book_name,
                'productProfile'   => 'Ebook',
                'productCategory'  => 'Ebook',
                'ProductList'      => $productArr,
            ];

            $epsPayment = new EPSPayment;
            $data = $epsPayment->CreatePayment($payload);

            if (! $data || ($data['isSuccess'] ?? false) !== true) {
                Cache::forget($cacheKey);
                Log::error('API Book EPS Init Failed', ['data' => $data]);

                return response()->json([
                    'success' => false,
                    'message' => 'Payment gateway returned no response.',
                    'data'    => $data,
                ], 502);
            }

            $paymentUrl = $data['RedirectURL'] ?? $data['payment_url'] ?? null;

            if (! $paymentUrl) {
                Cache::forget($cacheKey);
                Log::error('API Book EPS URL Missing', ['response' => $data]);

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
                    'payment_url'             => $paymentUrl,
                    'book'                    => [
                        'id'         => $book->id,
                        'book_name'  => $book->book_name,
                        'slug'       => $book->slug,
                        'book_photo' => $book->book_photo
                            ? asset('storage/' . $book->book_photo)
                            : null,
                    ],
                ],
            ], 200);

        } catch (\Exception $e) {
            if ($cacheKey) {
                Cache::forget($cacheKey);
            }

            Log::error('API Book EPS Initialize Error', [
                'message' => $e->getMessage(),
                'user_id' => $user->id,
                'book_id' => $book->id,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Payment processing error.',
            ], 500);
        }
    }

    /* ============================================================
       PAYMENT SUCCESS — Verify + Order Create + Commission
       Uses cache + EPS verify fallback (same as course)
    ============================================================ */
    public function paymentSuccess(Request $request)
    {
        $merchantTransactionId = null;

        try {
            Log::info('API Book EPS Success Callback', ['request' => $request->all()]);

            $merchantTransactionId =
                $request->merchant_transaction_id ??
                $request->MerchantTransactionId ??
                $request->merchantTransactionId ??
                null;

            if (! $merchantTransactionId) {
                return $this->renderCallback('error', 'Transaction information not found.');
            }

            /* ===== DUPLICATE CHECK ===== */
            $existingOrder = BookOrder::where('merchant_transaction_id', $merchantTransactionId)->first();
            if ($existingOrder) {
                Cache::forget(self::CACHE_PREFIX . $merchantTransactionId);
                return $this->renderCallback('success', 'This payment has already been processed.', $existingOrder);
            }

            /* ===== EPS VERIFY ===== */
            $epsPayment = new EPSPayment;
            $verifyResponse = $epsPayment->checkPaymentStatus($merchantTransactionId);

            Log::info('API Book EPS Verify Response', [
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

            $fallbackUserId = null;
            $fallbackBookId = null;

            if (empty($cachedData)) {
                Log::warning('Book Cache miss — using EPS verify fallback', [
                    'merchant_transaction_id' => $merchantTransactionId,
                ]);

                if (! empty($verifyResponse['ValueA'])) {
                    $fallbackUserId = (int) $verifyResponse['ValueA'];
                }

                if (! empty($verifyResponse['ValueC']) && preg_match('/ebook_(\d+)/', $verifyResponse['ValueC'], $m)) {
                    $fallbackBookId = (int) $m[1];
                }
            }

            $userId = $cachedData['user_id']  ?? $fallbackUserId;
            $bookId = $cachedData['ebook_id'] ?? $fallbackBookId;

            if (! $userId || ! $bookId) {
                Cache::forget(self::CACHE_PREFIX . $merchantTransactionId);
                Log::error('Cannot determine user/book for book payment', [
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
                $buyer = User::findOrFail($userId);
                $book  = Ebook::findOrFail($bookId);

                /* ============================================================
                   ✅ COMMISSION BASE = book->commission (মোট কমিশন টাকা)
                   50% + 25% + 15% + 10% = 100%
                ============================================================ */
                $commissionBase = (float) $book->commission;

                Log::info('API Book Commission Distribution', [
                    'ebook_id'        => $book->id,
                    'price'           => $book->price,
                    'commission_base' => $commissionBase,
                    'direct_50'       => $commissionBase * 0.50,
                    'gen1_25'         => $commissionBase * 0.25,
                    'gen2_15'         => $commissionBase * 0.15,
                    'gen3_10'         => $commissionBase * 0.10,
                ]);

                $order = BookOrder::create([
                    'user_id'                 => $buyer->id,
                    'ebook_id'                => $book->id,
                    'order_no'                => $cachedData['order_no'] ?? ('BORD-' . strtoupper(Str::random(10))),

                    'buyer_name'              => $buyer->full_name,
                    'buyer_email'             => $buyer->email,
                    'buyer_phone'             => $buyer->phone,

                    'price'                   => $book->price,
                    'discount'                => $book->discount,
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

                /* ----- Decrement stock ----- */
                if ($book->quantity > 0) {
                    $book->decrement('quantity');
                }

                /* ----- Transaction log ----- */
                Transaction::create([
                    'from_id'   => $buyer->id,
                    'user_id'   => $buyer->id,
                    'from_user' => $buyer->id,
                    'out'       => 'in',
                    'status'    => 'success',
                    'purpose'   => 'Book Purchase: ' . $book->book_name,
                    'amount'    => $verifiedAmount,
                ]);

                /* ----- Commission distribution ----- */
                $this->referBookCommission($buyer, $commissionBase, $book, $order);

                DB::commit();
                Cache::forget(self::CACHE_PREFIX . $merchantTransactionId);

                Log::info('API Book Order Created', [
                    'order_id'  => $order->id,
                    'order_no'  => $order->order_no,
                    'user_id'   => $buyer->id,
                    'amount'    => $order->paid_amount,
                    'cache_hit' => ! empty($cachedData),
                ]);

                return $this->renderCallback('success', '🎉 আপনার বই সফলভাবে কেনা হয়েছে।', $order);

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('API Book EPS Success Error', [
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
        Log::info('API Book EPS Fail Callback', $request->all());

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
        Log::info('API Book EPS Cancel Callback', $request->all());

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
       ORDERS — My orders list
    ============================================================ */
    public function orders(): JsonResponse
    {
        $orders = BookOrder::with(['ebook:id,book_name,book_photo,author_name,slug,sell_price,price'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(15);

        $transformed = collect($orders->items())->map(fn ($o) => $this->transformOrder($o));

        return response()->json([
            'success' => true,
            'message' => 'আপনার অর্ডারের তালিকা।',
            'data'    => $transformed,
            'pagination' => [
                'current_page'  => $orders->currentPage(),
                'per_page'      => $orders->perPage(),
                'total'         => $orders->total(),
                'last_page'     => $orders->lastPage(),
                'next_page_url' => $orders->nextPageUrl(),
                'prev_page_url' => $orders->previousPageUrl(),
            ],
        ], 200);
    }

    /* ============================================================
       VOUCHER — JSON + signed web URL (course-এর মতো)
    ============================================================ */
    public function voucher(string $order_no): JsonResponse
    {
        $order = BookOrder::with(['user', 'ebook'])
            ->where('order_no', $order_no)
            ->where('user_id', Auth::id())
            ->first();

        if (! $order) {
            return response()->json([
                'success' => false,
                'message' => 'Voucher not found or payment is not completed.',
                'data'    => null,
            ], 404);
        }

        if ($order->payment_status !== 'paid') {
            return response()->json([
                'success' => false,
                'message' => 'Payment is not completed. Voucher unavailable.',
                'data'    => null,
            ], 422);
        }

        /* ===== Signed web URL (course-এর মতো) ===== */
        $voucherWebUrl = URL::temporarySignedRoute(
            'book.voucher.public',
            now()->addDays(30),
            ['order_no' => $order->order_no]
        );

        $book = $order->ebook;

        return response()->json([
            'success' => true,
            'message' => 'Book voucher retrieved successfully.',
            'data'    => [
                'voucher_url'     => $voucherWebUrl,
                'voucher_expires' => now()->addDays(30)->toDateTimeString(),

                'voucher' => [
                    'voucher_no' => $order->order_no,
                    'issued_at'  => $order->paid_at?->toDateTimeString(),
                    'status'     => 'paid',
                ],

                'payment' => [
                    'order_id'                => $order->id,
                    'order_no'                => $order->order_no,
                    'price'                   => (float) $order->price,
                    'discount'                => (float) $order->discount,
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

                'book' => $book ? [
                    'id'         => $book->id,
                    'book_name'  => $book->book_name,
                    'slug'       => $book->slug,
                    'author'     => $book->author_name,
                    'total_page' => $book->total_page,
                    'book_photo' => $book->book_photo
                        ? asset('storage/' . $book->book_photo)
                        : null,
                ] : null,
            ],
        ], 200);
    }

    /* ============================================================
       TRANSFORMER
    ============================================================ */
    private function transformOrder(BookOrder $order): array
    {
        $book = $order->ebook;

        return [
            'order_id'    => $order->id,
            'order_no'    => $order->order_no,
            'paid_amount' => (float) $order->paid_amount,
            'paid_at'     => $order->paid_at?->toDateTimeString(),

            'payment_status'          => $order->payment_status,
            'payment_method'          => $order->payment_method,
            'merchant_transaction_id' => $order->merchant_transaction_id,

            'book' => $book ? [
                'id'         => $book->id,
                'book_name'  => $book->book_name,
                'slug'       => $book->slug,
                'author'     => $book->author_name,
                'book_photo' => $book->book_photo
                    ? asset('storage/' . $book->book_photo)
                    : null,
                'price'      => (float) $book->price,
                'sell_price' => (float) $book->sell_price,
            ] : null,

            'has_voucher' => $order->payment_status === 'paid',
        ];
    }

    /* ============================================================
       REFERRAL BOOK COMMISSION
       Direct 50% | 1st Gen 25% | 2nd Gen 15% | 3rd Gen 10%
    ============================================================ */
    public function referBookCommission(User $user, float $baseAmount, Ebook $book, BookOrder $order): bool
    {
        $directPercent = 50;
        $firstPercent  = 25;
        $secondPercent = 15;
        $thirdPercent  = 10;

        if ($baseAmount <= 0) {
            return false;
        }

        /* ===== Direct (50%) ===== */
        $directId = $user->refer_by;
        if (! $directId) {
            return true;
        }

        $direct = User::lockForUpdate()->find($directId);
        if (! $direct || $direct->id === $user->id) {
            return true;
        }

        $directAmt = ($baseAmount * $directPercent) / 100;
        if ($directAmt > 0) {
            $direct->increment('income_wallet', $directAmt);

            Transaction::create([
                'from_id'   => $user->id,
                'user_id'   => $direct->id,
                'from_user' => $user->id,
                'out'       => 'referral',
                'status'    => 'success',
                'purpose'   => 'Direct Referral Book Commission (' . $directPercent . '%) - ' . $book->book_name,
                'amount'    => $directAmt,
            ]);

            Generation::create([
                'from_user_id' => $user->id,
                'to_user_id'   => $direct->id,
                'level'        => 1,
                'date'         => now(),
                'status'       => 1,
                'commission'   => $directAmt,
                'total_amount' => $baseAmount,
            ]);
        }

        /* ===== 1st Gen (25%) ===== */
        $firstId = $direct->refer_by;
        if (! $firstId) {
            return true;
        }

        $first = User::lockForUpdate()->find($firstId);
        if (! $first || in_array($first->id, [$user->id, $direct->id])) {
            return true;
        }

        $firstAmt = ($baseAmount * $firstPercent) / 100;
        if ($firstAmt > 0) {
            $first->increment('income_wallet', $firstAmt);

            Transaction::create([
                'from_id'   => $user->id,
                'user_id'   => $first->id,
                'from_user' => $user->id,
                'out'       => 'referral',
                'status'    => 'success',
                'purpose'   => '1st Gen Book Commission (' . $firstPercent . '%) - ' . $book->book_name,
                'amount'    => $firstAmt,
            ]);

            Generation::create([
                'from_user_id' => $user->id,
                'to_user_id'   => $first->id,
                'level'        => 2,
                'date'         => now(),
                'status'       => 1,
                'commission'   => $firstAmt,
                'total_amount' => $baseAmount,
            ]);
        }

        /* ===== 2nd Gen (15%) ===== */
        $secondId = $first->refer_by;
        if (! $secondId) {
            return true;
        }

        $second = User::lockForUpdate()->find($secondId);
        if (! $second || in_array($second->id, [$user->id, $direct->id, $first->id])) {
            return true;
        }

        $secondAmt = ($baseAmount * $secondPercent) / 100;
        if ($secondAmt > 0) {
            $second->increment('income_wallet', $secondAmt);

            Transaction::create([
                'from_id'   => $user->id,
                'user_id'   => $second->id,
                'from_user' => $user->id,
                'out'       => 'referral',
                'status'    => 'success',
                'purpose'   => '2nd Gen Book Commission (' . $secondPercent . '%) - ' . $book->book_name,
                'amount'    => $secondAmt,
            ]);

            Generation::create([
                'from_user_id' => $user->id,
                'to_user_id'   => $second->id,
                'level'        => 3,
                'date'         => now(),
                'status'       => 1,
                'commission'   => $secondAmt,
                'total_amount' => $baseAmount,
            ]);
        }

        /* ===== 3rd Gen (10%) ===== */
        $thirdId = $second->refer_by;
        if (! $thirdId) {
            return true;
        }

        $third = User::lockForUpdate()->find($thirdId);
        if (! $third || in_array($third->id, [$user->id, $direct->id, $first->id, $second->id])) {
            return true;
        }

        $thirdAmt = ($baseAmount * $thirdPercent) / 100;
        if ($thirdAmt > 0) {
            $third->increment('income_wallet', $thirdAmt);

            Transaction::create([
                'from_id'   => $user->id,
                'user_id'   => $third->id,
                'from_user' => $user->id,
                'out'       => 'referral',
                'status'    => 'success',
                'purpose'   => '3rd Gen Book Commission (' . $thirdPercent . '%) - ' . $book->book_name,
                'amount'    => $thirdAmt,
            ]);

            Generation::create([
                'from_user_id' => $user->id,
                'to_user_id'   => $third->id,
                'level'        => 4,
                'date'         => now(),
                'status'       => 1,
                'commission'   => $thirdAmt,
                'total_amount' => $baseAmount,
            ]);
        }

        return true;
    }

    /* ============================================================
       HTML CALLBACK PAGE (Course-এর মতোই)
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