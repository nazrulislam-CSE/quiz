<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;

class PasswordController extends Controller
{
    public function index()
    {
        $pageTitle = "Change Password";
        return view('admin.auth.change_password', compact('pageTitle'));
    }

    public function update(Request $request, $id)
    {
        $admin_info = Admin::findOrFail($id);
        $check_password = Hash::check($request->current_password, $admin_info->password);
        if ($check_password != true) {
            alert('Wrong Password!', 'Your current password is wrong! Please enter your current password correctly & try again.', 'error');
            return redirect()->back();
        }
        $check_new_password = $request->new_password === $request->retype_password;
        if ($check_new_password == null) {
            alert('Password Not Matched!', 'New password & Re-type Password is not matched! Please Try again.', 'error');
            return redirect()->back();
        }

        $admin_info->password = Hash::make($request->new_password);
        $admin_info->save();

        alert('Password Changed!', 'Your password has been changed successfully!', 'success');
        return redirect()->route('admin.dashboard.index');
    }
}
