<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class ReferController extends Controller
{
    public function index()
    {
        $pageTitle = "Refer List";

        // Current user referred users
        $refers = User::where('refer_by', auth()->id())->get();

        return view('user.refer.index', compact('pageTitle', 'refers'));
    }
}
