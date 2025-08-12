<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subject;
use App\Models\Admission;
use App\Models\Department;
use Illuminate\Support\Facades\Auth;


class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = 'Subject List';
        $subjects = Subject::all();
        return view('admin.subject.index', compact('subjects','pageTitle'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pageTitle = 'Create Subject';
        $admissions = Admission::where('status', '1')->get();
        $departments = Department::where('status', '1')->get();
        return view('admin.subject.create', compact('admissions', 'departments','pageTitle'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'admission_id' => 'required|exists:admissions,id',
            'department_id' => 'required|exists:departments,id',
        ]);

        
        Subject::create([
            'name' => $request->name,
            'admission_id' => $request->admission_id,
            'department_id' => $request->department_id,
            'created_by' => Auth::user()->id ?? '1',
        ]);

        if ($request->file('image')) {
            $file = $request->file('image');
            $filename = date('YmdHi').$file->getClientOriginalName();
            $file->move(public_path('upload/subject'), $filename);
            Subject::where('id', $subject->id)->update(['subject' => $filename]);
        }

        flash()->addSuccess("Subject created successfully.");
        return redirect()->route('admin.subject.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pageTitle = 'Subject Details';
        $subject = Subject::findOrFail($id);
        return view('admin.subject.show', compact('subject','pageTitle'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $pageTitle = 'Edit Subject';
        $subject = Subject::findOrFail($id);
        $admissions = Admission::where('status', '1')->get();
        $departments = Department::where('status', '1')->get();
        return view('admin.subject.edit', compact('subject', 'admissions', 'departments','pageTitle'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // dd($request->all());
        $request->validate([
            'name' => 'required|string|max:255',
            'admission_id' => 'required|exists:admissions,id',
            'department_id' => 'required|exists:departments,id',
        ]);

        if ($request->status == Null) {
            $request->status = 1;
        }

        $subject = Subject::findOrFail($id);
        $subject->name = $request->name;
        $subject->admission_id = $request->admission_id;
        $subject->department_id = $request->department_id;
        $subject->updated_by  = Auth::user()->id ?? '1';
        $subject->status = $request->status;
        $subject->save();

        if ($request->file('image')) {
            $file = $request->file('image');
            @unlink(public_path('upload/subject/'.$subject->image));
            $filename = date('YmdHi').$file->getClientOriginalName();
            $file->move(public_path('upload/subject'), $filename);
            $subject->image = $filename;
            $subject->save();
        }

        flash()->addSuccess("Subject updated successfully.");
        return redirect()->route('admin.subject.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $subject = Subject::findOrFail($id);
        @unlink(public_path('upload/subject/'.$subject->image));
        $subject->delete();

        flash()->addSuccess("Subject deleted successfully.");
        return redirect()->route('admin.subject.index');
    }

    public function getDepartments($admission_id)
    {
        $departments = Department::where('admission_id', $admission_id)->get();

        return response()->json($departments);
    }

}
