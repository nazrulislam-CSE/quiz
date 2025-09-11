<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Withdraw;
use App\Models\User;
use App\Models\Transaction;
use App\Models\Generation;
use App\Models\Commission;
use Illuminate\Support\Facades\Auth;

class WithdrawController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   public function index(Request $request)
    {
        $pageTitle = "Withdraw Request List";

        $status = $request->query('status', 'pending');
        $date = $request->query('date');

        $requests = Withdraw::with('user')
            ->when($status, function ($q) use ($status) {
                return $q->where('status', $status);
            })
            ->when($date, function ($q) use ($date) {
                return $q->whereDate('created_at', $date);
            })
            ->latest()
            ->get();

        return view('admin.withdraw.index', compact('pageTitle', 'requests', 'status', 'date'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pageTitle = 'Withdraw Request Show';
        $request = Withdraw::with('user')->findOrFail($id);
        return view('admin.withdraw.show', compact('pageTitle', 'request'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $request = Withdraw::find($id);
        $pageTitle = 'Withdraw Request Edit';
        return view('admin.withdraw.edit', compact('request','pageTitle'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // dd($request->all());
        $withdraw = Withdraw::findOrFail($id);

        $request->validate([
            'status' => 'required|in:pending,approved,rejected',
        ]);

        if ($request->status === 'approved') {
            $user   = User::find($withdraw->user_id);
            $amount = $withdraw->amount;


            if ($user->income_wallet < $amount) {
                return back()->with('error', 'Insufficient balance in income wallet.');
            }


            $withdraw->update([
                'status' => $request->status,
            ]);

            $user->increment('withdraw_wallet', $withdraw->amount);
            $user->decrement('income_wallet', $withdraw->amount);

            // ✅ Log the transaction
            Transaction::create([
                'from_id'  => $user->id,
                'user_id'  => $user->id,
                'out'      => 'withdraw',
                'status'   => 'approved',
                'purpose'  => 'Withdraw Approved',
                'amount'   => $amount,
            ]);

        }

        return redirect()->route('admin.withdraw.request.index')
                        ->with('success','Withdraw request status updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $withdraw = Withdraw::findOrFail($id);
        $user = $withdraw->user;
        $amount = $withdraw->amount;

        if ($withdraw->status === 'approved') {
            // Reverse the fund transfer
            $user->decrement('withdraw_wallet', $amount);
            $user->increment('income_wallet', $amount);
        }

        $withdraw->delete();

        return redirect()->route('admin.withdraw.request.index')
                        ->with('success', 'Withdraw request deleted successfully.');
    }

}
