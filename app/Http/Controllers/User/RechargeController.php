<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Recharge;
use App\Services\RechargeApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class RechargeController extends Controller
{
    /**
     * Show Recharge Page
     */
    public function rechargeCreate()
    {
        $pageTitle = 'মোবাইল রিচার্জ';

        return view(
            'user.recharge.create',
            compact('pageTitle')
        );
    }


    /**
     * Store Recharge
     */
    public function rechargeStore(
        Request $request,
        RechargeApiService $rechargeApi
    ) {
        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

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
        ], [
            'number.required' => 'মোবাইল নম্বর দিন।',
            'number.regex' => 'সঠিক ১১ সংখ্যার মোবাইল নম্বর দিন।',

            'amount.required' => 'রিচার্জের পরিমাণ দিন।',
            'amount.numeric' => 'রিচার্জের পরিমাণ সঠিক হতে হবে।',
            'amount.min' => 'সর্বনিম্ন রিচার্জ ২০ টাকা।',
            'amount.max' => 'সর্বোচ্চ রিচার্জ ১০,০০০ টাকা।',

            'operator.required' => 'অপারেটর নির্বাচন করুন।',
            'operator.in' => 'সঠিক অপারেটর নির্বাচন করুন।',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Auth User
        |--------------------------------------------------------------------------
        */

        $user = Auth::user();

        if (!$user) {
            return redirect()
                ->back()
                ->with('error', 'অনুগ্রহ করে আগে লগইন করুন।');
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
            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    'এই মোবাইল নম্বরের অপারেটর শনাক্ত করা যায়নি।'
                );
        }


        if ($operator !== $requiredOperator) {
            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    'এই নম্বরের জন্য '
                    . $operatorNames[$requiredOperator]
                    . ' নির্বাচন করতে হবে।'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Initial Wallet Check
        |--------------------------------------------------------------------------
        */

        $walletBalance = (float) ($user->income_wallet ?? 0);

        if ($walletBalance < $amount) {
            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    'আপনার ইনকাম ওয়ালেটে পর্যাপ্ত ব্যালেন্স নেই। বর্তমান ব্যালেন্স: ৳'
                    . number_format($walletBalance, 2)
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Generate Recharge Reference
        |--------------------------------------------------------------------------
        |
        | এটা transaction_id নয়।
        | Recharge table-এর নিজস্ব reference হিসেবে ব্যবহার করা যাবে।
        |
        */

        $rechargeReference = 'RCH-' . strtoupper(Str::random(16));


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


            /*
            |--------------------------------------------------------------------------
            | API Response Check
            |--------------------------------------------------------------------------
            */

            Log::info('Recharge API response', [
                'user_id' => $user->id,
                'reference' => $rechargeReference,
                'response' => $result,
            ]);


            if (
                !isset($result['success']) ||
                $result['success'] !== true
            ) {

                return redirect()
                    ->back()
                    ->withInput()
                    ->with(
                        'error',
                        $result['message']
                        ?? 'রিচার্জ সফল হয়নি।'
                    );
            }


            /*
            |--------------------------------------------------------------------------
            | Database Transaction
            |--------------------------------------------------------------------------
            |
            | API success হওয়ার পর:
            |
            | 1. User wallet থেকে টাকা কাটবে
            | 2. Recharge table-এ success record হবে
            | 3. Transaction table-এ withdrawal record হবে
            | 4. Commission দেওয়া হবে
            |
            | যেকোনো একটি fail করলে DB rollback হবে।
            |
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

                $lockedUser = User::where('id', $user->id)
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
                | Deduct Income Wallet
                |--------------------------------------------------------------------------
                */

                $lockedUser->income_wallet =
                    $balance - $amount;

                $lockedUser->save();


                /*
                |--------------------------------------------------------------------------
                | Create Recharge Record
                |--------------------------------------------------------------------------
                |
                | এখানে status সরাসরি success হবে।
                |
                */

                $recharge = Recharge::create([
                    'user_id' => $lockedUser->id,
                    
                    'transaction_id' => $rechargeReference,

                    'number' => $number,

                    'operator' => $operator,

                    'amount' => $amount,

                    'reference' => $rechargeReference,

                    'status' => 'success',

                    'api_response' => $result,
                ]);


                /*
                |--------------------------------------------------------------------------
                | Create Financial Transaction
                |--------------------------------------------------------------------------
                |
                | transaction_id আর ব্যবহার করা হচ্ছে না।
                |
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
                | Give Commission
                |--------------------------------------------------------------------------
                */

                $this->giveRechargeCommission(
                    $lockedUser,
                    $amount
                );


                Log::info('Recharge database completed', [
                    'user_id' => $lockedUser->id,
                    'recharge_id' => $recharge->id,
                    'reference' => $rechargeReference,
                    'amount' => $amount,
                    'status' => 'success',
                ]);
            });


            /*
            |--------------------------------------------------------------------------
            | Final Success
            |--------------------------------------------------------------------------
            */

            return redirect()
                ->route('user.recharge.history')
                ->with(
                    'success',
                    'রিচার্জ সফল হয়েছে এবং কমিশন বিতরণ করা হয়েছে।'
                );


        } catch (\Throwable $e) {

            Log::error('Recharge failed', [
                'user_id' => $user->id ?? null,
                'reference' => $rechargeReference ?? null,
                'number' => $number ?? null,
                'amount' => $amount ?? null,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    $e->getMessage()
                );
        }

    }


    /*
    |--------------------------------------------------------------------------
    | Recharge Commission
    |--------------------------------------------------------------------------
    */

    private function giveRechargeCommission(
        User $user,
        float $amount
    ) {

        /*
        |--------------------------------------------------------------------------
        | Commission
        |--------------------------------------------------------------------------
        |
        | ২০ টাকা recharge হলে:
        |
        | Self       = ১০ টাকা
        | Referrer   = ৫ টাকা
        | 1st Gen    = ৩ টাকা
        | 2nd Gen    = ২ টাকা
        |
        | Total = ২০ টাকা
        |
        |--------------------------------------------------------------------------
        */

        $selfCommission = $amount * 0.50;
        $referrerCommission = $amount * 0.25;
        $firstGenerationCommission = $amount * 0.15;
        $secondGenerationCommission = $amount * 0.10;


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


        /*
        |--------------------------------------------------------------------------
        | Direct Referrer Commission
        |--------------------------------------------------------------------------
        */

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


    /*
    |--------------------------------------------------------------------------
    | Recharge History
    |--------------------------------------------------------------------------
    */

    public function rechargeHistory()
    {
        $userId = Auth::id();

        $pageTitle = 'রিচার্জ হিস্টোরি';

        $histories = Recharge::where(
            'user_id',
            $userId
        )
            ->latest()
            ->paginate(20);

        return view(
            'user.recharge.history',
            compact(
                'pageTitle',
                'histories'
            )
        );
    }
}
