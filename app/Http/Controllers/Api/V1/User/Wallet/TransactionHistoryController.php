<?php

namespace App\Http\Controllers\Api\V1\User\Wallet;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TransactionHistoryController extends Controller
{
    public function transactionHistory(Request $request)
    {
        $user = Auth::user();

        /* =====================================================
           BASE QUERY
        ===================================================== */
        $query = Transaction::where('user_id', $user->id);

        // Date Filter
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $transactions = $query->latest()->paginate(15)->withQueryString();

        /* =====================================================
           💰 ACTUAL WALLET BALANCES
           Source of truth = users table
           (Transaction sum থেকে recalculate না)
        ===================================================== */
        $mainWalletBalance   = (float) ($user->main_wallet   ?? 0);
        $incomeWalletBalance = (float) ($user->income_wallet ?? 0);

        /* =====================================================
           💰 MAIN WALLET — Credit / Debit (display purposes)
           Credit → EPS Deposit + Deposit Commission (Course Purchase বাদ)
           Debit  → Exam Fee
        ===================================================== */
        $mainWalletCredit = Transaction::where('user_id', $user->id)
            ->where('out', 'in')
            ->whereIn('status', ['success', 'approved'])
            ->where('purpose', 'not like', 'Course Purchase:%')
            ->sum('amount');

        $mainWalletDebit = Transaction::where('user_id', $user->id)
            ->where('out', 'exam_fee')
            ->whereIn('status', ['success', 'approved'])
            ->sum('amount');

        $mainWalletTransactionBalance = $mainWalletCredit - $mainWalletDebit;

        /* =====================================================
           💵 INCOME WALLET — Credit / Debit (display purposes)
           ✅ Purpose whitelist সরানো — সব referral+deposit যোগ
           (Course Commission purpose গুলো whitelist-এ ছিল না)
        ===================================================== */
        $incomeWalletCredit = Transaction::where('user_id', $user->id)
            ->whereIn('out', ['referral', 'deposit'])
            ->whereIn('status', ['success', 'approved'])
            ->sum('amount');

        $incomeWalletDebit = Transaction::where('user_id', $user->id)
            ->whereIn('out', ['withdraw', 'recharge'])
            ->whereIn('status', ['success', 'approved'])
            ->sum('amount');

        $incomeWalletTransactionBalance = $incomeWalletCredit - $incomeWalletDebit;

        /* =====================================================
           TOTAL
        ===================================================== */
        $totalAmount       = $transactions->getCollection()->sum('amount');
        $totalTransactions = $transactions->total();

        /* =====================================================
           TRANSACTION DATA — with proper type detection
        ===================================================== */
        $transactionData = $transactions->getCollection()->map(function ($transaction) {

            $purpose = $transaction->purpose ?? '';
            $out     = $transaction->out ?? '';

            /* ============================================================
               🎯 Course Purchase detection
               out='in' হলেও এটা আসলে Debit (user টাকা দিয়েছে)
            ============================================================ */
            $isCoursePurchase = ($out === 'in' && Str::startsWith($purpose, 'Course Purchase:'));

            /* ============================================================
               🎯 Type Determine
            ============================================================ */
            if ($isCoursePurchase) {
                $type = 'purchase';
            } elseif (in_array($out, ['exam_fee', 'withdraw', 'recharge'])) {
                $type = 'debit';
            } elseif ($out === 'transfer') {
                $type = 'transfer';
            } elseif (in_array($out, ['in', 'referral', 'deposit'])) {
                $type = 'credit';
            } else {
                $type = 'debit';
            }

            /* ============================================================
               🎯 Wallet Determine
            ============================================================ */
            if ($isCoursePurchase) {
                $wallet = 'course_purchase';
            } elseif ($out === 'in' || $out === 'exam_fee') {
                $wallet = 'main_wallet';
            } elseif (in_array($out, ['referral', 'deposit', 'withdraw', 'recharge'])) {
                $wallet = 'income_wallet';
            } elseif ($out === 'transfer') {
                $wallet = 'income_to_main';
            } else {
                $wallet = 'unknown';
            }

            return [
                'id'                 => $transaction->id,
                'from_id'            => $transaction->from_id,
                'user_id'            => $transaction->user_id,
                'from_user'          => $transaction->from_user,

                'type'               => $type,              // credit | debit | transfer | purchase
                'wallet'             => $wallet,            // main_wallet | income_wallet | course_purchase | income_to_main
                'transaction_type'   => $out,               // raw out column

                'is_course_purchase' => $isCoursePurchase,

                'status'             => $transaction->status,
                'purpose'            => $purpose,
                'amount'             => (float) $transaction->amount,
                'date'               => $transaction->created_at
                    ? $transaction->created_at->format('Y-m-d H:i:s')
                    : null,
            ];
        })->values();

        /* =====================================================
           API RESPONSE
        ===================================================== */
        return response()->json([
            'success' => true,
            'message' => 'Transaction history retrieved successfully.',

            'data' => [

                /* =================================================
                   WALLET SUMMARY
                ================================================= */
                'wallet' => [
                    'main_wallet' => [
                        'balance'             => $mainWalletBalance,
                        'total_credit'        => (float) $mainWalletCredit,
                        'total_debit'         => (float) $mainWalletDebit,
                        'transaction_balance' => (float) $mainWalletTransactionBalance,
                    ],

                    'income_wallet' => [
                        'balance'             => $incomeWalletBalance,
                        'total_credit'        => (float) $incomeWalletCredit,
                        'total_debit'         => (float) $incomeWalletDebit,
                        'transaction_balance' => (float) $incomeWalletTransactionBalance,
                    ],

                    'total_wallet_balance' => $mainWalletBalance + $incomeWalletBalance,
                ],

                /* =================================================
                   TRANSACTIONS
                ================================================= */
                'transactions' => $transactionData,

                /* =================================================
                   SUMMARY
                ================================================= */
                'summary' => [
                    'total_amount'       => (float) $totalAmount,
                    'total_transactions' => $totalTransactions,
                ],

                /* =================================================
                   PAGINATION
                ================================================= */
                'pagination' => [
                    'current_page'      => $transactions->currentPage(),
                    'last_page'         => $transactions->lastPage(),
                    'per_page'          => $transactions->perPage(),
                    'total'             => $transactions->total(),
                    'from'              => $transactions->firstItem(),
                    'to'                => $transactions->lastItem(),
                    'next_page_url'     => $transactions->nextPageUrl(),
                    'previous_page_url' => $transactions->previousPageUrl(),
                ],
            ],
        ]);
    }
}