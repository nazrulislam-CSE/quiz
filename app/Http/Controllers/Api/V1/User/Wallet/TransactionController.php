<?php

namespace App\Http\Controllers\Api\V1\User\Wallet;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $transactions = Transaction::with(['initiator', 'recipient', 'originUser'])
            ->where('user_id', $userId)
            ->orWhere('from_id', $userId)
            ->orderBy('id', 'desc')
            ->get()
            ->map(function ($item) {

                return [
                    'id' => $item->id,

                    'initiator' => [
                        'id' => $item->initiator->id ?? null,
                        'name' => $item->initiator->name ?? null,
                    ],

                    'recipient' => [
                        'id' => $item->recipient->id ?? null,
                        'name' => $item->recipient->name ?? null,
                    ],

                    'origin_user' => [
                        'id' => $item->originUser->id ?? null,
                        'name' => $item->originUser->name ?? null,
                    ],

                    'type' => $item->out,
                    'status' => $item->status,
                    'purpose' => $item->purpose,
                    'amount' => $item->amount,

                    'created_at' => $item->created_at->format('d M Y h:i A'),
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $transactions
        ]);
    }
}