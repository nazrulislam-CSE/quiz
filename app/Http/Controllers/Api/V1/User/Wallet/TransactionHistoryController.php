<?php

namespace App\Http\Controllers\Api\V1\User\Wallet;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionHistoryController extends Controller
{
    /**
     * Transaction History
     */
    public function transactionHistory(Request $request)
    {
        $user = Auth::user();

        // =====================================================
        // BASE QUERY
        // শুধু user_id
        // কারণ History = আমার Wallet-এ কী হয়েছে
        // =====================================================

        $query = Transaction::where(
            'user_id',
            $user->id
        );

        // =====================================================
        // DATE FILTER
        // =====================================================

        if ($request->filled('from_date')) {

            $query->whereDate(
                'created_at',
                '>=',
                $request->from_date
            );
        }

        if ($request->filled('to_date')) {

            $query->whereDate(
                'created_at',
                '<=',
                $request->to_date
            );
        }

        // =====================================================
        // TRANSACTIONS
        // =====================================================

        $transactions = $query
            ->latest()
            ->paginate(15)
            ->withQueryString();


        // =====================================================
        // MAIN WALLET
        //
        // Credit:
        // EPS Deposit
        // Deposit Commission
        //
        // Debit:
        // Exam Fee
        // =====================================================

        $mainWalletCredit = Transaction::where(
                'user_id',
                $user->id
            )
            ->where('out', 'in')
            ->whereIn('purpose', [
                'EPS Balance Deposit',
                'Deposit Commission',
            ])
            ->whereIn('status', [
                'success',
                'approved',
            ])
            ->sum('amount');


        $mainWalletDebit = Transaction::where(
                'user_id',
                $user->id
            )
            ->where('out', 'exam_fee')
            ->whereIn('status', [
                'success',
                'approved',
            ])
            ->sum('amount');


        $mainWalletTransactionBalance =
            $mainWalletCredit -
            $mainWalletDebit;


        // =====================================================
        // INCOME WALLET
        //
        // Credit:
        // Referral Commission
        // Generation Commission
        // Recharge Commission
        //
        // Debit:
        // Withdraw
        // Recharge
        // =====================================================

        $incomeWalletCredit = Transaction::where(
                'user_id',
                $user->id
            )
            ->whereIn('out', [
                'referral',
                'deposit',
            ])
            ->whereIn('purpose', [

                'Direct Referral Commission',

                '1st Generation Referral Commission',

                '2nd Generation Referral Commission',

                'Recharge Self Commission',

                'Recharge Referrer Commission',

                'Recharge 1st Gen Commission',

                'Recharge 2nd Gen Commission',

            ])
            ->whereIn('status', [
                'success',
                'approved',
            ])
            ->sum('amount');


        $incomeWalletDebit = Transaction::where(
                'user_id',
                $user->id
            )
            ->whereIn('out', [
                'withdraw',
                'recharge',
            ])
            ->whereIn('status', [
                'success',
                'approved',
            ])
            ->sum('amount');


        $incomeWalletTransactionBalance =
            $incomeWalletCredit -
            $incomeWalletDebit;


        // =====================================================
        // CURRENT ACTUAL WALLET BALANCE
        // User table থেকে বর্তমান balance
        // =====================================================

        $mainWalletBalance =
            (float) $user->main_wallet;

        $incomeWalletBalance =
            (float) $user->income_wallet;


        // =====================================================
        // TOTAL
        // =====================================================

        $totalAmount =
            $transactions->getCollection()->sum('amount');

        $totalTransactions =
            $transactions->total();


        // =====================================================
        // TRANSACTION DATA
        // =====================================================

        $transactionData = $transactions
            ->getCollection()
            ->map(function ($transaction) {

                // Default type
                $type = 'credit';

                // Debit transaction
                if (
                    in_array(
                        $transaction->out,
                        [
                            'exam_fee',
                            'withdraw',
                            'recharge',
                        ]
                    )
                ) {
                    $type = 'debit';
                }

                // Transfer transaction
                if (
                    $transaction->out === 'transfer'
                ) {
                    $type = 'transfer';
                }

                return [

                    'id' =>
                        $transaction->id,

                    'from_id' =>
                        $transaction->from_id,

                    'user_id' =>
                        $transaction->user_id,

                    'from_user' =>
                        $transaction->from_user,

                    'type' =>
                        $type,

                    'transaction_type' =>
                        $transaction->out,

                    'status' =>
                        $transaction->status,

                    'purpose' =>
                        $transaction->purpose,

                    'amount' =>
                        (float) $transaction->amount,

                    'date' =>
                        $transaction->created_at
                            ? $transaction->created_at
                                ->format('Y-m-d H:i:s')
                            : null,
                ];
            })
            ->values();


        // =====================================================
        // API RESPONSE
        // =====================================================

        return response()->json([

            'success' => true,

            'message' =>
                'Transaction history retrieved successfully.',

            'data' => [

                // =================================================
                // WALLET SUMMARY
                // =================================================

                'wallet' => [

                    'main_wallet' => [

                        'balance' =>
                            $mainWalletBalance,

                        'total_credit' =>
                            (float) $mainWalletCredit,

                        'total_debit' =>
                            (float) $mainWalletDebit,

                        'transaction_balance' =>
                            (float) $mainWalletTransactionBalance,
                    ],

                    'income_wallet' => [

                        'balance' =>
                            $incomeWalletBalance,

                        'total_credit' =>
                            (float) $incomeWalletCredit,

                        'total_debit' =>
                            (float) $incomeWalletDebit,

                        'transaction_balance' =>
                            (float) $incomeWalletTransactionBalance,
                    ],

                    'total_wallet_balance' =>
                        $mainWalletBalance +
                        $incomeWalletBalance,
                ],


                // =================================================
                // TRANSACTIONS
                // =================================================

                'transactions' =>
                    $transactionData,


                // =================================================
                // SUMMARY
                // =================================================

                'summary' => [

                    'total_amount' =>
                        (float) $totalAmount,

                    'total_transactions' =>
                        $totalTransactions,
                ],


                // =================================================
                // PAGINATION
                // =================================================

                'pagination' => [

                    'current_page' =>
                        $transactions->currentPage(),

                    'last_page' =>
                        $transactions->lastPage(),

                    'per_page' =>
                        $transactions->perPage(),

                    'total' =>
                        $transactions->total(),

                    'from' =>
                        $transactions->firstItem(),

                    'to' =>
                        $transactions->lastItem(),

                    'next_page_url' =>
                        $transactions->nextPageUrl(),

                    'previous_page_url' =>
                        $transactions->previousPageUrl(),
                ],
            ],
        ]);
    }
}
