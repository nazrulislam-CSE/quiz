<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\RechargeApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RechargeController extends Controller
{
    /**
     * Show recharge form
     */
    public function rechargeCreate()
    {
        $pageTitle = "মোবাইল রিচার্জ";
        return view('user.recharge.create',compact('pageTitle'));
    }

    /**
     * Process recharge
     */
    public function rechargeStore(
        Request $request,
        RechargeApiService $rechargeApi
    ) {
        $request->validate([
            'number' => [
                'required',
                'string',
                'regex:/^01[3-9][0-9]{8}$/',
            ],
            'amount' => [
                'required',
                'numeric',
                'min:1',
            ],
            'operator' => [
                'required',
                'string',
                'in:GP,BL,RB,AT,TT',
            ],
        ]);

        $transactionId = 'TXN-' . strtoupper(Str::random(16));

        try {

            $result = $rechargeApi->recharge(
                $request->number,
                $request->amount,
                $transactionId,
                $request->operator
            );

            return redirect()
                ->back()
                ->with('success', 'Recharge request submitted successfully.')
                ->with('recharge_data', $result);

        } catch (\Throwable $e) {

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Recharge request failed: ' . $e->getMessage());
        }
    }
}
