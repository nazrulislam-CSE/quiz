<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    public function getEmail()
    {
        return view('admin.auth.forgot_password');
    }

    public function postEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:admins',
        ]);

        $token = Str::random(64);

        DB::table('admin_password_resets')->insert(
            ['email' => $request->email, 'token' => $token, 'created_at' => Carbon::now()]
        );

        Mail::send('email.admin', ['token' => $token], function ($message) use ($request) {
            $message->to($request->email);
            $message->subject('Reset Password Notification');
        });
        alert('Success!', 'We have sent you an email with a link to reset your password!', 'success');
        return back();
    }
}
