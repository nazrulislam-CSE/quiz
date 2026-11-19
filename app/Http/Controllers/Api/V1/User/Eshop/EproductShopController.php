<?php

namespace App\Http\Controllers\Api\V1\User\Eshop;

use App\EPS\EPSPayment;
use App\Http\Controllers\Controller;
use App\Models\Eproduct;
use App\Models\EproductOrder;
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

class EproductShopController extends Controller
{
    /* ============================================================
       CACHE CONFIG — Payment data survives across requests
    ============================================================ */
    private const CACHE_PREFIX    = 'eproduct_payment_';
    private const CACHE_TTL_HOURS = 2;

    /* ============================================================
       INDEX — All active products
    ============================================================ */
    public function index(): JsonResponse
    {
        $products = Eproduct::where('status', 'active')->latest()->paginate(12);

        return response()->json([
            'success' => true,
            'message' => 'প্রোডাক্টের তালিকা পাওয়া গেছে।',
            'data'    => $products->items(),
            'pagination' => [
                'current_page'  => $products->currentPage(),
                'per_page'      => $products->perPage(),
                'total'         => $products->total(),
                'last_page'     => $products->lastPage(),
                'next_page_url' => $products->nextPageUrl(),
                'prev_page_url' => $products->previousPageUrl(),
            ],
        ], 200);
    }

