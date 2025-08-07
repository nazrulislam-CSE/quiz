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
            'company_name' => ['required'],
            'owner_name' => ['required'],
            'city_name' => ['required'],
            'established_year' => ['required',],
            // 'nid_number' => ['required'],
            'designation' => ['required'],
            'phone' => ['required',],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            
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
        // dd($data);
        $user = User::create([
            'company_name' => $data['company_name'],
            'owner_name' => $data['owner_name'],
            'city_name' => $data['city_name'],
            'established_year' => $data['established_year'],
            // 'nid_number' => $data['nid_number'],
            'designation' => $data['designation'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'show_password' => $data['password'],
            'username' => $data['company_name'],
            'phone' => $data['phone'],
            'created_by' => $data['company_name'],
        ]);

        flash()->addSuccess('User Register Successfully.');

        // DB::table('model_has_roles')->insert([
        //     'role_id' => 1,
        //     'model_type' => 'App\Models\User',
        //     'model_id' => $user->id,
        // ]);
      
        return $user;
    }
}
