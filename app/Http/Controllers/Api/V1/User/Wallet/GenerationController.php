<?php

namespace App\Http\Controllers\Api\V1\User\Wallet;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Generation;
use Illuminate\Support\Facades\Auth;

class GenerationController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $generations = Generation::with(['fromUser', 'toUser'])
            ->where('from_user_id', $userId)
            ->orderBy('level', 'asc')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'from_user' => [
                        'id' => $item->fromUser->id ?? null,
                        'name' => $item->fromUser->name ?? null,
                        'email' => $item->fromUser->email ?? null,
                    ],
                    'to_user' => [
                        'id' => $item->toUser->id ?? null,
                        'name' => $item->toUser->name ?? null,
                        'email' => $item->toUser->email ?? null,
                    ],
                    'level' => $item->level,
                    'commission' => $item->commission,
                    'total_amount' => $item->total_amount,
                    'date' => $item->date,
                    'status' => $item->status,
                    'created_at' => $item->created_at->format('d M Y h:i A'),
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $generations
        ]);
    }
}