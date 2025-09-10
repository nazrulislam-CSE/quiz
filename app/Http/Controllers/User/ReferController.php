<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Auth;

class ReferController extends Controller
{
    public function index()
    {
        $pageTitle = "Refer List";

        $user = Auth::user();

        // Get all users referred by the currently logged-in user
        $refers = $user->referrals()->latest()->paginate(10); // or ->get() if no pagination

        return view('user.refer.index', compact('pageTitle', 'refers'));
    }
}
