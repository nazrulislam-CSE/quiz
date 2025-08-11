<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admission;
use App\Models\Department;
use Illuminate\Support\Carbon;
use Auth;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = 'Department List';
        $departments = Department::where('status', 1)->latest()->get();
        return view('admin.department.index', compact('departments', 'pageTitle'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pageTitle = 'Department Create';
        $admissions = Admission::where('status', 1)->get();
        return view('admin.department.create', compact('pageTitle', 'admissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'admission_id' => 'required|exists:admissions,id',
        ]);

        $department = new Department;

        if ($request->status == Null) {
            $request->status = 0;
        }

        $department->status = $request->status;
        $department->name = $request->name;
        $department->admission_id = $request->admission_id;
        $department->created_by = Auth::user()->id ?? '1';
        $department->created_at = Carbon::now();
        $department->save();

        if ($request->file('image')) {
            $file = $request->file('image');
            @unlink(public_path('upload/department/'.$department->image));
            $filename = date('YmdHi').$file->getClientOriginalName();
            $file->move(public_path('upload/department'),$filename);
            $department['image'] = $filename;
        }

        $department->save();

        flash()->addSuccess("Department Department Successfully.");
        $url = '/admin/department/index';
        return redirect($url);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $department = Department::findOrFail($id);
        $pageTitle = 'Department Details';
        return view('admin.department.show', compact('department', 'pageTitle'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $department = Department::findOrFail($id);
        $pageTitle = 'Department Edit';
        $admissions = Admission::where('status', 1)->get();
        return view('admin.department.edit', compact('department', 'pageTitle', 'admissions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $department = Department::findOrFail($id);

        if ($request->status == Null) {
            $request->status = 0;
        }

        $this->validate($request, [
            'name' => 'required',
            'admission_id' => 'required|exists:admissions,id',
        ]);

        $department->status = $request->status;
        $department->name = $request->name;
        $department->admission_id = $request->admission_id;
        $department->updated_by = Auth::user()->id ?? '1';
        $department->updated_at = Carbon::now();
        $department->save();

        if ($request->file('image')) {
            $file = $request->file('image');
            @unlink(public_path('upload/department/'.$department->image));
            $filename = date('YmdHi').$file->getClientOriginalName();
            $file->move(public_path('upload/department'),$filename);
            $department['image'] = $filename;
        }

        flash()->addSuccess("Department Updated Successfully.");
        return redirect()->route('admin.department.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $department = Department::findOrFail($id);

        try {
            if (file_exists(public_path('upload/department/'.$department->image))) {
                @unlink(public_path('upload/department/'.$department->image));
            }
            $department->delete();
            flash()->addSuccess("Department Deleted Successfully.");
        } catch (\Exception $e) {
            flash()->addError("Failed to delete department: " . $e->getMessage());
        }

        return redirect()->route('admin.department.index');
    }
}
