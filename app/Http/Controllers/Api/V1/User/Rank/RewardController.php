<?php

namespace App\Http\Controllers\Api\V1\User\Rank;

use App\Http\Controllers\Controller;
use App\Models\Rank;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class RewardController extends Controller
{
    public function reward(): JsonResponse
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthenticated.',
            ], 401);
        }

        // User যে Rank গুলো অর্জন করেছে
        $rewards = Rank::where('user_id', $user->id)
            ->where('status', 1)
            ->orderBy('id', 'asc')
            ->get();

        return response()->json([
            'status' => true,
            'message' => 'Rewards retrieved successfully.',
            'data' => [
                'total_rewards' => $rewards->count(),

                'rewards' => $rewards->map(function ($reward) {
                    return [
                        'id' => $reward->id,
                        'rank_name' => $reward->rank_name,
                        'status' => (int) $reward->status,
                        'created_at' => $reward->created_at,
                        'updated_at' => $reward->updated_at,
                    ];
                })->values(),
            ],
        ]);
    }
}
