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
        $pageTitle = "Upline Generation List";
        $userId = auth()->id();

        $uplineGenerations = [];
        $currentUserId = $userId;

        for ($i = 0; $i < 3; $i++) {
            $generation = Generation::where('to_user_id', $currentUserId)
                ->with('fromUser')
                ->first();

            if ($generation) {
                $uplineGenerations[] = $generation;
                $currentUserId = $generation->from_user_id;
            } else {
                break;
            }
        }

        return view('user.generation.index', compact('pageTitle', 'uplineGenerations'));
    }

}
