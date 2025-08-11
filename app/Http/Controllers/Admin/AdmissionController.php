<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admission;
use Illuminate\Support\Carbon;
use Session;
use Auth;

class AdmissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = 'Admission List';
        $admissions = Admission::latest()->get();
        return view('admin.admission.index',compact('admissions','pageTitle'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pageTitle = 'Admission Create';
        return view('admin.admission.create',compact('pageTitle'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'image' => 'required|image|mimes:jpg,jpeg,png,gif,svg|max:2048',
            'name' => 'required',
        ]);


        $admission = new Admission;

        if($request->status == Null){
            $request->status = 0;
        }
      
        $admission->status = $request->status;
        $admission->name = $request->name;
        $admission->created_by = Auth::user()->id ?? '1';
        $admission->created_at = Carbon::now();
        $admission->save();


        if ($request->file('image')) {
            $file = $request->file('image');
            @unlink(public_path('upload/admission/'.$admission->image));
            $filename = date('YmdHi').$file->getClientOriginalName();
            $file->move(public_path('upload/admission'),$filename);
            $admission['image'] = $filename;
        }

        $admission->save();

        flash()->addSuccess("Admission Created Successfully.");
        $url = '/admin/admission/index';
        return redirect($url);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pageTitle = 'Admission Show';
        $admission = Admission::find($id);
        return view('admin.admission.show',compact('pageTitle','admission'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $admission = Admission::find($id);
        $pageTitle = 'Admission Edit';
        return view('admin.admission.edit', compact('admission','pageTitle'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $admission = Admission::find($id);

        if($request->status == Null){
            $request->status = 0;
        }
       
        $admission->status = $request->status;
        $admission->name = $request->name;

        $admission->updated_by = Auth::user()->id ?? '1';
        $admission->updated_at = Carbon::now();
        $admission->save();

        if ($request->file('image')) {
            $file = $request->file('image');
            @unlink(public_path('upload/admission/'.$admission->image));
            $filename = date('YmdHi').$file->getClientOriginalName();
            $file->move(public_path('upload/admission'),$filename);
            $admission['image'] = $filename;
        }

        $admission->save();


        flash()->addSuccess("Admission Updated Successfully.");
        $url = '/admin/admission/index';
        return redirect($url);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $admission = Admission::find($id);

        try {
            if(file_exists($admission->image)){
                unlink($admission->image);
            }
        } catch (Exception $e) {

        }

        $admission->delete();


        flash()->addError("Admission Deleted Successfully.");
        $url = '/admin/admissions/index';
        return redirect($url);
    }
}
