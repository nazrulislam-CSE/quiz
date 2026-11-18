<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Eproduct;
use App\Models\EproductOrder;
use App\Models\Generation;
use App\Models\Transaction;
use App\Models\User;
use App\EPS\EPSPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class EproductShopController extends Controller
{
    /* ============================================================
       INDEX
    ============================================================ */
    public function index()
    {
        $pageTitle = 'আমাদের প্রোডাক্ট শপ';

        $products = Eproduct::where('status', 'active')
            ->latest()
            ->paginate(12);

        return view('user.eshop.eproduct.index', compact('pageTitle', 'products'));
    }

    /* ============================================================
       SHOW
    ============================================================ */
    public function show($slug)
    {
        $pageTitle = 'প্রোডাক্টের বিবরণ';

        $product = Eproduct::where('slug', $slug)
            ->where('status', 'active')
            ->firstOrFail();

        $product->increment('view_count');

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

        return view('user.eshop.eproduct.show',
            compact('pageTitle', 'product', 'relatedProducts'));
    }

    /* ============================================================
       CHECKOUT — Collect shipping + EPS Init
    ============================================================ */
    public function checkout(Request $request, $slug)
    {
        $buyer = Auth::user();

        $product = Eproduct::where('slug', $slug)
            ->where('status', 'active')
            ->firstOrFail();

        if ($product->quantity <= 0) {
            return back()->with('error', 'এই প্রোডাক্টটি স্টকে নেই।');
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
            return back()->with('error', 'Invalid payment amount.');
        }

        try {
            $customerOrderId       = 'PRD_' . now()->format('YmdHis') . '_' . rand(1000, 9999);
            $merchantTransactionId = 'EPD' . now()->format('YmdHis') . rand(10000, 99999);
            $orderNo               = 'PORD-' . strtoupper(Str::random(10));

            /* ===== SESSION — payment data ===== */
            session([
                'eproduct_payment_data' => [
                    'user_id'                 => $buyer->id,
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
                ],
            ]);

            $productArr = [[
                'ProductName'     => $product->product_name,
                'NoOfItem'        => '1',
                'ProductProfile'  => '103',
                'ProductCategory' => 'Eproduct',
                'ProductPrice'    => (string) $productTotal,
            ]];

            /* ===== Shipping as separate line item ===== */
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
                'ipAddress'        => $request->ip(),
                'successUrl'       => route('user.eproduct.payment.success'),
                'failUrl'          => route('user.eproduct.payment.fail'),
                'cancelUrl'        => route('user.eproduct.payment.cancel'),
                'customerName'     => $buyer->full_name ?? '',
                'customerEmail'    => $buyer->email ?? '',
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
                'valueA'           => (string) $buyer->id,
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

            if (! $data || ! isset($data['isSuccess']) || $data['isSuccess'] !== true) {
                session()->forget('eproduct_payment_data');
                Log::error('Eproduct EPS Init Failed', ['data' => $data]);

                return back()->with('error', 'Payment gateway থেকে response পাওয়া যায়নি।');
            }

            $paymentUrl = $data['RedirectURL'] ?? $data['payment_url'] ?? null;

            if (! $paymentUrl) {
                session()->forget('eproduct_payment_data');
                Log::error('Eproduct EPS URL Missing', ['response' => $data]);

                return back()->with('error', 'Payment URL পাওয়া যায়নি।');
            }

            session([
                'eproduct_payment_data' => array_merge(
                    session('eproduct_payment_data', []),
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
            session()->forget('eproduct_payment_data');
            Log::error('Eproduct EPS Init Error', [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);

            return back()->with('error', 'Payment আরম্ভ করা যায়নি।');
        }
    }

    /* ============================================================
       PAYMENT SUCCESS — Verify + Order Create + Commission
    ============================================================ */
    public function paymentSuccess(Request $request)
    {
        try {
            Log::info('Eproduct EPS Success Callback', ['request' => $request->all()]);

            $sessionData = session('eproduct_payment_data', []);

            $merchantTransactionId =
                $request->merchant_transaction_id ??
                $request->MerchantTransactionId ??
                $request->merchantTransactionId ??
                $sessionData['merchant_transaction_id'] ??
                null;

            if (! $merchantTransactionId) {
                session()->forget('eproduct_payment_data');

                return redirect()->route('user.eproduct.shop')
                    ->with('error', 'Transaction তথ্য পাওয়া যায়নি।');
            }

            /* ===== Duplicate check ===== */
            $existingOrder = EproductOrder::where('merchant_transaction_id', $merchantTransactionId)->first();
            if ($existingOrder) {
                session()->forget('eproduct_payment_data');

                return redirect()
                    ->route('user.eproduct.shop.show', $existingOrder->eproduct->slug ?? '')
                    ->with('success', 'এই payment ইতিমধ্যে প্রক্রিয়াকৃত হয়েছে।');
            }

            /* ===== EPS Verify ===== */
            $epsPayment = new EPSPayment;
            $verifyResponse = $epsPayment->checkPaymentStatus($merchantTransactionId);

            Log::info('Eproduct EPS Verify Response', [
                'merchant_transaction_id' => $merchantTransactionId,
                'verify_response'         => $verifyResponse,
            ]);

            if (! $verifyResponse || ! isset($verifyResponse['isSuccess']) || $verifyResponse['isSuccess'] !== true) {
                session()->forget('eproduct_payment_data');

                return redirect()->route('user.eproduct.shop')
                    ->with('error', 'Payment Verify করা সম্ভব হয়নি।');
            }

            $epsStatus = strtolower(trim($verifyResponse['Status'] ?? ''));
            if ($epsStatus !== 'success') {
                session()->forget('eproduct_payment_data');

                return redirect()->route('user.eproduct.shop')
                    ->with('error', 'Payment সফল হয়নি। Status: ' . ($verifyResponse['Status'] ?? 'Unknown'));
            }

            $verifiedMerchantTransactionId = $verifyResponse['MerchantTransactionId'] ?? null;
            if ($verifiedMerchantTransactionId && $verifiedMerchantTransactionId !== $merchantTransactionId) {
                session()->forget('eproduct_payment_data');

                return redirect()->route('user.eproduct.shop')
                    ->with('error', 'Transaction ID verification ব্যর্থ হয়েছে।');
            }

            $originalAmount = (float) ($sessionData['amount'] ?? 0);
            $verifiedAmount = (float) ($verifyResponse['TotalAmount'] ?? 0);

            if ($originalAmount > 0 && abs($originalAmount - $verifiedAmount) > 0.01) {
                session()->forget('eproduct_payment_data');

                return redirect()->route('user.eproduct.shop')
                    ->with('error', 'Payment amount verification ব্যর্থ হয়েছে।');
            }

            $epsTransactionId =
                $verifyResponse['EpsTransactionId'] ??
                $verifyResponse['EPSTransactionId'] ??
                null;

            /* ===== DB Transaction ===== */
            DB::beginTransaction();

            try {
                $buyer   = User::findOrFail($sessionData['user_id'] ?? Auth::id());
                $product = Eproduct::findOrFail($sessionData['eproduct_id']);

                /* ============================================================
                   ✅ COMMISSION BASE — শুধু product এর commission (shipping নয়)
                   50% + 25% + 15% + 10% = 100%
                ============================================================ */
                $commissionBase = (float) $product->commission;

                Log::info('Eproduct Commission Distribution', [
                    'eproduct_id'     => $product->id,
                    'product_name'    => $product->product_name,
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
                    'order_no'                => $sessionData['order_no'] ?? ('PORD-' . strtoupper(Str::random(10))),

                    /* Buyer */
                    'buyer_name'              => $buyer->full_name,
                    'buyer_email'             => $buyer->email,
                    'buyer_phone'             => $buyer->phone,

                    /* Shipping */
                    'shipping_name'           => $sessionData['shipping_name'] ?? $buyer->full_name,
                    'shipping_phone'          => $sessionData['shipping_phone'] ?? $buyer->phone,
                    'shipping_address'        => $sessionData['shipping_address'] ?? ($buyer->address ?? ''),
                    'shipping_city'           => $sessionData['shipping_city'] ?? ($buyer->city ?? 'Dhaka'),
                    'shipping_postcode'       => $sessionData['shipping_postcode'] ?? null,

                    /* Delivery */
                    'delivery_type'           => $sessionData['delivery_type'] ?? 'inside_dhaka',
                    'shipping_charge'         => (float) ($sessionData['shipping_charge'] ?? 0),

                    /* Pricing */
                    'price'                   => $product->price,
                    'discount'                => $product->discount,
                    'product_total'           => (float) ($sessionData['product_total'] ?? $verifiedAmount),
                    'paid_amount'             => $verifiedAmount,

                    /* Payment */
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

                /* ----- Decrement stock ----- */
                if ($product->quantity > 0) {
                    $product->decrement('quantity');
                }

                /* ----- Transaction log ----- */
                Transaction::create([
                    'from_id'   => $buyer->id,
                    'user_id'   => $buyer->id,
                    'from_user' => $buyer->id,
                    'out'       => 'in',
                    'status'    => 'success',
                    'purpose'   => 'Eproduct Purchase: ' . $product->product_name,
                    'amount'    => $verifiedAmount,
                ]);

                /* ----- Commission distribute ----- */
                $this->referEproductCommission($buyer, $commissionBase, $product, $order);

                DB::commit();

                session()->forget('eproduct_payment_data');

                return redirect()
                    ->route('user.eproduct.shop.show', $product->slug)
                    ->with('success', '🎉 অভিনন্দন! আপনার অর্ডার সফলভাবে সম্পন্ন হয়েছে।');

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }

        } catch (\Exception $e) {
            DB::rollBack();
            session()->forget('eproduct_payment_data');

            Log::error('Eproduct EPS Success Error', [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
                'request' => $request->all(),
            ]);

            return redirect()->route('user.eproduct.shop')
                ->with('error', 'Payment verification এ সমস্যা হয়েছে।');
        }
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
            Log::warning('Eproduct Commission Skipped - base=0', [
                'eproduct_id' => $product->id,
                'user_id'     => $user->id,
            ]);
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
       PAYMENT FAIL / CANCEL
    ============================================================ */
    public function paymentFail(Request $request)
    {
        Log::info('Eproduct EPS Fail Callback', $request->all());
        session()->forget('eproduct_payment_data');

        return redirect()->route('user.eproduct.shop')
            ->with('error', '❌ Payment ব্যর্থ হয়েছে। টাকা কাটা হয়নি। আবার চেষ্টা করুন।');
    }

    public function paymentCancel(Request $request)
    {
        Log::info('Eproduct EPS Cancel Callback', $request->all());
        session()->forget('eproduct_payment_data');

        return redirect()->route('user.eproduct.shop')
            ->with('error', 'Payment বাতিল করা হয়েছে। টাকা কাটা হয়নি।');
    }

    /* ============================================================
       ORDERS LIST
    ============================================================ */
    public function orders()
    {
        $pageTitle = 'আমার প্রোডাক্ট অর্ডার';

        $orders = EproductOrder::where('user_id', Auth::id())
            ->with('eproduct')
            ->latest()
            ->paginate(15);

        return view('user.eshop.eproduct.orders', compact('pageTitle', 'orders'));
    }

    /* ============================================================
       ORDER VOUCHER
    ============================================================ */
    public function voucher($orderNo)
    {
        $order = EproductOrder::where('order_no', $orderNo)
            ->where('user_id', Auth::id())
            ->with(['user', 'eproduct'])
            ->firstOrFail();

        return view('user.eshop.eproduct.voucher', compact('order'));
    }

    /* ============================================================
       PUBLIC VOUCHER (Signed URL)
    ============================================================ */
    public function publicVoucher($orderNo)
    {
        $order = EproductOrder::where('order_no', $orderNo)
            ->with(['user', 'eproduct'])
            ->firstOrFail();

        return view('user.eshop.eproduct.voucher', compact('order'));
    }
}