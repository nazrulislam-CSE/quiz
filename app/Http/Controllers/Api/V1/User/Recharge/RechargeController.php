<?php

namespace App\Http\Controllers\Api\V1\User\Recharge;

use App\Http\Controllers\Controller;
use App\Models\Recharge;
use App\Models\Transaction;
use App\Models\User;
use App\Services\RechargeApiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class RechargeController extends Controller
{
    /**
     * Recharge
     */
    public function store(
        Request $request,
        RechargeApiService $rechargeApi
    ): JsonResponse {

        $validated = $request->validate([
            'number' => [
                'required',
                'string',
                'regex:/^01[3-9][0-9]{8}$/',
            ],

            'amount' => [
                'required',
                'numeric',
                'min:20',
                'max:10000',
            ],

            'operator' => [
                'required',
                'string',
                'in:GP,BL,RB,AT,TT',
            ],
        ]);

        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'অনুগ্রহ করে আগে লগইন করুন।',
            ], 401);
        }

        $amount = (float) $validated['amount'];
        $number = $validated['number'];
        $operator = $validated['operator'];

        /*
        |--------------------------------------------------------------------------
        | Operator Map
        |--------------------------------------------------------------------------
        */

        $operatorMap = [
            '013' => 'BL',
            '014' => 'BL',
            '015' => 'TT',
            '016' => 'AT',
            '017' => 'GP',
            '018' => 'RB',
            '019' => 'BL',
        ];

        $operatorNames = [
            'GP' => 'গ্রামীণফোন',
            'BL' => 'বাংলালিংক',
            'RB' => 'রবি',
            'AT' => 'এয়ারটেল',
            'TT' => 'টেলিটক',
        ];

        /*
        |--------------------------------------------------------------------------
        | Check Operator
        |--------------------------------------------------------------------------
        */

        $prefix = substr($number, 0, 3);

        $requiredOperator = $operatorMap[$prefix] ?? null;

        if (!$requiredOperator) {
            return response()->json([
                'success' => false,
                'message' => 'এই মোবাইল নম্বরের অপারেটর শনাক্ত করা যায়নি।',
            ], 422);
        }

        if ($operator !== $requiredOperator) {
            return response()->json([
                'success' => false,
                'message' => 'এই নম্বরের জন্য '
                    . $operatorNames[$requiredOperator]
                    . ' নির্বাচন করতে হবে।',
            ], 422);
        }

        /*
        |--------------------------------------------------------------------------
        | Wallet Check
        |--------------------------------------------------------------------------
        */

        $walletBalance = (float) ($user->income_wallet ?? 0);

        if ($walletBalance < $amount) {
            return response()->json([
                'success' => false,
                'message' => 'আপনার ইনকাম ওয়ালেটে পর্যাপ্ত ব্যালেন্স নেই।',
                'balance' => $walletBalance,
            ], 422);
        }

        /*
        |--------------------------------------------------------------------------
        | Generate Reference
        |--------------------------------------------------------------------------
        */

        $rechargeReference =
            'RCH-' . strtoupper(Str::random(16));

        try {

            /*
            |--------------------------------------------------------------------------
            | Call Recharge API
            |--------------------------------------------------------------------------
            */

            Log::info('Recharge API calling', [
                'user_id' => $user->id,
                'reference' => $rechargeReference,
                'number' => $number,
                'amount' => $amount,
                'operator' => $operator,
            ]);

            $result = $rechargeApi->recharge(
                $number,
                $amount,
                $rechargeReference,
                $operator
            );

            Log::info('Recharge API response', [
                'user_id' => $user->id,
                'reference' => $rechargeReference,
                'response' => $result,
            ]);

            /*
            |--------------------------------------------------------------------------
            | API Response Check
            |--------------------------------------------------------------------------
            */

            if (
                !isset($result['success']) ||
                $result['success'] !== true
            ) {
                return response()->json([
                    'success' => false,
                    'message' => $result['message']
                        ?? 'রিচার্জ সফল হয়নি।',
                ], 422);
            }

            /*
            |--------------------------------------------------------------------------
            | Database Transaction
            |--------------------------------------------------------------------------
            */

            DB::transaction(function () use (
                $user,
                $amount,
                $number,
                $operator,
                $rechargeReference,
                $result
            ) {

                /*
                |--------------------------------------------------------------------------
                | Lock User
                |--------------------------------------------------------------------------
                */

                $lockedUser = User::where(
                    'id',
                    $user->id
                )
                    ->lockForUpdate()
                    ->first();

                if (!$lockedUser) {
                    throw new \Exception(
                        'ইউজার পাওয়া যায়নি।'
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | Re-check Wallet
                |--------------------------------------------------------------------------
                */

                $balance = (float) (
                    $lockedUser->income_wallet ?? 0
                );

                if ($balance < $amount) {
                    throw new \Exception(
                        'আপনার ইনকাম ওয়ালেটে পর্যাপ্ত ব্যালেন্স নেই।'
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | Deduct Wallet
                |--------------------------------------------------------------------------
                */

                $lockedUser->income_wallet =
                    $balance - $amount;

                $lockedUser->save();

                /*
                |--------------------------------------------------------------------------
                | Recharge Record
                |--------------------------------------------------------------------------
                */

                Recharge::create([
                    'user_id' => $lockedUser->id,

                    'transaction_id' =>
                        $rechargeReference,

                    'number' => $number,

                    'operator' => $operator,

                    'amount' => $amount,

                    'reference' =>
                        $rechargeReference,

                    'status' => 'success',

                    'api_response' => $result,
                ]);

                /*
                |--------------------------------------------------------------------------
                | Transaction Record
                |--------------------------------------------------------------------------
                */

                Transaction::create([
                    'from_id' => $lockedUser->id,

                    'user_id' => $lockedUser->id,

                    'out' => 'withdraw',

                    'status' => 'success',

                    'purpose' => 'Recharge',

                    'amount' => $amount,
                ]);

                /*
                |--------------------------------------------------------------------------
                | Commission
                |--------------------------------------------------------------------------
                */

                $this->giveRechargeCommission(
                    $lockedUser,
                    $amount
                );
            });

            /*
            |--------------------------------------------------------------------------
            | Refresh User
            |--------------------------------------------------------------------------
            */

            $user->refresh();

            return response()->json([
                'success' => true,

                'message' =>
                    'রিচার্জ সফল হয়েছে এবং কমিশন বিতরণ করা হয়েছে।',

                'data' => [
                    'reference' =>
                        $rechargeReference,

                    'number' =>
                        $number,

                    'operator' =>
                        $operator,

                    'amount' =>
                        $amount,

                    'balance' =>
                        (float) $user->income_wallet,
                ],
            ], 200);

        } catch (\Throwable $e) {

            Log::error('Recharge failed', [
                'user_id' =>
                    $user->id ?? null,

                'reference' =>
                    $rechargeReference ?? null,

                'number' =>
                    $number ?? null,

                'amount' =>
                    $amount ?? null,

                'error' =>
                    $e->getMessage(),

                'file' =>
                    $e->getFile(),

                'line' =>
                    $e->getLine(),
            ]);

            return response()->json([
                'success' => false,

                'message' =>
                    $e->getMessage(),
            ], 500);
        }
    }


    /**
     * Recharge Commission
     */
    private function giveRechargeCommission(
        User $user,
        float $amount
    ): void {

        /*
        |--------------------------------------------------------------------------
        | Commission Distribution
        |--------------------------------------------------------------------------
        |
        | Self       = 50%
        | Referrer   = 25%
        | 1st Gen    = 15%
        | 2nd Gen    = 10%
        |
        |--------------------------------------------------------------------------
        */

        $selfCommission =
            $amount * 0.50;

        $referrerCommission =
            $amount * 0.25;

        $firstGenerationCommission =
            $amount * 0.15;

        $secondGenerationCommission =
            $amount * 0.10;


        /*
        |--------------------------------------------------------------------------
        | Self Commission
        |--------------------------------------------------------------------------
        */

        $user->income_wallet =
            (float) ($user->income_wallet ?? 0)
            + $selfCommission;

        $user->save();


        /*
        |--------------------------------------------------------------------------
        | Direct Referrer
        |--------------------------------------------------------------------------
        */

        if (empty($user->refer_by)) {
            return;
        }

        $referrer = User::where(
            'id',
            $user->refer_by
        )
            ->lockForUpdate()
            ->first();

        if (!$referrer) {
            return;
        }

        $referrer->income_wallet =
            (float) ($referrer->income_wallet ?? 0)
            + $referrerCommission;

        $referrer->save();


        /*
        |--------------------------------------------------------------------------
        | 1st Generation
        |--------------------------------------------------------------------------
        */

        if (empty($referrer->refer_by)) {
            return;
        }

        $firstGeneration = User::where(
            'id',
            $referrer->refer_by
        )
            ->lockForUpdate()
            ->first();

        if (!$firstGeneration) {
            return;
        }

        $firstGeneration->income_wallet =
            (float) ($firstGeneration->income_wallet ?? 0)
            + $firstGenerationCommission;

        $firstGeneration->save();


        /*
        |--------------------------------------------------------------------------
        | 2nd Generation
        |--------------------------------------------------------------------------
        */

        if (empty($firstGeneration->refer_by)) {
            return;
        }

        $secondGeneration = User::where(
            'id',
            $firstGeneration->refer_by
        )
            ->lockForUpdate()
            ->first();

        if (!$secondGeneration) {
            return;
        }

        $secondGeneration->income_wallet =
            (float) ($secondGeneration->income_wallet ?? 0)
            + $secondGenerationCommission;

        $secondGeneration->save();
    }


    /**
     * Recharge History
     */
    public function rechargeHistory(): JsonResponse
    {
        $histories = Recharge::where(
            'user_id',
            Auth::id()
        )
            ->latest()
            ->paginate(20);

        return response()->json([
            'success' => true,

            'message' =>
                'Recharge history fetched successfully.',

            'data' => $histories,
        ], 200);
    }
}
