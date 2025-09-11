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
        $pageTitle = "My Generation List";
        $userId = auth()->id();

        $myGenerations = Generation::with(['fromUser', 'toUser'])
            ->where('from_user_id', $userId)
            ->orderBy('level', 'asc')
            ->get();

        return view('user.generation.index', compact('pageTitle', 'myGenerations'));
    }



}