    /* ============================================================
       SHOW — Product details by slug
    ============================================================ */
    public function show(string $slug): JsonResponse
    {
        $product = Eproduct::where('slug', $slug)->where('status', 'active')->first();

        if (! $product) {
            return response()->json([
                'success' => false,
                'message' => 'প্রোডাক্টটি খুঁজে পাওয়া যায়নি।',
                'data'    => null,
            ], 404);
        }

        $product->increment('view_count');
        $product->refresh();

        $relatedProducts = Eproduct::where('status', 'active')
            ->where('id', '!=', $product->id)
            ->where(function ($q) use ($product) {
                if ($product->brand_name) {
                    $q->where('brand_name', $product->brand_name);
                }
            })
            ->latest()
            ->take(4)
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'প্রোডাক্টের বিবরণ পাওয়া গেছে।',
            'data'    => [
                'product'            => $product,
                'related_products'   => $relatedProducts,
                'in_stock'           => $product->quantity > 0,
                'shipping_charges'   => [
                    'inside_dhaka'  => 100,
                    'outside_dhaka' => 130,
                    'self_receive'  => 0,
                ],
            ],
        ], 200);
    }

    /* ============================================================
       BUY — Initialize EPS (Cache-based)
       Shipping info + delivery_type required
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

        $product = Eproduct::where('slug', $slug)->where('status', 'active')->first();

        if (! $product) {
            return response()->json(['success' => false, 'message' => 'প্রোডাক্টটি খুঁজে পাওয়া যায়নি।'], 404);
        }

        if ($product->quantity <= 0) {
            return response()->json(['success' => false, 'message' => 'এই প্রোডাক্টটি স্টকে নেই।'], 422);
        }

        /* ===== VALIDATION ===== */
        $validated = $request->validate([
            'shipping_name'     => 'required|string|max:255',
            'shipping_phone'    => 'required|string|max:20',
            'shipping_address'  => 'required|string|max:500',
            'shipping_city'     => 'required|string|max:100',
            'shipping_postcode' => 'nullable|string|max:20',
            'delivery_type'     => 'required|in:inside_dhaka,outside_dhaka,self_receive',
        ], [
            'shipping_name.required'    => 'প্রাপকের নাম দিন।',
            'shipping_phone.required'   => 'মোবাইল নম্বর দিন।',
            'shipping_address.required' => 'ঠিকানা দিন।',
            'shipping_city.required'    => 'শহর/জেলা দিন।',
            'delivery_type.required'    => 'ডেলিভারি ধরন নির্বাচন করুন।',
        ]);

        /* ===== SHIPPING CHARGE ===== */
        $shippingCharge = EproductOrder::getShippingCharge($validated['delivery_type']);

        $productPrice = (float) ($product->has_discount ? $product->customer_price : $product->price);
        $productTotal = $productPrice;
        $totalAmount  = $productTotal + $shippingCharge;

        if ($totalAmount <= 0) {
            return response()->json(['success' => false, 'message' => 'Invalid payment amount.'], 422);
        }

        $merchantTransactionId = null;
        $cacheKey = null;

        try {
            $customerOrderId       = 'PRD_' . now()->format('YmdHis') . '_' . rand(1000, 9999);
            $merchantTransactionId = 'EPD' . now()->format('YmdHis') . rand(10000, 99999);
            $orderNo               = 'PORD-' . strtoupper(Str::random(10));
            $cacheKey              = self::CACHE_PREFIX . $merchantTransactionId;

            /* ===== CACHE — persists across requests ===== */
            Cache::put($cacheKey, [
                'user_id'                 => $user->id,
                'eproduct_id'             => $product->id,
                'order_no'                => $orderNo,
                'merchant_transaction_id' => $merchantTransactionId,
                'customer_order_id'       => $customerOrderId,

                // Shipping
                'shipping_name'           => $validated['shipping_name'],
                'shipping_phone'          => $validated['shipping_phone'],
                'shipping_address'        => $validated['shipping_address'],
                'shipping_city'           => $validated['shipping_city'],
                'shipping_postcode'       => $validated['shipping_postcode'] ?? null,

                // Delivery
                'delivery_type'           => $validated['delivery_type'],
                'shipping_charge'         => $shippingCharge,

                // Amounts
                'product_price'           => $productPrice,
                'product_total'           => $productTotal,
                'amount'                  => $totalAmount,
            ], now()->addHours(self::CACHE_TTL_HOURS));

            /* ===== EPS Payload ===== */
            $productArr = [[
                'ProductName'     => $product->product_name,
                'NoOfItem'        => '1',
                'ProductProfile'  => '103',
                'ProductCategory' => 'Eproduct',
                'ProductPrice'    => (string) $productTotal,
            ]];

            if ($shippingCharge > 0) {
                $productArr[] = [
                    'ProductName'     => 'Shipping Charge (' . ucfirst(str_replace('_', ' ', $validated['delivery_type'])) . ')',
                    'NoOfItem'        => '1',
                    'ProductProfile'  => '999',
                    'ProductCategory' => 'Shipping',
                    'ProductPrice'    => (string) $shippingCharge,
                ];
            }

            $payload = [
                'CustomerOrderId'  => $customerOrderId,
                'totalAmount'      => $totalAmount,
                'ipAddress'        => $request->ip() ?? '127.0.0.1',
                'successUrl'       => route('eproduct.shop.payment.success'),
                'failUrl'          => route('eproduct.shop.payment.fail'),
                'cancelUrl'        => route('eproduct.shop.payment.cancel'),
                'customerName'     => $user->full_name ?? '',
                'customerEmail'    => $user->email ?? '',
                'customerAddress'  => $validated['shipping_address'],
                'customerAddress2' => '',
                'customerCity'     => $validated['shipping_city'],
                'customerState'    => $validated['shipping_city'],
                'customerPostcode' => $validated['shipping_postcode'] ?? '1000',
                'customerCountry'  => 'BD',
                'customerPhone'    => $validated['shipping_phone'],
                'shipmentName'     => $validated['shipping_name'],
                'shipmentAddress'  => $validated['shipping_address'],
                'shipmentAddress2' => '',
                'shipmentCity'     => $validated['shipping_city'],
                'shipmentState'    => $validated['shipping_city'],
                'shipmentPostcode' => $validated['shipping_postcode'] ?? '1000',
                'shipmentCountry'  => 'BD',
                'valueA'           => (string) $user->id,
                'valueB'           => $merchantTransactionId,
                'valueC'           => 'eproduct_' . $product->id,
                'valueD'           => '',
                'shippingMethod'   => $validated['delivery_type'] === 'self_receive' ? 'Self Pickup' : 'Courier',
                'noOfItem'         => (string) count($productArr),
                'productName'      => $product->product_name,
                'productProfile'   => 'Eproduct',
                'productCategory'  => 'Eproduct',
                'ProductList'      => $productArr,
            ];

            $epsPayment = new EPSPayment;
            $data = $epsPayment->CreatePayment($payload);

            if (! $data || ($data['isSuccess'] ?? false) !== true) {
                Cache::forget($cacheKey);
                Log::error('API Eproduct EPS Init Failed', ['data' => $data]);

                return response()->json([
                    'success' => false,
                    'message' => 'Payment gateway returned no response.',
                    'data'    => $data,
                ], 502);
            }

            $paymentUrl = $data['RedirectURL'] ?? $data['payment_url'] ?? null;

            if (! $paymentUrl) {
                Cache::forget($cacheKey);
                Log::error('API Eproduct EPS URL Missing', ['response' => $data]);

                return response()->json([
                    'success' => false,
                    'message' => 'Payment URL not found.',
                ], 502);
            }

            /* ===== Update cache with EPS response ===== */
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

                    'product_total'           => $productTotal,
                    'shipping_charge'         => $shippingCharge,
                    'total_amount'            => $totalAmount,

                    'delivery_type'           => $validated['delivery_type'],
                    'delivery_label'          => EproductOrder::getShippingCharge($validated['delivery_type']),

                    'payment_url'             => $paymentUrl,
                    'product'                 => [
                        'id'          => $product->id,
                        'product_name'=> $product->product_name,
                        'slug'        => $product->slug,
                        'product_photo'=> $product->product_photo
                            ? asset('storage/' . $product->product_photo)
                            : null,
                    ],
                ],
            ], 200);

        } catch (\Exception $e) {
            if ($cacheKey) {
                Cache::forget($cacheKey);
            }

            Log::error('API Eproduct EPS Initialize Error', [
                'message' => $e->getMessage(),
                'user_id' => $user->id,
                'product_id' => $product->id,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Payment processing error.',
            ], 500);
        }
    }

    /* ============================================================
       PAYMENT SUCCESS — Verify + Order Create + Commission
    ============================================================ */
    public function paymentSuccess(Request $request)
    {
        $merchantTransactionId = null;

        try {
            Log::info('API Eproduct EPS Success Callback', ['request' => $request->all()]);

            $merchantTransactionId =
                $request->merchant_transaction_id ??
                $request->MerchantTransactionId ??
                $request->merchantTransactionId ??
                null;

            if (! $merchantTransactionId) {
                return $this->renderCallback('error', 'Transaction information not found.');
            }

            /* ===== Duplicate check ===== */
            $existingOrder = EproductOrder::where('merchant_transaction_id', $merchantTransactionId)->first();
            if ($existingOrder) {
                Cache::forget(self::CACHE_PREFIX . $merchantTransactionId);
                return $this->renderCallback('success', 'This payment has already been processed.', $existingOrder);
            }

            /* ===== EPS Verify ===== */
            $epsPayment = new EPSPayment;
            $verifyResponse = $epsPayment->checkPaymentStatus($merchantTransactionId);

            Log::info('API Eproduct EPS Verify Response', [
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

            /* ===== Cache + Fallback ===== */
            $cachedData = Cache::get(self::CACHE_PREFIX . $merchantTransactionId, []);

            $fallbackUserId  = null;
            $fallbackProductId = null;

            if (empty($cachedData)) {
                Log::warning('Eproduct Cache miss — EPS verify fallback', [
                    'merchant_transaction_id' => $merchantTransactionId,
                ]);

                if (! empty($verifyResponse['ValueA'])) {
                    $fallbackUserId = (int) $verifyResponse['ValueA'];
                }

                if (! empty($verifyResponse['ValueC']) && preg_match('/eproduct_(\d+)/', $verifyResponse['ValueC'], $m)) {
                    $fallbackProductId = (int) $m[1];
                }
            }

            $userId    = $cachedData['user_id']     ?? $fallbackUserId;
            $productId = $cachedData['eproduct_id'] ?? $fallbackProductId;

            if (! $userId || ! $productId) {
                Cache::forget(self::CACHE_PREFIX . $merchantTransactionId);
                Log::error('Cannot determine user/product for eproduct payment', [
                    'merchant_transaction_id' => $merchantTransactionId,
                    'cached_data'             => $cachedData,
                    'verify_response'         => $verifyResponse,
                ]);

                return $this->renderCallback(
                    'error',
                    'Payment data unavailable. Txn ID: ' . $merchantTransactionId . ' — please contact support.'
                );
            }

            $originalAmount = (float) ($cachedData['amount'] ?? 0);
            if ($originalAmount > 0 && abs($originalAmount - $verifiedAmount) > 0.01) {
                Cache::forget(self::CACHE_PREFIX . $merchantTransactionId);
                return $this->renderCallback('error', 'Payment amount verification failed.');
            }

            $epsTransactionId = $verifyResponse['EpsTransactionId'] ?? $verifyResponse['EPSTransactionId'] ?? null;

            /* ===== DB Transaction ===== */
            DB::beginTransaction();

            try {
                $buyer   = User::findOrFail($userId);
                $product = Eproduct::findOrFail($productId);

                $commissionBase = (float) $product->commission;

                Log::info('API Eproduct Commission Distribution', [
                    'eproduct_id'     => $product->id,
                    'price'           => $product->price,
                    'commission_base' => $commissionBase,
                    'direct_50'       => $commissionBase * 0.50,
                    'gen1_25'         => $commissionBase * 0.25,
                    'gen2_15'         => $commissionBase * 0.15,
                    'gen3_10'         => $commissionBase * 0.10,
                ]);

                $order = EproductOrder::create([
                    'user_id'                 => $buyer->id,
                    'eproduct_id'             => $product->id,
                    'order_no'                => $cachedData['order_no'] ?? ('PORD-' . strtoupper(Str::random(10))),

                    'buyer_name'              => $buyer->full_name,
                    'buyer_email'             => $buyer->email,
                    'buyer_phone'             => $buyer->phone,

                    'shipping_name'           => $cachedData['shipping_name'] ?? $buyer->full_name,
                    'shipping_phone'          => $cachedData['shipping_phone'] ?? $buyer->phone,
                    'shipping_address'        => $cachedData['shipping_address'] ?? ($buyer->address ?? ''),
                    'shipping_city'           => $cachedData['shipping_city'] ?? ($buyer->city ?? 'Dhaka'),
                    'shipping_postcode'       => $cachedData['shipping_postcode'] ?? null,

                    'delivery_type'           => $cachedData['delivery_type'] ?? 'inside_dhaka',
                    'shipping_charge'         => (float) ($cachedData['shipping_charge'] ?? 0),

                    'price'                   => $product->price,
                    'discount'                => $product->discount,
                    'product_total'           => (float) ($cachedData['product_total'] ?? $verifiedAmount),
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

                /* Stock decrement */
                if ($product->quantity > 0) {
                    $product->decrement('quantity');
                }

                /* Transaction log */
                Transaction::create([
                    'from_id'   => $buyer->id,
                    'user_id'   => $buyer->id,
                    'from_user' => $buyer->id,
                    'out'       => 'in',
                    'status'    => 'success',
                    'purpose'   => 'Eproduct Purchase: ' . $product->product_name,
                    'amount'    => $verifiedAmount,
                ]);

                /* Commission distribute */
                $this->referEproductCommission($buyer, $commissionBase, $product, $order);

                DB::commit();
                Cache::forget(self::CACHE_PREFIX . $merchantTransactionId);

                Log::info('API Eproduct Order Created', [
                    'order_id'  => $order->id,
                    'order_no'  => $order->order_no,
                    'user_id'   => $buyer->id,
                    'amount'    => $order->paid_amount,
                    'cache_hit' => ! empty($cachedData),
                ]);

                return $this->renderCallback('success', '🎉 আপনার অর্ডার সফলভাবে সম্পন্ন হয়েছে।', $order);

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('API Eproduct EPS Success Error', [
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
        Log::info('API Eproduct EPS Fail Callback', $request->all());

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
        Log::info('API Eproduct EPS Cancel Callback', $request->all());

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
        $orders = EproductOrder::with(['eproduct:id,product_name,product_photo,brand_name,slug,customer_price,price'])
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
       VOUCHER — JSON + signed web URL
    ============================================================ */
    public function voucher(string $order_no): JsonResponse
    {
        $order = EproductOrder::with(['user', 'eproduct'])
            ->where('order_no', $order_no)
            ->where('user_id', Auth::id())
            ->first();

        if (! $order) {
            return response()->json([
                'success' => false,
                'message' => 'Voucher not found.',
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

        $voucherWebUrl = URL::temporarySignedRoute(
            'eproduct.voucher.public',
            now()->addDays(30),
            ['orderNo' => $order->order_no]
        );

        $product = $order->eproduct;

        return response()->json([
            'success' => true,
            'message' => 'Eproduct voucher retrieved successfully.',
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
                    'product_total'           => (float) $order->product_total,
                    'shipping_charge'         => (float) $order->shipping_charge,
                    'paid_amount'             => (float) $order->paid_amount,
                    'payment_status'          => $order->payment_status,
                    'payment_method'          => $order->payment_method,
                    'merchant_transaction_id' => $order->merchant_transaction_id,
                    'eps_transaction_id'      => $order->eps_transaction_id,
                    'paid_at'                 => $order->paid_at?->toDateTimeString(),
                ],

                'shipping' => [
                    'name'            => $order->shipping_name,
                    'phone'           => $order->shipping_phone,
                    'address'         => $order->shipping_address,
                    'city'            => $order->shipping_city,
                    'postcode'        => $order->shipping_postcode,
                    'delivery_type'   => $order->delivery_type,
                    'delivery_label'  => $order->delivery_label,
                    'shipping_charge' => (float) $order->shipping_charge,
                ],

                'buyer' => [
                    'id'        => $order->user_id,
                    'full_name' => $order->buyer_name ?? $order->user->full_name ?? null,
                    'email'     => $order->buyer_email ?? $order->user->email ?? null,
                    'phone'     => $order->buyer_phone ?? $order->user->phone ?? null,
                ],

                'product' => $product ? [
                    'id'           => $product->id,
                    'product_name' => $product->product_name,
                    'slug'         => $product->slug,
                    'brand'        => $product->brand_name,
                    'size'         => $product->size,
                    'color'        => $product->color,
                    'warranty'     => $product->warranty,
                    'product_photo'=> $product->product_photo
                        ? asset('storage/' . $product->product_photo)
                        : null,
                ] : null,
            ],
        ], 200);
    }

    /* ============================================================
       TRANSFORMER
    ============================================================ */
    private function transformOrder(EproductOrder $order): array
    {
        $product = $order->eproduct;

        return [
            'order_id'    => $order->id,
            'order_no'    => $order->order_no,
            'paid_amount' => (float) $order->paid_amount,
            'paid_at'     => $order->paid_at?->toDateTimeString(),

            'price'           => (float) $order->price,
            'discount'        => (float) $order->discount,
            'product_total'   => (float) $order->product_total,
            'shipping_charge' => (float) $order->shipping_charge,

            'delivery_type'  => $order->delivery_type,
            'delivery_label' => $order->delivery_label,

            'payment_status'          => $order->payment_status,
            'payment_method'          => $order->payment_method,
            'merchant_transaction_id' => $order->merchant_transaction_id,

            'shipping' => [
                'name'     => $order->shipping_name,
                'phone'    => $order->shipping_phone,
                'address'  => $order->shipping_address,
                'city'     => $order->shipping_city,
                'postcode' => $order->shipping_postcode,
            ],

            'product' => $product ? [
                'id'           => $product->id,
                'product_name' => $product->product_name,
                'slug'         => $product->slug,
                'brand'        => $product->brand_name,
                'product_photo'=> $product->product_photo
                    ? asset('storage/' . $product->product_photo)
                    : null,
                'price'        => (float) $product->price,
                'customer_price'=> (float) $product->customer_price,
            ] : null,

            'has_voucher' => $order->payment_status === 'paid',
        ];
    }

    /* ============================================================
       REFERRAL EPRODUCT COMMISSION
       Direct 50% | 1st Gen 25% | 2nd Gen 15% | 3rd Gen 10%
    ============================================================ */
    public function referEproductCommission(User $user, float $baseAmount, Eproduct $product, EproductOrder $order): bool
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
        if (! $directId) return true;

        $direct = User::lockForUpdate()->find($directId);
        if (! $direct || $direct->id === $user->id) return true;

        $directAmt = ($baseAmount * $directPercent) / 100;
        if ($directAmt > 0) {
            $direct->increment('income_wallet', $directAmt);

            Transaction::create([
                'from_id'   => $user->id,
                'user_id'   => $direct->id,
                'from_user' => $user->id,
                'out'       => 'referral',
                'status'    => 'success',
                'purpose'   => 'Direct Referral Eproduct Commission (' . $directPercent . '%) - ' . $product->product_name,
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
        if (! $firstId) return true;

        $first = User::lockForUpdate()->find($firstId);
        if (! $first || in_array($first->id, [$user->id, $direct->id])) return true;

        $firstAmt = ($baseAmount * $firstPercent) / 100;
        if ($firstAmt > 0) {
            $first->increment('income_wallet', $firstAmt);

            Transaction::create([
                'from_id'   => $user->id,
                'user_id'   => $first->id,
                'from_user' => $user->id,
                'out'       => 'referral',
                'status'    => 'success',
                'purpose'   => '1st Gen Eproduct Commission (' . $firstPercent . '%) - ' . $product->product_name,
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
        if (! $secondId) return true;

        $second = User::lockForUpdate()->find($secondId);
        if (! $second || in_array($second->id, [$user->id, $direct->id, $first->id])) return true;

        $secondAmt = ($baseAmount * $secondPercent) / 100;
        if ($secondAmt > 0) {
            $second->increment('income_wallet', $secondAmt);

            Transaction::create([
                'from_id'   => $user->id,
                'user_id'   => $second->id,
                'from_user' => $user->id,
                'out'       => 'referral',
                'status'    => 'success',
                'purpose'   => '2nd Gen Eproduct Commission (' . $secondPercent . '%) - ' . $product->product_name,
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
        if (! $thirdId) return true;

        $third = User::lockForUpdate()->find($thirdId);
        if (! $third || in_array($third->id, [$user->id, $direct->id, $first->id, $second->id])) return true;

        $thirdAmt = ($baseAmount * $thirdPercent) / 100;
        if ($thirdAmt > 0) {
            $third->increment('income_wallet', $thirdAmt);

            Transaction::create([
                'from_id'   => $user->id,
                'user_id'   => $third->id,
                'from_user' => $user->id,
                'out'       => 'referral',
                'status'    => 'success',
                'purpose'   => '3rd Gen Eproduct Commission (' . $thirdPercent . '%) - ' . $product->product_name,
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