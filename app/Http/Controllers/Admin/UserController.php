<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Agent;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::latest()->get();
        $pageTitle = 'User List';
        return view('admin.user.index',compact('pageTitle', 'users'));
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
        $pageTitle = 'User Details';
        $user = User::findOrFail($id);
        return view('admin.user.show', compact('user', 'pageTitle'));
    }

    /**
     * Show the form for editing the specified resource.
     */
   public function edit(string $id)
    {
        $user = User::findOrFail($id);
        $pageTitle = 'User Edit';
        return view('admin.user.edit', compact('user','pageTitle'));
    }

    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        // ✅ Validation
        $request->validate([
            'full_name' => 'required|string|max:255',
            'username'  => 'required|string|max:255|unique:users,username,'.$id,
            // 'phone'     => 'required|string|max:20|unique:users,phone,'.$id,
            'email'     => 'nullable|email|unique:users,email,'.$id,
            'status'    => 'required|in:0,1',
            'image'     => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'password'  => 'nullable|min:6',
        ]);

        $user->full_name = $request->full_name;
        $user->username  = $request->username;
        $user->phone     = $request->phone;
        $user->email     = $request->email;
        $user->status    = $request->status;

        // ✅ Password update (optional)
        if ($request->filled('password')) {
            $user->password      = bcrypt($request->password);
            $user->show_password = $request->password; 
        }

        // ✅ Image update
        if ($request->hasFile('image')) {
            @unlink(public_path('upload/user/'.$user->image));
            $file = $request->file('image');
            $filename = date('YmdHi').'.'.$file->getClientOriginalExtension();
            $file->move(public_path('upload/user'), $filename);
            $user->image = $filename;
        }

        $user->save();

        return redirect()->route('admin.user.index')->with('success','User updated successfully!');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);

        if ($user->image && file_exists(public_path('upload/user/' . $user->image))) {
            unlink(public_path('upload/user/' . $user->image));
        }

        $user->delete();

        return redirect()->route('admin.user.index')->with('success', 'User deleted successfully!');
    }

}
