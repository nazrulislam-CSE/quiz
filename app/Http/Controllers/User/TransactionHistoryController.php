<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionHistoryController extends Controller
{
    public function transactionHistory(Request $request)
    {
        $user = Auth::user();

        /* =====================================================
           BASE QUERY — শুধু user_id (আমার wallet-এ কী হলো)
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
           💰 WALLET BALANCES — Source of Truth = users table
           ✅ Transaction sum থেকে recalculate না করে
              users.main_wallet & users.income_wallet ব্যবহার
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

        /* =====================================================
           💵 INCOME WALLET — Credit / Debit (display purposes)
           ✅ Purpose whitelist সরিয়ে সব referral+deposit যোগ
           কারণ: Course Commission purpose গুলো whitelist-এ ছিল না
        ===================================================== */
        $incomeWalletCredit = Transaction::where('user_id', $user->id)
            ->whereIn('out', ['referral', 'deposit'])
            ->whereIn('status', ['success', 'approved'])
            ->sum('amount');

        $incomeWalletDebit = Transaction::where('user_id', $user->id)
            ->whereIn('out', ['withdraw', 'recharge'])
            ->whereIn('status', ['success', 'approved'])
            ->sum('amount');

        /* =====================================================
           TOTAL
        ===================================================== */
        $totalAmount       = $transactions->sum('amount');
        $totalTransactions = $transactions->total();

        $pageTitle = 'Transaction History';

        return view('user.transaction.history', compact(
            'transactions',
            'pageTitle',
            'totalAmount',
            'totalTransactions',

            // Main Wallet
            'mainWalletBalance',
            'mainWalletCredit',
            'mainWalletDebit',

            // Income Wallet
            'incomeWalletBalance',
            'incomeWalletCredit',
            'incomeWalletDebit',
        ));
    }
}