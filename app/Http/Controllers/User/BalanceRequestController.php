<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BalanceRequest;
use Illuminate\Support\Facades\Auth;

class BalanceRequestController extends Controller
{
    public function create()
    {
        $pageTitle = "Balance Request";
        return view('user.balance.request', compact('pageTitle'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'method' => 'required|string',
            'from_account' => 'required|string',
            'amount' => 'required|numeric|min:10',
            'trx_id' => 'nullable|string',
            'screenshot' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // $screenshotPath = null;
        // if ($request->hasFile('screenshot')) {
        //     $screenshotPath = $request->file('screenshot')->store('balance_screenshots', 'public');
        // }

        $balance = BalanceRequest::create([
            'user_id' => Auth::id(),
            'method' => $request->method,
            'from_account' => $request->from_account,
            'amount' => $request->amount,
            'trx_id' => $request->trx_id,
            'screenshot' => $screenshotPath,
            'status' => 'pending',
        ]);

        if ($request->file('screenshot')) {
            $file = $request->file('screenshot');
            @unlink(public_path('upload/balance/'.$balance->screenshot));
            $filename = date('YmdHi').$file->getClientOriginalName();
            $file->move(public_path('upload/balance'),$filename);
            $balance['screenshot'] = $filename;
        }

        $balance->save();


        return redirect()->route('user.balance.request')->with('success', 'Balance request submitted successfully!');
    }

    public function report()
    {
        $pageTitle = "Balance Request Report";
        $requests = BalanceRequest::where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('user.balance.report', compact('pageTitle', 'requests'));
    }
}
