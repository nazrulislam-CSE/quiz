<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Teacher;
use Illuminate\Support\Carbon;
use Session;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = 'Teacher List';
        $teachers = Teacher::latest()->get();
        return view('admin.teacher.index',compact('teachers','pageTitle'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pageTitle = 'Teacher Create';
        return view('admin.teacher.create',compact('pageTitle'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'designation' => 'required',
            'description' => 'required',
            'image' => 'required|image|mimes:jpg,jpeg,png,gif,svg|max:2048',
            // 'description' => 'required',
        ]);


        $teacher = new Teacher;

        $teacher->name            = $request->name;
        $teacher->designation     = $request->designation;
        $teacher->description     = $request->description;

        if($request->status == Null){
            $request->status = 0;
        }
       
        $teacher->status = $request->status;
        $teacher->created_at = Carbon::now();
        $teacher->save();


        if ($request->file('image')) {
            $file = $request->file('image');
            @unlink(public_path('upload/teacher/'.$teacher->image));
            $filename = date('YmdHi').$file->getClientOriginalName();
            $file->move(public_path('upload/teacher'),$filename);
            $teacher['image'] = $filename;
        }

        $teacher->save();

        flash()->addSuccess("Teacher Created Successfully.");
        $url = '/admin/teacher/index';
        return redirect($url);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pageTitle = 'Teacher Show';
        $teacher = Teacher::find($id);
        return view('admin.teacher.show',compact('pageTitle','teacher'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $teacher = Teacher::find($id);
        $pageTitle = 'Teacher Edit';
        return view('admin.teacher.edit', compact('teacher','pageTitle'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $teacher = Teacher::find($id);

        $teacher->name            = $request->name;
        $teacher->designation     = $request->designation;
        $teacher->description     = $request->description;


        if($request->status == Null){
            $request->status = 0;
        }
      
        $teacher->status = $request->status;


        $teacher->updated_at = Carbon::now();

        $teacher->save();

        if ($request->file('image')) {
            $file = $request->file('image');
            @unlink(public_path('upload/teacher/'.$teacher->image));
            $filename = date('YmdHi').$file->getClientOriginalName();
            $file->move(public_path('upload/teacher'),$filename);
            $teacher['image'] = $filename;
        }

        $teacher->save();


        flash()->addSuccess("Teacher Updated Successfully.");
        $url = '/admin/teacher/index';
        return redirect($url);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $teacher = Teacher::find($id);

        try {
            if(file_exists($teacher->image)){
                unlink('upload/teacher/'.$teacher->image);
            }
        } catch (Exception $e) {

        }

        $teacher->delete();


        flash()->addError("Teacher Deleted Successfully.");
        $url = '/admin/teacher/index';
        return redirect($url);
    }
}
