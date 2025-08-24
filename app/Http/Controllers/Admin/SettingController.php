<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\Admin;
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
        $pageTitle = 'Advance Settings';
        return view('admin.settings.index', compact('pageTitle'));
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
        // dd($request->types);
        if($request->types !=null && count($request->types) > 0){
            foreach ($request->types as $key => $type) {
                $setting = Setting::where('name', $type)->first();
                $setting->value = $request[$type];
                // if ($request[$type] == ) {
                //     # code...
                // }
                $setting->save();
            }
        }

        //Setting Logo Update
        if ($request->file('site_logo')) {
            $logo = $request->file('site_logo');
            $logo_save = time().$logo->getClientOriginalName();
            $logo->move('upload/setting/logo/',$logo_save);
            $save_url_logo = 'upload/setting/logo/'.$logo_save;

            $setting = Setting::where('name', 'site_logo')->first();
            try {
                if(file_exists($setting->value)){
                    unlink($setting->value);
                }
            } catch (Exception $e) {
                
            }
            $setting->value = $save_url_logo;

            $setting->save();
        }

        //Setting Logo Update
        if ($request->file('site_footer_logo')) {
            $logo = $request->file('site_footer_logo');
            $logo_save = time().$logo->getClientOriginalName();
            $logo->move('upload/setting/logo/',$logo_save);
            $save_url_footer_logo = 'upload/setting/logo/'.$logo_save;

            $setting = Setting::where('name', 'site_footer_logo')->first();
            try {
                if(file_exists($setting->value)){
                    unlink($setting->value);
                }
            } catch (Exception $e) {
                
            }
            $setting->value = $save_url_footer_logo;

            $setting->save();
        }

        //Setting Favicon Update
        if ($request->file('site_favicon')) {
            $favicon = $request->file('site_favicon');
            $favicon_save = time().$favicon->getClientOriginalName();
            $favicon->move('upload/setting/favicon/',$favicon_save);
            $save_url_favicon = 'upload/setting/favicon/'.$favicon_save;
            
            $setting = Setting::where('name', 'site_favicon')->first();
            try {
                if(file_exists($setting->value)){
                    unlink($setting->value);
                }
            } catch (Exception $e) {
                
            }
            $setting->value = $save_url_favicon;

            $setting->save();
        }

        //Setting Contact Update
        if ($request->file('site_contact_logo')) {
            $favicon = $request->file('site_contact_logo');
            $favicon_save = time().$favicon->getClientOriginalName();
            $favicon->move('upload/setting/contact/',$favicon_save);
            $save_url_favicon = 'upload/setting/contact/'.$favicon_save;
            
            $setting = Setting::where('name', 'site_contact_logo')->first();
            try {
                if(file_exists($setting->value)){
                    unlink($setting->value);
                }
            } catch (Exception $e) {
                
            }
            $setting->value = $save_url_favicon;

            $setting->save();
        }

        //Setting Company Banner Update
        if ($request->file('site_company_logo')) {
            $favicon = $request->file('site_company_logo');
            $favicon_save = time().$favicon->getClientOriginalName();
            $favicon->move('upload/setting/company/',$favicon_save);
            $save_url_favicon = 'upload/setting/company/'.$favicon_save;
            
            $setting = Setting::where('name', 'site_company_logo')->first();
            try {
                if(file_exists($setting->value)){
                    unlink($setting->value);
                }
            } catch (Exception $e) {
                
            }
            $setting->value = $save_url_favicon;

            $setting->save();
        }

        // Banner 1
        if ($request->file('top_banner')) {
            $banner = $request->file('top_banner');
            $banner_save = time().$banner->getClientOriginalName();
            $banner->move('upload/setting/company/',$banner_save);
            $save_url_banner = 'upload/setting/company/'.$banner_save;
            
            $setting = Setting::where('name', 'top_banner')->first();
            try {
                if(file_exists($setting->value)){
                    unlink($setting->value);
                }
            } catch (Exception $e) {
                
            }
            $setting->value = $save_url_banner;

            $setting->save();
        }

        // Banner 2
        if ($request->file('top_banner1')) {
            $banner = $request->file('top_banner1');
            $banner_save = time().$banner->getClientOriginalName();
            $banner->move('upload/setting/company/',$banner_save);
            $save_url_banner = 'upload/setting/company/'.$banner_save;
            
            $setting = Setting::where('name', 'top_banner1')->first();
            try {
                if(file_exists($setting->value)){
                    unlink($setting->value);
                }
            } catch (Exception $e) {
                
            }
            $setting->value = $save_url_banner;

            $setting->save();
        }

        // Banner 3
        if ($request->file('middle_banner')) {
            $banner = $request->file('middle_banner');
            $banner_save = time().$banner->getClientOriginalName();
            $banner->move('upload/setting/company/',$banner_save);
            $save_url_banner = 'upload/setting/company/'.$banner_save;
            
            $setting = Setting::where('name', 'middle_banner')->first();
            try {
                if(file_exists($setting->value)){
                    unlink($setting->value);
                }
            } catch (Exception $e) {
                
            }
            $setting->value = $save_url_banner;

            $setting->save();
        }

        // Banner 4
        if ($request->file('middle_banner1')) {
            $banner = $request->file('middle_banner1');
            $banner_save = time().$banner->getClientOriginalName();
            $banner->move('upload/setting/company/',$banner_save);
            $save_url_banner = 'upload/setting/company/'.$banner_save;
            
            $setting = Setting::where('name', 'middle_banner1')->first();
            try {
                if(file_exists($setting->value)){
                    unlink($setting->value);
                }
            } catch (Exception $e) {
                
            }
            $setting->value = $save_url_banner;

            $setting->save();
        }

        flash()->addSuccess("Setting Updated Successfull.");

        $url = '/admin/settings/index';
        return redirect($url);
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
        $profile = Admin::where('id',Auth::guard('admin')->user()->id)->first();
        return view('admin.settings.profile', compact('pageTitle','profile'));
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

        $profile = Admin::where('id',Auth::guard('admin')->user()->id)->first();
        $profile->name = $request->name;
        $profile->username = $request->username;
        $profile->email = $request->email;
        $profile->phone = $request->phone;
        $profile->address = $request->address;
        $profile->save();

        if ($request->file('photo')) {
            $file = $request->file('photo');
            @unlink(public_path('upload/admin_images/'.$profile->photo));
            $filename = date('YmdHi').$file->getClientOriginalName();
            $file->move(public_path('upload/admin_images'),$filename);
            $profile['image'] = $filename;
        }

        $profile->save();

        flash()->addSuccess("Profile Updated Successfull.");

        $url = '/admin/settings/profile/view';
        return redirect($url);
    }
    /* ===========> End Profile Update <=============*/

    /* ===========> Start Password Change <===========*/
    public function passwordChange(){
        $pageTitle = 'Password Change';
        return view('admin.settings.password', compact('pageTitle'));
    }
    /* ===========> Start Password Change <===========*/

    /* ===========> Start Password Update <===========*/
    public function passwordUpdate(Request $request){

        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|confirmed',
        ]);

        $admin = Admin::where('id',Auth::guard('admin')->user()->id)->first();

        // Match The Old Password
        if (!Hash::check($request->old_password, $admin->password)) {
            flash()->addError("Old Password Does Not Match.");
            $url = '/admin/settings/password/change';
            return redirect($url);
        }

        // Update The new password
        $admin->update([
            'password' => Hash::make($request->new_password),
            'show_password' => $request->new_password
        ]);

        flash()->addSuccess("Password Updated Successfull.");

        $url = '/admin/settings/password/change';
        return redirect($url);
    }
    /* ===========> Start Password Update <===========*/
}
