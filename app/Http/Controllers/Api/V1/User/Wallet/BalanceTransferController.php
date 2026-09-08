<?php

namespace App\Http\Controllers\Api\V1\User\Wallet;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\BalanceTransfer;
use App\Models\Transaction;

class BalanceTransferController extends Controller
{
    /**
     * Balance Transfer History
     */
    public function index()
    {
        try {

            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. Please login first.',
                ], 401);
            }

            $transfers = BalanceTransfer::where('user_id', $user->id)
                ->latest()
                ->get();


            return response()->json([
                'success' => true,
                'message' => 'Balance transfer history retrieved successfully.',
                'data' => $transfers,
            ], 200);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    /**
     * Balance Transfer
     * Income Wallet -> Main Wallet
     */
    public function store(Request $request)
    {
        try {

            $validator = \Validator::make($request->all(), [
                'amount' => 'required|numeric|min:1',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed.',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. Please login first.',
                ], 401);
            }

            $amount = (float) $request->amount;


            // ================= CHECK BALANCE =================

            if ($amount > $user->income_wallet) {
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient balance in Income Wallet.',
                    'data' => [
                        'income_wallet' => $user->income_wallet,
                        'requested_amount' => $amount,
                    ],
                ], 400);
            }


            // ================= DATABASE TRANSACTION =================

            DB::beginTransaction();

            // Deduct Income Wallet
            $user->income_wallet -= $amount;

            // Add Main Wallet
            $user->main_wallet += $amount;

            $user->save();


            // ================= BALANCE TRANSFER =================

            $balanceTransfer = BalanceTransfer::create([
                'user_id' => $user->id,
                'amount' => $amount,
                'status' => 'success',
            ]);


            // ================= TRANSACTION HISTORY =================

            Transaction::create([
                'user_id' => $user->id,
                'from_id' => $user->id,
                'from_user' => $user->id,
                'out' => 'transfer',
                'amount' => $amount,
                'status' => 'success',
                'purpose' => 'Income to Main Wallet Transfer',
            ]);


            DB::commit();


            // Refresh user data
            $user->refresh();


            return response()->json([
                'success' => true,
                'message' => 'Balance transferred successfully.',

                'data' => [
                    'transfer' => [
                        'id' => $balanceTransfer->id,
                        'amount' => $balanceTransfer->amount,
                        'status' => $balanceTransfer->status,
                        'created_at' => $balanceTransfer->created_at,
                    ],

                    'wallet' => [
                        'income_wallet' => $user->income_wallet,
                        'main_wallet' => $user->main_wallet,
                    ],
                ],
            ], 200);


        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Balance transfer failed.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}