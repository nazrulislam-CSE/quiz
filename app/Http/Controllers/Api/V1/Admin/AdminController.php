<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    // ================= GET ALL USERS =================
    public function getAllUsers(Request $request)
    {
        $users = User::latest()->paginate($request->get('per_page', 15));
        
        return response()->json([
            'success' => true,
            'data' => $users
        ]);
    }

    // ================= GET SINGLE USER =================
    public function getUser($id)
    {
        $user = User::find($id);
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'data' => $user
        ]);
    }

    // ================= UPDATE USER STATUS =================
    public function updateUserStatus(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:0,1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::find($id);
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }
        
        $user->update(['status' => $request->status]);
        
        return response()->json([
            'success' => true,
            'message' => 'User status updated successfully',
            'data' => $user
        ]);
    }

    // ================= UPDATE USER WALLETS =================
    public function updateUserWallets(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'main_wallet' => 'nullable|numeric',
            'income_wallet' => 'nullable|numeric',
            'withdraw_wallet' => 'nullable|numeric',
            'refer_bonus' => 'nullable|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::find($id);
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }
        
        $user->update($request->only(['main_wallet', 'income_wallet', 'withdraw_wallet', 'refer_bonus']));
        
        return response()->json([
            'success' => true,
            'message' => 'User wallets updated successfully',
            'data' => $user
        ]);
    }

    // ================= DELETE USER =================
    public function deleteUser($id)
    {
        $user = User::find($id);
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }
        
        $user->tokens()->delete();
        $user->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully'
        ]);
    }

    // ================= GET ALL ADMINS =================
    public function getAllAdmins()
    {
        $admins = Admin::latest()->get();
        
        return response()->json([
            'success' => true,
            'data' => $admins
        ]);
    }

    // ================= CREATE ADMIN =================
    public function createAdmin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:admins,username',
            'email' => 'required|email|unique:admins,email',
            'phone' => 'nullable|integer',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $admin = Admin::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'password' => Hash::make($request->password),
            'show_password' => $request->password,
            'status' => 1,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Admin created successfully',
            'data' => $admin
        ], 201);
    }

    // ================= UPDATE ADMIN STATUS =================
    public function updateAdminStatus(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:0,1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $admin = Admin::find($id);
        
        if (!$admin) {
            return response()->json([
                'success' => false,
                'message' => 'Admin not found'
            ], 404);
        }
        
        $admin->update(['status' => $request->status]);
        
        return response()->json([
            'success' => true,
            'message' => 'Admin status updated successfully',
            'data' => $admin
        ]);
    }
}