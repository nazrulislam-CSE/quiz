<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Program;
use Illuminate\Support\Carbon;
use Session;
use Illuminate\Support\Str;

class ProgramController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = 'Program List';
        $programs = Program::latest()->get();
        return view('admin.program.index',compact('pageTitle','programs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pageTitle = 'Program Create';
        return view('admin.program.create',compact('pageTitle'));
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


        $program = new Program;

        if($request->status == Null){
            $request->status = 0;
        }
      
        $program->status = $request->status;
        $program->name = $request->name;
        $program->slug = Str::slug($request->name);
        $program->description = $request->description;
        $program->created_at = Carbon::now();
        $program->save();


        if ($request->file('image')) {
            $file = $request->file('image');
            @unlink(public_path('upload/program/'.$program->image));
            $filename = date('YmdHi').$file->getClientOriginalName();
            $file->move(public_path('upload/program'),$filename);
            $program['image'] = $filename;
        }

        $program->save();

        flash()->addSuccess("Program Created Successfully.");
        $url = '/admin/program/index';
        return redirect($url);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pageTitle = 'Program Show';
        $program = Program::find($id);
        return view('admin.program.show',compact('pageTitle','program'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $program = Program::find($id);
        $pageTitle = 'Program Edit';
        return view('admin.program.edit', compact('program','pageTitle'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $program = Program::find($id);

        if($request->status == Null){
            $request->status = 0;
        }
       
        $program->status = $request->status;
        $program->name = $request->name;
        $program->slug = Str::slug($request->name);
        $program->description = $request->description;
        $program->updated_at = Carbon::now();

        $program->save();

        if ($request->file('image')) {
            $file = $request->file('image');
            @unlink(public_path('upload/program/'.$slider->image));
            $filename = date('YmdHi').$file->getClientOriginalName();
            $file->move(public_path('upload/program'),$filename);
            $program['image'] = $filename;
        }

        $program->save();


        flash()->addSuccess("Program Updated Successfully.");
        $url = '/admin/program/index';
        return redirect($url);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $program = Program::find($id);

        try {
            if(file_exists($program->image)){
                unlink($program->image);
            }
        } catch (Exception $e) {

        }

        $program->delete();


        flash()->addError("Program Deleted Successfully.");
        $url = '/admin/program/index';
        return redirect($url);
    }
}
