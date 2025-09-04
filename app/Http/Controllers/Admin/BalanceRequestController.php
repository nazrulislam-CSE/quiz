<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BalanceRequest;
use Illuminate\Support\Facades\Auth;

class BalanceRequestController extends Controller
{
    public function index()
    {
        $pageTitle = "Balance Request List";
        $requests = BalanceRequest::latest()->get();

        return view('admin.balance.index', compact('pageTitle', 'requests'));
    }

    public function show(string $id)
    {
        $pageTitle = 'Balance Request Show';
        $request = BalanceRequest::with('user')->findOrFail($id);
        return view('admin.balance.show', compact('pageTitle', 'request'));
    }


    public function edit(string $id)
    {
        $request = BalanceRequest::find($id);
        $pageTitle = 'Balance Request Edit';
        return view('admin.balance.edit', compact('request','pageTitle'));
    }

    public function update(Request $request, string $id)
    {
        $balanceRequest = BalanceRequest::findOrFail($id);

        $request->validate([
            'status' => 'required|in:pending,approved,rejected',
        ]);

        $balanceRequest->update([
            'status' => $request->status,
        ]);

        return redirect()->route('admin.balance.request.index')
                        ->with('success','Balance request status updated successfully.');
    }

    public function destroy(string $id)
    {
        $balanceRequest = BalanceRequest::findOrFail($id);

        try {
            if(file_exists($balanceRequest->screenshot)){
                unlink($balanceRequest->screenshot);
            }
        } catch (Exception $e) {

        }
        
        $balanceRequest->delete();

        return redirect()->route('admin.balance.request.index')
                        ->with('success','Balance request deleted successfully.');
    }

    

}
