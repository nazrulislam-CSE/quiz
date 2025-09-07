<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BalanceRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class BalanceRequestController extends Controller
{
    public function index()
    {
        $pageTitle = "Balance Request List";
        $requests = BalanceRequest::latest()->get();

        return view('admin.balance.index', compact('pageTitle', 'requests'));
    }

    public function show(string $id)
    {
        $pageTitle = 'Balance Request Show';
        $request = BalanceRequest::with('user')->findOrFail($id);
        return view('admin.balance.show', compact('pageTitle', 'request'));
    }


    public function edit(string $id)
    {
        $request = BalanceRequest::find($id);
        $pageTitle = 'Balance Request Edit';
        return view('admin.balance.edit', compact('request','pageTitle'));
    }

    public function update(Request $request, string $id)
    {
        // dd($request->all());
        $balanceRequest = BalanceRequest::findOrFail($id);

        $request->validate([
            'status' => 'required|in:pending,approved,rejected',
        ]);

        $balanceRequest->update([
            'status' => $request->status,
            'amount' => $balanceRequest->amount + 0.20,
        ]);

        // ---- Only run commission logic if status is approved ----
        if ($request->status === 'approved') {
            $user   = User::find($balanceRequest->user_id); // Balance request owner
            // dd($user);
            $amount = $balanceRequest->amount;
           

            // Direct Referrer (20%)
            if ($user && $user->refer_by) {
                $directRef = User::find($user->refer_by);
                // dd($directRef);
                if ($directRef) {
                    $directRef->increment('direct_commission', $amount / 100 * 0.20);
                }

                // 1st Generation Referrer (10%)
                if ($directRef && $directRef->refer_by) {
                    $firstGen = User::find($directRef->refer_by);
                    if ($firstGen) {
                        $firstGen->increment('first_gen_commission', $amount / 100 * 0.10);
                    }

                    // 2nd Generation Referrer (5%)
                    if ($firstGen && $firstGen->refer_by) {
                        $secondGen = User::find($firstGen->refer_by);
                        if ($secondGen) {
                            $secondGen->increment('second_gen_commission', $amount / 100 * 0.5);
                        }
                    }
                }
            }
            $user->visa_amount += $amount / 100 * 0.20;
            $user->save();
        }

        return redirect()->route('admin.balance.request.index')
                        ->with('success','Balance request status updated successfully.');
    }

    public function destroy(string $id)
    {
        $balanceRequest = BalanceRequest::findOrFail($id);

        try {
            if(file_exists($balanceRequest->screenshot)){
                unlink($balanceRequest->screenshot);
            }
        } catch (Exception $e) {

        }
        
        $balanceRequest->delete();

        return redirect()->route('admin.balance.request.index')
                        ->with('success','Balance request deleted successfully.');
    }

    

}
