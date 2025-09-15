<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subject;
use App\Models\Admission;
use App\Models\Department;
use App\Models\Group;
use Illuminate\Support\Facades\Auth;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = 'Group List';
        $groups = Group::all();
        return view('admin.group.index', compact('groups','pageTitle'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pageTitle = 'Create Group';
        $admissions = Admission::where('status', '1')->get();
        $departments = Department::where('status', '1')->get();
        return view('admin.group.create', compact('admissions', 'departments','pageTitle'));
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

        
        $group = Group::create([
            'name' => $request->name,
            'admission_id' => $request->admission_id,
            'department_id' => $request->department_id,
            'created_by' => Auth::user()->id ?? '1',
        ]);

        if ($request->file('image')) {
            $file = $request->file('image');
            $filename = date('YmdHi').$file->getClientOriginalName();
            $file->move(public_path('upload/group'), $filename);
            $group->image = $filename;
            $group->save();
        }

        flash()->addSuccess("Group created successfully.");
        return redirect()->route('admin.group.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pageTitle = 'Group Details';
        $group = Group::findOrFail($id);
        return view('admin.group.show', compact('group','pageTitle'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $pageTitle = 'Edit Group';
        $group = Group::findOrFail($id);
        $admissions = Admission::where('status', '1')->get();
        $departments = Department::where('status', '1')->get();
        return view('admin.group.edit', compact('group', 'admissions', 'departments','pageTitle'));
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

        $group = Group::findOrFail($id);
        $group->name = $request->name;
        $group->admission_id = $request->admission_id;
        $group->department_id = $request->department_id;
        $group->updated_by  = Auth::user()->id ?? '1';
        $group->status = $request->status;
        $group->save();

        if ($request->file('image')) {
            $file = $request->file('image');
            @unlink(public_path('upload/group/'.$group->image));
            $filename = date('YmdHi').$file->getClientOriginalName();
            $file->move(public_path('upload/group'), $filename);
            $group->image = $filename;
            $group->save();
        }

        flash()->addSuccess("Group updated successfully.");
        return redirect()->route('admin.group.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $group = Group::findOrFail($id);
        @unlink(public_path('upload/group/'.$group->image));
        $group->delete();

        flash()->addSuccess("Group deleted successfully.");
        return redirect()->route('admin.group.index');
    }
    
    public function getDepartments($admission_id)
    {
        $departments = Department::where('admission_id', $admission_id)->get();

        return response()->json($departments);
    }
}
