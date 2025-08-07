<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Session;
use Illuminate\Support\Carbon;
use Image;
use Auth;
use Illuminate\Support\Facades\Hash;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       //
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /* ===========> Start Profile Vew <===========*/
    public function profileView(){
        $pageTitle = 'Profile View';
        $profile = User::where('id',Auth::user()->id)->first();
        return view('user.settings.profile', compact('pageTitle','profile'));
    }

    /* ===========> End Profile Vew <=============*/

    /* ===========> Start Profile Update <===========*/
    public function profileUpdate(Request $request){
        // $this->validate($request, [
        //     'name' =>'required',
        //     'email' =>'required',
        //     'phone' =>'required',
        //     'address' =>'required',
        // ]);

        $profile = User::where('id',Auth::user()->id)->first();
        $profile->company_name = $request->company_name;
        $profile->owner_name = $request->owner_name;
        $profile->city_name = $request->city_name;
        $profile->established_year = $request->established_year;
        $profile->nid_number = $request->nid_number;
        $profile->username = $request->username;
        $profile->email = $request->email;
        $profile->phone = $request->phone;
        $profile->designation = $request->designation;
        $profile->save();

        if ($request->file('photo')) {
            $file = $request->file('photo');
            @unlink(public_path('upload/user_images/'.$profile->photo));
            $filename = date('YmdHi').$file->getClientOriginalName();
            $file->move(public_path('upload/user_images'),$filename);
            $profile['image'] = $filename;
        }

        $profile->save();


        flash()->addSuccess("Profile Updated Successfull.");

        $url = '/user/settings/profile/view';
        return redirect($url);
    }
    /* ===========> End Profile Update <=============*/

    /* ===========> Start Password Change <===========*/
    public function passwordChange(){
        $pageTitle = 'Password Change';
        return view('user.settings.password', compact('pageTitle'));
    }
    /* ===========> Start Password Change <===========*/

    /* ===========> Start Password Update <===========*/
    public function passwordUpdate(Request $request){

        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|confirmed',
        ]);

        $user = User::where('id',Auth::user()->id)->first();

        // Match The Old Password
        if (!Hash::check($request->old_password, $user->password)) {
            flash()->addError("Old Password Does Not Match.");
            $url = '/user/settings/password/change';
            return redirect($url);
        }

        // Update The new password
        $user->update([
            'password' => Hash::make($request->new_password),
            'show_password' => $request->new_password
        ]);

        flash()->addSuccess("Password Updated Successfull.");

        $url = '/user/settings/password/change';
        return redirect($url);
    }
    /* ===========> Start Password Update <===========*/
}
