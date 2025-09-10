<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Withdraw;
use App\Models\User;
use Carbon\Carbon;

class WithdrawController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = "Withdraw List";

        $user = Auth::user();

        $withdraws = Withdraw::where('user_id', auth()->id())
        ->latest()
        ->paginate(10);

        return view('user.withdraw.index', compact('pageTitle', 'withdraws'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pageTitle = "Withdraw Create";
        return view('user.withdraw.create', compact('pageTitle', 'withdraws'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'method' => 'required|string',
            'account_number' => 'required|string',
            'amount' => 'required|numeric|min:200', // min withdraw limit
        ]);

        $user = auth()->user();

        // check balance
        if ($user->main_wallet < $request->amount) {
            return back()->with('error', 'Your balance is not sufficient!');
        }

        // create withdraw request
        $withdraw = Withdraw::create([
            'user_id' => $user->id,
            'method' => $request->method,
            'account_number' => $request->account_number,
            'amount' => $request->amount,
            'status' => 'pending',
        ]);

        // decrease user wallet balance
        $user->decrement('main_wallet', $request->amount);

        // create transaction log
        Transaction::create([
            'from_id'  => $user->id,
            'user_id'  => $user->id,
            'out'      => 'withdraw',
            'status'   => 'pending',
            'purpose'  => 'Withdraw Request',
            'amount'   => $request->amount,
        ]);

        return redirect()
            ->route('user.withdraw.list')
            ->with('success', 'Withdraw request submitted successfully!');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
