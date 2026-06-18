<?php

namespace App\Http\Controllers\Api\V1\User\Wallet;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BalanceRequest;
use Illuminate\Support\Facades\Validator;

class BalanceRequestController extends Controller
{
    public function index(Request $request)
    {
        $requests = BalanceRequest::where('user_id', auth()->id())
            ->latest()
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'method' => $item->method,
                    'from_account' => $item->from_account,
                    'amount' => $item->amount,
                    'trx_id' => $item->trx_id,
                    'screenshot' => $item->screenshot
                        ? asset('upload/balance-request/' . $item->screenshot)
                        : null,
                    'status' => $item->status,
                    'created_at' => $item->created_at->format('d M Y h:i A'),
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $requests,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'method' => 'required|string',
            'from_account' => 'required|string',
            'amount' => 'required|numeric|min:1',
            'trx_id' => 'nullable|string',
            'screenshot' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $imageName = null;

        if ($request->hasFile('screenshot')) {
            $image = $request->file('screenshot');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('upload/balance-request'), $imageName);
        }

        $balanceRequest = BalanceRequest::create([
            'user_id' => auth()->id(),
            'method' => $request->method,
            'from_account' => $request->from_account,
            'amount' => $request->amount,
            'trx_id' => $request->trx_id,
            'screenshot' => $imageName,
            'status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Balance request submitted successfully.',
            'data' => $balanceRequest,
        ]);
    }
}
