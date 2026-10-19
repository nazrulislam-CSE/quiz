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

        // =====================================================
        // BASE QUERY
        // ✅ শুধু user_id — from_id বাদ
        // কারণ: History = আমার wallet এ কী হলো
        // =====================================================
        $query = Transaction::where('user_id', $user->id);

        // Date Filter
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $transactions = $query->latest()->paginate(15)->withQueryString();


        // =====================================================
        // 💰 MAIN WALLET
        // Credit → EPS Deposit + Deposit Commission
        // Debit  → Exam Fee
        // =====================================================
        $mainWalletCredit = Transaction::where('user_id', $user->id)
            ->where('out', 'in')
            ->whereIn('purpose', [
                'EPS Balance Deposit',
                'Deposit Commission',
            ])
            ->whereIn('status', ['success', 'approved'])
            ->sum('amount');

        $mainWalletDebit = Transaction::where('user_id', $user->id)
            ->where('out', 'exam_fee')
            ->whereIn('status', ['success', 'approved'])
            ->sum('amount');

        $mainWalletBalance = $mainWalletCredit - $mainWalletDebit;


        // =====================================================
        // 💵 INCOME WALLET
        // Credit → Referral (out=referral) + Recharge Commission (out=deposit)
        // Debit  → Withdraw + Recharge
        // =====================================================
        $incomeWalletCredit = Transaction::where('user_id', $user->id)
            ->whereIn('out', ['referral', 'deposit'])
            ->whereIn('purpose', [
                'Direct Referral Commission',
                '1st Generation Referral Commission',
                '2nd Generation Referral Commission',
                'Recharge Self Commission',
                'Recharge Referrer Commission',
                'Recharge 1st Gen Commission',
                'Recharge 2nd Gen Commission',
            ])
            ->whereIn('status', ['success', 'approved'])
            ->sum('amount');

        $incomeWalletDebit = Transaction::where('user_id', $user->id)
            ->whereIn('out', ['withdraw', 'recharge'])
            ->whereIn('status', ['success', 'approved'])
            ->sum('amount');

        $incomeWalletBalance = $incomeWalletCredit - $incomeWalletDebit;


        // =====================================================
        // TOTAL
        // =====================================================
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