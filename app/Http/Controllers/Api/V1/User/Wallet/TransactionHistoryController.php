<?php

namespace App\Http\Controllers\Api\V1\User\Wallet;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionHistoryController extends Controller
{
    public function transactionHistory(Request $request)
    {
        $userId = auth()->id();

        $fromDate = $request->from_date;
        $toDate   = $request->to_date;

        // Validation
        $request->validate([
            'from_date' => ['nullable', 'date'],
            'to_date'   => ['nullable', 'date', 'after_or_equal:from_date'],
            'per_page'  => ['nullable', 'integer', 'min:1', 'max:100'],
        ]);

        // শুধুমাত্র লগইন করা User-এর নিজের Transaction
        $query = Transaction::where('user_id', $userId)
            ->when($fromDate, function ($query) use ($fromDate) {
                $query->whereDate('created_at', '>=', $fromDate);
            })
            ->when($toDate, function ($query) use ($toDate) {
                $query->whereDate('created_at', '<=', $toDate);
            });

        // Summary
        $totalAmount = (clone $query)->sum('amount');

        $totalTransactions = (clone $query)->count();

        $totalReceived = (clone $query)->sum('amount');

        // Pagination
        $perPage = $request->integer('per_page', 15);

        $transactions = (clone $query)
            ->latest()
            ->paginate($perPage);

        return response()->json([
            'success' => true,

            'message' => 'লেনদেনের ইতিহাস সফলভাবে পাওয়া গেছে।',

            'summary' => [
                'total_received'    => (float) $totalReceived,
                'total_transactions' => $totalTransactions,
                'total_amount'      => (float) $totalAmount,
            ],

            'transactions' => $transactions->items(),

            'pagination' => [
                'current_page' => $transactions->currentPage(),
                'last_page'    => $transactions->lastPage(),
                'per_page'     => $transactions->perPage(),
                'total'        => $transactions->total(),
                'from'         => $transactions->firstItem(),
                'to'           => $transactions->lastItem(),

                'next_page_url' => $transactions->nextPageUrl(),
                'prev_page_url' => $transactions->previousPageUrl(),
            ],

            'filters' => [
                'from_date' => $fromDate,
                'to_date'   => $toDate,
            ],
        ]);
    }
}
