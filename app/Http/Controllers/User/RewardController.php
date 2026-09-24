<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Rank;
use Illuminate\Support\Facades\Auth;

class RewardController extends Controller
{
    public function reward()
    {
        $pageTitle = "My Reward";

        $user = Auth::user();

        // User যে Rank গুলো অর্জন করেছে
        $rewards = Rank::where('user_id', $user->id)
            ->where('status', 1)
            ->orderBy('id', 'asc')
            ->get();

        return view('user.reward.index', compact(
            'pageTitle',
            'rewards'
        ));
    }
}