<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Carbon;

class FrontendController extends Controller
{
    public function index()
    {
       
        $pageTitle = 'Home Page';
        return view('frontend.index',compact('pageTitle'));
    }

}
