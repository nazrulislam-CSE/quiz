<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class GenerationController extends Controller
{
    public function index()
    {
        $pageTitle = "Generation List";

        $userId = auth()->id();

        // First generation (directly referred users)
        $firstGen = User::where('refer_by', $userId)->get();

        // Second generation (referred by first gen)
        $secondGen = User::whereIn('refer_by', $firstGen->pluck('id'))->get();

        // Third generation (referred by second gen)
        $thirdGen = User::whereIn('refer_by', $secondGen->pluck('id'))->get();

        return view('user.generation.index', compact(
            'pageTitle', 'firstGen', 'secondGen', 'thirdGen'
        ));
    }
}
