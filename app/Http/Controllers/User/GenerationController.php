<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Generation;

class GenerationController extends Controller
{
   public function index()
    {
        $pageTitle = "Generation List";

        $userId = auth()->id();

        // Get generations where current user was the receiver (to_user_id)
        $generations = Generation::where('to_user_id', $userId)
            ->with('fromUser') 
            ->latest()
            ->paginate(10);

        return view('user.generation.index', compact('pageTitle', 'generations'));
    }
}
