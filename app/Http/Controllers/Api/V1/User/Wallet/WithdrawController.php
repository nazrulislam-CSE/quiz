<?php

namespace App\Http\Controllers\Api\V1\User\Wallet;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Withdraw;
use App\Models\Transaction;

class WithdrawController extends Controller
{
    // ================= Withdraw List =================
    public function index()
    {
        $withdraws = Withdraw::where('user_id', auth()->id())
            ->latest()
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'method' => $item->method,
                    'account_number' => $item->account_number,
                    'amount' => $item->amount,
                    'status' => $item->status,
                    'created_at' => $item->created_at->format('d M Y h:i A'),
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $withdraws,
        ]);
    }

    // ================= Withdraw Request =================
    public function store(Request $request)
    {
        $request->validate([
            'method' => 'required|string',
            'account_number' => 'required|string',
            'amount' => 'required|numeric|min:200',
        ]);

        $user = auth()->user();

        if ($user->income_wallet < $request->amount) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient balance'
            ], 422);
        }

        $withdraw = Withdraw::create([
            'user_id' => $user->id,
            'method' => $request->method,
            'account_number' => $request->account_number,
            'amount' => $request->amount,
            'status' => 'pending',
        ]);

        // Optional: Transaction log
        Transaction::create([
            'from_id' => $user->id,
            'user_id' => $user->id,
            'out' => 'withdraw',
            'status' => 'pending',
            'purpose' => 'Withdraw Request',
            'amount' => $request->amount,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Withdraw request submitted successfully',
            'data' => $withdraw
        ]);
    }
}