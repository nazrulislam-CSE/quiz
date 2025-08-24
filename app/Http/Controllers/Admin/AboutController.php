<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\About;
use Illuminate\Support\Carbon;

class AboutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = 'About List';
        $abouts = About::latest()->get();
        return view('admin.about.index',compact('pageTitle','abouts'));
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
        $pageTitle = 'About Show';
        $about = About::find($id);
        return view('admin.about.show',compact('pageTitle','about'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $about = About::find($id);
        $pageTitle = 'About Edit';
        return view('admin.about.edit', compact('about','pageTitle'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $about = About::find($id);

        $about->title = $request->title;
        $about->experience_no = $request->experience_no;
        $about->experience_title = $request->experience_title;
        $about->description = $request->description;

        if($request->status == Null){
            $request->status = 0;
        }
       
        $about->status = $request->status;
        $about->updated_at = Carbon::now();

        $about->save();

        if ($request->file('image')) {
            $file = $request->file('image');
            @unlink(public_path('upload/about/'.$about->image));
            $filename = date('YmdHi').$file->getClientOriginalName();
            $file->move(public_path('upload/about'),$filename);
            $about['image'] = $filename;
        }

        if ($request->file('image1')) {
            $file = $request->file('image1');
            @unlink(public_path('upload/about/'.$about->image1));
            $filename = date('YmdHi').$file->getClientOriginalName();
            $file->move(public_path('upload/about'),$filename);
            $about['image1'] = $filename;
        }

        $about->save();


        flash()->addSuccess("About Updated Successfully.");
        $url = '/admin/abouts/index';
        return redirect($url);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $about = About::find($id);

        try {
            if(file_exists($about->image)){
                unlink($about->image);
            }
        } catch (Exception $e) {

        }

        try {
            if(file_exists($about->image1)){
                unlink($about->image1);
            }
        } catch (Exception $e) {

        }

        $about->delete();


        flash()->addError("About Deleted Successfully.");
        $url = '/admin/abouts/index';
        return redirect($url);
    }
}
