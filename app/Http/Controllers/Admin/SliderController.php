<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Slider;
use Illuminate\Support\Carbon;
use Session;

class SliderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = 'Slider List';
        $sliders = Slider::latest()->get();
        return view('admin.slider.index',compact('sliders','pageTitle'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pageTitle = 'Slider Create';
        return view('admin.slider.create',compact('pageTitle'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'image' => 'required|image|mimes:jpg,jpeg,png,gif,svg|max:2048',
            // 'description' => 'required',
        ]);


        $slider = new Slider;

        if($request->status == Null){
            $request->status = 0;
        }
      
        $slider->status = $request->status;
        $slider->created_at = Carbon::now();
        $slider->save();


        if ($request->file('image')) {
            $file = $request->file('image');
            @unlink(public_path('upload/slider/'.$slider->image));
            $filename = date('YmdHi').$file->getClientOriginalName();
            $file->move(public_path('upload/slider'),$filename);
            $slider['image'] = $filename;
        }

        $slider->save();

        flash()->addSuccess("Slider Created Successfully.");
        $url = '/admin/slider/index';
        return redirect($url);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pageTitle = 'Slider Show';
        $slider = Slider::find($id);
        return view('admin.slider.show',compact('pageTitle','slider'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $slider = Slider::find($id);
        $pageTitle = 'Slider Edit';
        return view('admin.slider.edit', compact('slider','pageTitle'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $slider = Slider::find($id);

        if($request->status == Null){
            $request->status = 0;
        }
       
        $slider->status = $request->status;


        $slider->updated_at = Carbon::now();

        $slider->save();

        if ($request->file('image')) {
            $file = $request->file('image');
            @unlink(public_path('upload/slider/'.$slider->image));
            $filename = date('YmdHi').$file->getClientOriginalName();
            $file->move(public_path('upload/slider'),$filename);
            $slider['image'] = $filename;
        }

        $slider->save();


        flash()->addSuccess("Slider Updated Successfully.");
        $url = '/admin/slider/index';
        return redirect($url);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $slider = Slider::find($id);

        try {
            if(file_exists($slider->image)){
                unlink($slider->image);
            }
        } catch (Exception $e) {

        }

        $slider->delete();


        flash()->addError("Slider Deleted Successfully.");
        $url = '/admin/slider/index';
        return redirect($url);
    }
}
