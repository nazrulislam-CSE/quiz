<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BalanceTransfer;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class BalanceTransferController extends Controller
{
     // Show list of transfers
    public function index()
    {
        $pageTitle = "Balance Transfer List";
        $transfers = BalanceTransfer::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('user.transfer.index', compact('pageTitle', 'transfers'));
    }

    // Show transfer form
    public function create()
    {
        $pageTitle = "Balance Transfer";
        $user = Auth::user();
        return view('user.transfer.create', compact('pageTitle', 'user'));
    }

    // Store transfer
    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);

        $user = Auth::user();

        if ($request->amount > $user->income_wallet) {
            return back()->with('error', 'Insufficient balance in Income Wallet.');
        }

        // Deduct from income_wallet
        $user->income_wallet -= $request->amount;
        // Add to main_wallet
        $user->main_wallet += $request->amount;
        $user->save();

        // Record transfer
        BalanceTransfer::create([
            'user_id' => $user->id,
            'amount' => $request->amount,
            'status' => 'success',
        ]);

        Transaction::create([
            'user_id'      => $user->id,
            'from_id'      => $user->id,
            'from_user'    => $user->id,
            'out'         => 'transfer',
            'amount'       => $request->amount,
            'status'       => 'success',
            'purpose'      => 'Income to Main Wallet Transfer',
        ]);


        return redirect()->route('user.balance.transfer.index')->with('success', 'Balance transferred successfully.');
    }
}
