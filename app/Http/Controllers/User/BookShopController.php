<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\BookOrder;
use App\Models\Ebook;
use App\Models\Generation;
use App\Models\Transaction;
use App\Models\User;
use App\EPS\EPSPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class BookShopController extends Controller
{
    /* ============================================================
       INDEX
    ============================================================ */
    public function index()
    {
        $pageTitle = 'আমাদের বুক শপ';

        $books = Ebook::where('status', 'active')
            ->latest()
            ->paginate(12);

        return view('user.eshop.book.index', compact('pageTitle', 'books'));
    }

    /* ============================================================
       SHOW
    ============================================================ */
    public function show($slug)
    {
        $pageTitle = 'বইয়ের বিবরণ';

        $book = Ebook::where('slug', $slug)
            ->where('status', 'active')
            ->firstOrFail();

        $book->increment('view_count');

        $relatedBooks = Ebook::where('status', 'active')
            ->where('id', '!=', $book->id)
            ->where('author_name', $book->author_name)
            ->latest()
            ->take(4)
            ->get();

        return view('user.eshop.book.show', compact('pageTitle', 'book', 'relatedBooks'));
    }

    /* ============================================================
       CHECKOUT — EPS Payment Init
    ============================================================ */
    public function checkout(Request $request, $slug)
    {
        $buyer = Auth::user();

        $book = Ebook::where('slug', $slug)
            ->where('status', 'active')
            ->firstOrFail();

        if ($book->quantity <= 0) {
            return back()->with('error', 'এই বইটি স্টকে নেই।');
        }

        $amount = (float) ($book->sell_price > 0 ? $book->sell_price : $book->price);

        if ($amount <= 0) {
            return back()->with('error', 'Invalid payment amount.');
        }

        try {
            $customerOrderId       = 'BOK_' . now()->format('YmdHis') . '_' . rand(1000, 9999);
            $merchantTransactionId = 'EBS' . now()->format('YmdHis') . rand(10000, 99999);
            $orderNo               = 'BORD-' . strtoupper(Str::random(10));

            /* ===== Store in session for callback ===== */
            session([
                'book_payment_data' => [
                    'user_id'                 => $buyer->id,
                    'ebook_id'                => $book->id,
                    'amount'                  => $amount,
                    'order_no'                => $orderNo,
                    'merchant_transaction_id' => $merchantTransactionId,
                    'customer_order_id'       => $customerOrderId,
                ],
            ]);

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
                'ipAddress'        => $request->ip(),
                'successUrl'       => route('user.book.payment.success'),
                'failUrl'          => route('user.book.payment.fail'),
                'cancelUrl'        => route('user.book.payment.cancel'),
                'customerName'     => $buyer->full_name ?? '',
                'customerEmail'    => $buyer->email ?? '',
                'customerAddress'  => $buyer->address ?? '',
                'customerAddress2' => '',
                'customerCity'     => $buyer->city ?? 'Dhaka',
                'customerState'    => $buyer->state ?? 'Dhaka',
                'customerPostcode' => $buyer->postcode ?? '1000',
                'customerCountry'  => 'BD',
                'customerPhone'    => $buyer->phone ?? '01700000000',
                'shipmentName'     => $buyer->full_name ?? '',
                'shipmentAddress'  => $buyer->address ?? '',
                'shipmentAddress2' => '',
                'shipmentCity'     => $buyer->city ?? 'Dhaka',
                'shipmentState'    => $buyer->state ?? 'Dhaka',
                'shipmentPostcode' => $buyer->postcode ?? '1000',
                'shipmentCountry'  => 'BD',
                'valueA'           => (string) $buyer->id,
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

            if (! $data || ! isset($data['isSuccess']) || $data['isSuccess'] !== true) {
                session()->forget('book_payment_data');
                Log::error('Book EPS Init Failed', ['data' => $data]);

                return back()->with('error', 'Payment gateway থেকে response পাওয়া যায়নি।');
            }

            $paymentUrl = $data['RedirectURL'] ?? $data['payment_url'] ?? null;

            if (! $paymentUrl) {
                session()->forget('book_payment_data');
                Log::error('Book EPS URL Missing', ['response' => $data]);

                return back()->with('error', 'Payment URL পাওয়া যায়নি।');
            }

            session([
                'book_payment_data' => array_merge(
                    session('book_payment_data', []),
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
            session()->forget('book_payment_data');
            Log::error('Book EPS Init Error', [
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
            Log::info('Book EPS Success Callback', ['request' => $request->all()]);

            $sessionData = session('book_payment_data', []);

            /* ===== STEP 1: Merchant Transaction ID ===== */
            $merchantTransactionId =
                $request->merchant_transaction_id ??
                $request->MerchantTransactionId ??
                $request->merchantTransactionId ??
                $sessionData['merchant_transaction_id'] ??
                null;

            if (! $merchantTransactionId) {
                session()->forget('book_payment_data');

                return redirect()->route('user.book.shop')
                    ->with('error', 'Transaction তথ্য পাওয়া যায়নি।');
            }

            /* ===== STEP 2: Duplicate Check ===== */
            $existingOrder = BookOrder::where('merchant_transaction_id', $merchantTransactionId)->first();
            if ($existingOrder) {
                session()->forget('book_payment_data');

                return redirect()
                    ->route('user.book.shop.show', $existingOrder->ebook->slug ?? '')
                    ->with('success', 'এই payment ইতিমধ্যে প্রক্রিয়াকৃত হয়েছে।');
            }

            /* ===== STEP 3: EPS Verify ===== */
            $epsPayment = new EPSPayment;
            $verifyResponse = $epsPayment->checkPaymentStatus($merchantTransactionId);

            Log::info('Book EPS Verify Response', [
                'merchant_transaction_id' => $merchantTransactionId,
                'verify_response'         => $verifyResponse,
            ]);

            /* ===== STEP 4: Verify API Success ===== */
            if (
                ! $verifyResponse ||
                ! isset($verifyResponse['isSuccess']) ||
                $verifyResponse['isSuccess'] !== true
            ) {
                session()->forget('book_payment_data');

                return redirect()->route('user.book.shop')
                    ->with('error', 'Payment Verify করা সম্ভব হয়নি।');
            }

            /* ===== STEP 5: EPS Status ===== */
            $epsStatus = strtolower(trim($verifyResponse['Status'] ?? ''));
            if ($epsStatus !== 'success') {
                session()->forget('book_payment_data');

                return redirect()->route('user.book.shop')
                    ->with('error', 'Payment সফল হয়নি। Status: ' . ($verifyResponse['Status'] ?? 'Unknown'));
            }

            /* ===== STEP 6: Txn ID Match ===== */
            $verifiedMerchantTransactionId = $verifyResponse['MerchantTransactionId'] ?? null;
            if ($verifiedMerchantTransactionId && $verifiedMerchantTransactionId !== $merchantTransactionId) {
                session()->forget('book_payment_data');

                return redirect()->route('user.book.shop')
                    ->with('error', 'Transaction ID verification ব্যর্থ হয়েছে।');
            }

            /* ===== STEP 7: Amount Match ===== */
            $originalAmount = (float) ($sessionData['amount'] ?? 0);
            $verifiedAmount = (float) ($verifyResponse['TotalAmount'] ?? 0);

            if ($originalAmount > 0 && abs($originalAmount - $verifiedAmount) > 0.01) {
                session()->forget('book_payment_data');

                return redirect()->route('user.book.shop')
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
                $buyer = User::findOrFail($sessionData['user_id'] ?? Auth::id());
                $book  = Ebook::findOrFail($sessionData['ebook_id']);

                $commissionBase = (float) ($book->sell_price > 0 ? $book->sell_price : $book->price);

                /* ----- Create Book Order ----- */
                $order = BookOrder::create([
                    'user_id'                 => $buyer->id,
                    'ebook_id'                => $book->id,
                    'order_no'                => $sessionData['order_no'] ?? ('BORD-' . strtoupper(Str::random(10))),

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
                        'initialize_response' => $sessionData['gateway_response'] ?? [],
                        'success_callback'    => $request->all(),
                        'verify_response'     => $verifyResponse,
                    ],

                    'payment_status' => 'paid',
                    'status'         => 'completed',
                    'paid_at'        => now(),
                ]);

                /* ----- Decrement stock ----- */
                if ($book->quantity > 0) {
                    $book->decrement('quantity');
                }

                /* ----- Transaction: Book Purchase ----- */
                Transaction::create([
                    'from_id'   => $buyer->id,
                    'user_id'   => $buyer->id,
                    'from_user' => $buyer->id,
                    'out'       => 'in',
                    'status'    => 'success',
                    'purpose'   => 'Book Purchase: ' . $book->book_name,
                    'amount'    => $verifiedAmount,
                ]);

                /* ----- Commission distribute ----- */
                $this->referBookCommission($buyer, $commissionBase, $book, $order);

                DB::commit();

                session()->forget('book_payment_data');

                return redirect()
                    ->route('user.book.shop.show', $book->slug)
                    ->with('success', '🎉 অভিনন্দন! আপনার বই সফলভাবে কেনা হয়েছে।');

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }

        } catch (\Exception $e) {
            DB::rollBack();
            session()->forget('book_payment_data');

            Log::error('Book EPS Success Error', [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
                'request' => $request->all(),
            ]);

            return redirect()->route('user.book.shop')
                ->with('error', 'Payment verification এ সমস্যা হয়েছে।');
        }
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

        /* ===== STEP 1: Direct Referrer (50%) ===== */
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
                'purpose'   => 'Direct Referral Book Commission (' . $directPercent . '%) - ' . $book->book_name,
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

        /* ===== STEP 2: 1st Gen (25%) ===== */
        $firstGenId = $directReferrer->refer_by;
        if (! $firstGenId) {
            return true;
        }

        $firstGen = User::lockForUpdate()->find($firstGenId);
        if (! $firstGen
            || $firstGen->id === $user->id
            || $firstGen->id === $directReferrer->id
        ) {
            return true;
        }

        $firstCommission = ($baseAmount * $firstPercent) / 100;
        if ($firstCommission > 0) {
            $firstGen->increment('income_wallet', $firstCommission);

            Transaction::create([
                'from_id'   => $user->id,
                'user_id'   => $firstGen->id,
                'from_user' => $user->id,
                'out'       => 'referral',
                'status'    => 'success',
                'purpose'   => '1st Gen Book Commission (' . $firstPercent . '%) - ' . $book->book_name,
                'amount'    => $firstCommission,
            ]);

            Generation::create([
                'from_user_id' => $user->id,
                'to_user_id'   => $firstGen->id,
                'level'        => 2,
                'date'         => now(),
                'status'       => 1,
                'commission'   => $firstCommission,
                'total_amount' => $baseAmount,
            ]);
        }

        /* ===== STEP 3: 2nd Gen (15%) ===== */
        $secondGenId = $firstGen->refer_by;
        if (! $secondGenId) {
            return true;
        }

        $secondGen = User::lockForUpdate()->find($secondGenId);
        if (! $secondGen
            || in_array($secondGen->id, [$user->id, $directReferrer->id, $firstGen->id])
        ) {
            return true;
        }

        $secondCommission = ($baseAmount * $secondPercent) / 100;
        if ($secondCommission > 0) {
            $secondGen->increment('income_wallet', $secondCommission);

            Transaction::create([
                'from_id'   => $user->id,
                'user_id'   => $secondGen->id,
                'from_user' => $user->id,
                'out'       => 'referral',
                'status'    => 'success',
                'purpose'   => '2nd Gen Book Commission (' . $secondPercent . '%) - ' . $book->book_name,
                'amount'    => $secondCommission,
            ]);

            Generation::create([
                'from_user_id' => $user->id,
                'to_user_id'   => $secondGen->id,
                'level'        => 3,
                'date'         => now(),
                'status'       => 1,
                'commission'   => $secondCommission,
                'total_amount' => $baseAmount,
            ]);
        }

        /* ===== STEP 4: 3rd Gen (10%) ===== */
        $thirdGenId = $secondGen->refer_by;
        if (! $thirdGenId) {
            return true;
        }

        $thirdGen = User::lockForUpdate()->find($thirdGenId);
        if (! $thirdGen
            || in_array($thirdGen->id, [$user->id, $directReferrer->id, $firstGen->id, $secondGen->id])
        ) {
            return true;
        }

        $thirdCommission = ($baseAmount * $thirdPercent) / 100;
        if ($thirdCommission > 0) {
            $thirdGen->increment('income_wallet', $thirdCommission);

            Transaction::create([
                'from_id'   => $user->id,
                'user_id'   => $thirdGen->id,
                'from_user' => $user->id,
                'out'       => 'referral',
                'status'    => 'success',
                'purpose'   => '3rd Gen Book Commission (' . $thirdPercent . '%) - ' . $book->book_name,
                'amount'    => $thirdCommission,
            ]);

            Generation::create([
                'from_user_id' => $user->id,
                'to_user_id'   => $thirdGen->id,
                'level'        => 4,
                'date'         => now(),
                'status'       => 1,
                'commission'   => $thirdCommission,
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
        Log::info('Book EPS Fail Callback', $request->all());
        session()->forget('book_payment_data');

        return redirect()->route('user.book.shop')
            ->with('error', '❌ Payment ব্যর্থ হয়েছে। টাকা কাটা হয়নি। আবার চেষ্টা করুন।');
    }
 
    public function paymentCancel(Request $request)
    {
        Log::info('Book EPS Cancel Callback', $request->all());
        session()->forget('book_payment_data');

        return redirect()->route('user.book.shop')
            ->with('error', 'Payment বাতিল করা হয়েছে। টাকা কাটা হয়নি।');
    }

    /* ============================================================
       ORDERS LIST
    ============================================================ */
    public function orders()
    {
        $pageTitle = 'আমার বইয়ের অর্ডার';

        $orders = BookOrder::where('user_id', Auth::id())
            ->with('ebook')
            ->latest()
            ->paginate(15);

        return view('user.eshop.book.order', compact('pageTitle', 'orders'));
    }

    /* ============================================================
    BOOK ORDER VOUCHER
    ============================================================ */
    public function voucher($orderNo)
    {
        $order = BookOrder::where('order_no', $orderNo)
            ->where('user_id', Auth::id())
            ->with(['user', 'ebook'])
            ->firstOrFail();

        return view('user.eshop.book.voucher', compact('order'));
    }
}