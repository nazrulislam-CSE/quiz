<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use Illuminate\Support\Carbon;
use Session;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = 'Student List';
        $students = Student::latest()->get();
        return view('admin.student.index',compact('students','pageTitle'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pageTitle = 'Student Create';
        return view('admin.student.create',compact('pageTitle'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'description' => 'required',
            'image' => 'required|image|mimes:jpg,jpeg,png,gif,svg|max:2048',
            // 'description' => 'required',
        ]);


        $student = new Student;

        $student->name            = $request->name;
        $student->description     = $request->description;

        if($request->status == Null){
            $request->status = 0;
        }
       
        $student->status = $request->status;
        $student->created_at = Carbon::now();
        $student->save();


        if ($request->file('image')) {
            $file = $request->file('image');
            @unlink(public_path('upload/student/'.$student->image));
            $filename = date('YmdHi').$file->getClientOriginalName();
            $file->move(public_path('upload/student'),$filename);
            $student['image'] = $filename;
        }

        $student->save();

        flash()->addSuccess("Student Created Successfully.");
        $url = '/admin/student/index';
        return redirect($url);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pageTitle = 'Student Show';
        $student = Student::find($id);
        return view('admin.student.show',compact('pageTitle','student'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $student = Student::find($id);
        $pageTitle = 'Student Edit';
        return view('admin.student.edit', compact('student','pageTitle'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $student = Student::find($id);

        $student->name            = $request->name;
        $student->designation     = $request->designation;
        $student->description     = $request->description;


        if($request->status == Null){
            $request->status = 0;
        }
      
        $student->status = $request->status;


        $student->updated_at = Carbon::now();

        $student->save();

        if ($request->file('image')) {
            $file = $request->file('image');
            @unlink(public_path('upload/student/'.$student->image));
            $filename = date('YmdHi').$file->getClientOriginalName();
            $file->move(public_path('upload/student'),$filename);
            $student['image'] = $filename;
        }

        $student->save();


        flash()->addSuccess("Student Updated Successfully.");
        $url = '/admin/student/index';
        return redirect($url);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $student = Student::find($id);

        try {
            if(file_exists($student->image)){
                unlink('upload/student/'.$student->image);
            }
        } catch (Exception $e) {

        }

        $student->delete();


        flash()->addError("Student Deleted Successfully.");
        $url = '/admin/student/index';
        return redirect($url);
    }
}
