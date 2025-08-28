<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use App\Models\Agent;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redirect;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
    
        return Validator::make($data, [
            'full_name'   => ['required', 'string', 'max:255'],
            'username'    => ['required', 'string', 'max:255', 'unique:users,username'],
            'institute'   => ['required', 'string'],
            'division_id' => ['required', 'integer'],
            'refer_by'    => [
                'nullable', 
                'string',
                function ($attribute, $value, $fail) {
                    if (!empty($value)) {
                        $exists = User::where('username', $value)->exists();
                        if (!$exists) {
                            $fail('Please provide a valid Username!'); 
                        }
                    }
                }
            ],
            'phone' => ['required', 'digits:11', 'numeric'],
            'password'    => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    public function create(array $data)
    {
        try {
            if (empty($data['refer_by'])) {
                flash()->addError("Please provide a Refer Username!");
                return null; 
            }

            $referUser = User::where('username', $data['refer_by'])->first();
            if (!$referUser) {
                flash()->addError("Please provide a valid Refer Username!");
                return null; 
            }

            $refer_id = $referUser->id;

            $user = User::create([
                'full_name'     => $data['full_name'],
                'username'      => $data['username'],
                'refer_by'      => $refer_id,
                'phone'         => $data['phone'],
                'institute'     => $data['institute'],
                'division_id'   => $data['division_id'],
                'password'      => Hash::make($data['password']),
                'show_password' => $data['password'],
                'created_by'    => $data['username'],
            ]);

            flash()->addSuccess('User Registered Successfully.');
            return $user;

        } catch (\Exception $e) {
            flash()->addError($e->getMessage());
            return null;
        }

    }

    public function checkRefer($username){
        $user = User::where('username', $username)->first();

        if($user){
            return response()->json([
                'status' => true,
                'message' => 'Valid Refer Username ✅'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Invalid Refer Username ❌'
            ]);
        }
    }

}
