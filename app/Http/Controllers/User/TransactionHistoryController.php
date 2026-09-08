<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionHistoryController extends Controller
{
    public function transactionHistory(Request $request)
    {
        $pageTitle = "লেনদেনের ইতিহাস";
        $userId = auth()->id();

        $fromDate = $request->from_date;
        $toDate   = $request->to_date;

        // শুধুমাত্র লগইন করা User-এর নিজের Transaction
        $query = Transaction::where('user_id', $userId)
            ->when($fromDate, function ($query) use ($fromDate) {
                $query->whereDate('created_at', '>=', $fromDate);
            })
            ->when($toDate, function ($query) use ($toDate) {
                $query->whereDate('created_at', '<=', $toDate);
            });

        // মোট লেনদেনের পরিমাণ
        $totalAmount = (clone $query)->sum('amount');

        // মোট লেনদেন
        $totalTransactions = (clone $query)->count();

        // মোট পাওয়া টাকা
        $totalReceived = (clone $query)->sum('amount');

        // Transaction List
        $transactions = (clone $query)
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('user.transaction.history', compact(
            'pageTitle',
            'transactions',
            'totalAmount',
            'totalTransactions',
            'totalReceived',
            'fromDate',
            'toDate'
        ));
    }
}