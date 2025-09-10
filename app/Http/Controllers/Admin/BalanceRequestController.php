<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BalanceRequest;
use App\Models\User;
use App\Models\Transaction;
use App\Models\Generation;
use App\Models\Commission;
use Illuminate\Support\Facades\Auth;

class BalanceRequestController extends Controller
{
    public function index(Request $request)
    {
        $pageTitle = "Balance Request List";

        $status = $request->query('status', 'pending');
        $date = $request->query('date');

        $requests = BalanceRequest::with('user')
            ->when($status, function ($q) use ($status) {
                return $q->where('status', $status);
            })
            ->when($date, function ($q) use ($date) {
                return $q->whereDate('created_at', $date);
            })
            ->latest()
            ->get();

        return view('admin.balance.index', compact('pageTitle', 'requests', 'status', 'date'));
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

        // ---- Only run commission logic if status is approved ---- //
        if ($request->status === 'approved') {
            $user   = User::find($balanceRequest->user_id);
            $amount = $balanceRequest->amount;
            $this->referCommission($user->id, $amount);
        }

        $commission = Commission::find(1);

        $commission_bonus = ($balanceRequest->amount * $commission->refer1) / 100;

        $balanceRequest->update([
            'status' => $request->status,
            'amount' => $balanceRequest->amount + $commission_bonus,
        ]);


        return redirect()->route('admin.balance.request.index')
                        ->with('success','Balance request status updated successfully.');
    }

    public function referCommission($refer_id, $amount)
    {
        $commission = Commission::find(1);

        if (!$commission) {
            return back()->with('error', 'Commission settings not found.');
        }

        // Step 1: Direct Referrer
        $directReferrer = User::find($refer_id);

        if (!$directReferrer || !$directReferrer->refer_by) {
            return back()->with('error', 'Direct referrer not found.');
        }

        // 💰 Pay Direct Referrer (20%)
        $directCommission = ($amount * $commission->refer1) / 100;
        $directReferrer->increment('main_wallet', $directCommission);
        $directReferrer->increment('refer_bonus', $directCommission);
        // $directReferrer->increment('fund_wallet', $directCommission); // optional

        Transaction::create([
            'from_id'   => $refer_id,
            'user_id'   => $directReferrer->id,
            'from_user' => $refer_id,
            'out'       => 'referral',
            'status'    => 'success',
            'purpose'   => 'Direct Referral Commission',
            'amount'    => $directCommission,
        ]);

        Generation::create([
            'from_user_id' => $refer_id,
            'to_user_id'   => $directReferrer->id,
            'level'        => 0,
            'date'         => now(),
            'status'       => 1,
            'commission'   => $directCommission,
            'total_amount' => $amount,
        ]);

        // Step 2: 1st Generation Referrer
        $firstGenReferrer = User::find($directReferrer->refer_by);

        if ($firstGenReferrer) {
            $firstGenCommission = ($amount * $commission->refer2) / 100;
            $firstGenReferrer->increment('main_wallet', $firstGenCommission);
            $firstGenReferrer->increment('refer_bonus', $firstGenCommission);
            // $firstGenReferrer->increment('fund_wallet', $firstGenCommission); // optional

            Transaction::create([
                'from_id'   => $refer_id,
                'user_id'   => $firstGenReferrer->id,
                'from_user' => $refer_id,
                'out'       => 'referral',
                'status'    => 'success',
                'purpose'   => '1st Generation Referral Commission',
                'amount'    => $firstGenCommission,
            ]);

            Generation::create([
                'from_user_id' => $refer_id,
                'to_user_id'   => $firstGenReferrer->id,
                'level'        => 1,
                'date'         => now(),
                'status'       => 1,
                'commission'   => $firstGenCommission,
                'total_amount' => $amount,
            ]);

            // Step 3: 2nd Generation Referrer
            if ($firstGenReferrer->refer_by) {
                $secondGenReferrer = User::find($firstGenReferrer->refer_by);

                if ($secondGenReferrer) {
                    $secondGenCommission = ($amount * $commission->refer3) / 100;
                    $secondGenReferrer->increment('main_wallet', $secondGenCommission);
                    $secondGenReferrer->increment('refer_bonus', $secondGenCommission);
                    // $secondGenReferrer->increment('fund_wallet', $secondGenCommission); // optional

                    Transaction::create([
                        'from_id'   => $refer_id,
                        'user_id'   => $secondGenReferrer->id,
                        'from_user' => $refer_id,
                        'out'       => 'referral',
                        'status'    => 'success',
                        'purpose'   => '2nd Generation Referral Commission',
                        'amount'    => $secondGenCommission,
                    ]);

                    Generation::create([
                        'from_user_id' => $refer_id,
                        'to_user_id'   => $secondGenReferrer->id,
                        'level'        => 2,
                        'date'         => now(),
                        'status'       => 1,
                        'commission'   => $secondGenCommission,
                        'total_amount' => $amount,
                    ]);
                }
            }
        }

        return true;
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
