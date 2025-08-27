<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Feature;
use Illuminate\Support\Carbon;
use Session;


class FeatureController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = 'Feature List';
        $features = Feature::latest()->get();
        return view('admin.feature.index',compact('pageTitle','features'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pageTitle = 'Feature Create';
        return view('admin.feature.create',compact('pageTitle'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'icon' => 'required',
            'title' => 'required',
            'description' => 'required',
        ]);


        $feature = new Feature;

        if($request->status == Null){
            $request->status = 0;
        }
      
        $feature->status = $request->status;
        $feature->icon = $request->icon;
        $feature->title = $request->title;
        $feature->description = $request->description;
        $feature->save();

        flash()->addSuccess("Feature Created Successfully.");
        $url = '/admin/features/index';
        return redirect($url);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pageTitle = 'Feature Show';
        $feature = Feature::find($id);
        return view('admin.feature.show',compact('pageTitle','feature'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $feature = Feature::find($id);
        $pageTitle = 'Feature Edit';
        return view('admin.feature.edit', compact('feature','pageTitle'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->validate($request, [
            'icon' => 'required',
            'title' => 'required',
            'description' => 'required',
        ]);

        $feature = Feature::find($id);

        if($request->status == Null){
            $request->status = 0;
        }
       
        $feature->status = $request->status;
        $feature->icon = $request->icon;
        $feature->title = $request->title;
        $feature->description = $request->description;

        $feature->save();


        flash()->addSuccess("Feature Updated Successfully.");
        $url = '/admin/features/index';
        return redirect($url);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $feature = Feature::find($id);
        $feature->delete();

        flash()->addError("Feature Deleted Successfully.");
        $url = '/admin/features/index';
        return redirect($url);
    }
}
