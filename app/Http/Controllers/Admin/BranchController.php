<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Branch;
use Illuminate\Support\Carbon;
use Image;
use Session;

class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $branchs = Branch::latest()->get();
        $pageTitle = 'Branch List';
        return view('admin.branch.index',compact('branchs','pageTitle'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pageTitle = 'Branch Create';
        return view('admin.branch.create',compact('pageTitle'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'branch_name'=>'required',
            'contact_no'=>'required',
            'contact_no_optional'=>'required',
            'area_link'=>'required',
        ]);

        $branch = new Branch();

        $branch->branch_name = $request->branch_name ;
        $branch->contact_no = $request->contact_no ;
        $branch->contact_no_optional = $request->contact_no_optional ;
        $branch->area_link = $request->area_link ;


        if($request->status == Null){
            $request->status = 0;
        }

        $branch->status = $request->status;
        $branch->created_at = Carbon::now();

        $branch->save();

        $notification = array(
            'message' => 'Branch Inserted Successfully.', 
            'alert-type' => 'success'
        );
        return redirect()->route('admin.branch.index')->with($notification);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $branch = Branch::find($id);
        $pageTitle = 'Branch View';
        return view('admin.branch.show', compact('branch','pageTitle'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $branch = Branch::find($id);
        $pageTitle = 'Branch Edit';
        return view('admin.branch.edit', compact('branch','pageTitle'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $branch = Branch::find($id);

        $branch->branch_name = $request->branch_name ;
        $branch->contact_no = $request->contact_no ;
        $branch->contact_no_optional = $request->contact_no_optional ;
        $branch->area_link = $request->area_link ;


        if($request->status == Null){
            $request->status = 0;
        }

        $branch->status = $request->status;
        $branch->Updated_at = Carbon::now();

        $branch->save();

        $notification = array(
            'message' => 'Branch Updated Successfully.', 
            'alert-type' => 'success'
        );
        return redirect()->route('admin.branch.index')->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $branch = Branch::find($id);
       
        $branch->delete();

        $notification = array(
            'message' => 'Branch Deleted Successfully.', 
            'alert-type' => 'error'
        );

        return redirect()->back()->with($notification);
    }

    public function active($id){

        $branch = Branch::find($id);
        $branch->status = 1;
        $branch->save();

        $notification = array(
            'message' => 'Branch Active Successfully.', 
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }

    public function inactive($id){
        $branch = Branch::find($id);
        $branch->status = 0;
        $branch->save();

        $notification = array(
            'message' => 'branch Disabled Successfully.', 
            'alert-type' => 'error'
        );

        return redirect()->back()->with($notification);
    }
}