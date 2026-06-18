<?php

namespace App\Http\Controllers\Api\V1\User\Wallet;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ReferController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $refers = $user->referrals()
            ->latest()
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'email' => $item->email,
                    'phone' => $item->phone ?? null,
                    'created_at' => $item->created_at->format('d M Y h:i A'),
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $refers
        ]);
    }
}
