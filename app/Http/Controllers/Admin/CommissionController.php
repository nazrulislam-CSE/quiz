<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Commission;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class CommissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = 'Commission List';
        $commissions = Commission::latest()->get();
        return view('admin.commission.index',compact('commissions','pageTitle'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $pageTitle = 'Commission Edit';
        $commission = Commission::findOrFail($id); 
        return view('admin.commission.edit', compact('commission','pageTitle'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'refer1' => 'nullable|string|max:255',
            'refer2' => 'nullable|string|max:255',
            'refer3' => 'nullable|string|max:255',
        ]);

        $commission = Commission::findOrFail($id);

        $commission->update([
            'refer1' => $request->refer1,
            'refer2' => $request->refer2,
            'refer3' => $request->refer3,
        ]);

        return redirect()->route('admin.commission.index')
                        ->with('success', 'Commission updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
