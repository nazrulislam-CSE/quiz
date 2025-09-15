<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ModelTest;
use App\Models\Admission;
use App\Models\Department;
use App\Models\Group;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ModelTestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = 'Final Model Test List';
        $models = ModelTest::latest()->get();
        return view('admin.model.index', compact('models', 'pageTitle'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pageTitle = 'Create Final Model Test';
        $admissions = Admission::where('status', '1')->get();
        $departments = Department::where('status', '1')->get();
        $groups = Group::where('status', '1')->get();
        return view('admin.model.create', compact('admissions', 'departments','groups', 'pageTitle'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'group_id' => 'required|exists:groups,id',
            'name' => 'required|string|max:255',
            'exam_duration' => 'required|integer',
            'exam_mark' => 'required|integer',
            'fee' => 'required|numeric',
        ]);

        $slug = Str::slug($request->name);
        $originalSlug = $slug;
        $counter = 1;

        while (ModelTest::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        $model = ModelTest::create([
            'group_id' => $request->group_id,
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description,
            'exam_duration' => $request->exam_duration,
            'exam_mark' => $request->exam_mark,
            'pass_mark' => $request->pass_mark,
            'fee' => $request->fee,
            'status' => $request->status ?? 1,
            'order' => $request->order ?? 0,
            'created_by' => Auth::user()->id ?? 1,
        ]);

        if ($request->file('image')) {
            $file = $request->file('image');
            $filename = date('YmdHi').$file->getClientOriginalName();
            $file->move(public_path('upload/model'), $filename);
            $model->image = $filename;
            $model->save();
        }

        flash()->addSuccess("Final Model Test created successfully.");
        return redirect()->route('admin.model.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pageTitle = 'Final Model Test Details';
        $model = ModelTest::findOrFail($id);
        return view('admin.model.show', compact('model', 'pageTitle'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $pageTitle = 'Edit Final Model Test';
        $model = ModelTest::findOrFail($id);
        $admissions = Admission::where('status', '1')->get();
        $departments = Department::where('status', '1')->get();
        $groups = Group::where('status', '1')->get();
        return view('admin.model.edit', compact('model','admissions', 'departments','groups', 'pageTitle'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'group_id' => 'required|exists:groups,id',
            'name' => 'required|string|max:255',
            'exam_duration' => 'required|integer',
            'exam_mark' => 'required|integer',
            'fee' => 'required|numeric',
        ]);

        $model = ModelTest::findOrFail($id);

        $slug = Str::slug($request->name);
        $originalSlug = $slug;
        $counter = 1;

        while (ModelTest::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        $model->update([
            'group_id' => $request->group_id,
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description,
            'exam_duration' => $request->exam_duration,
            'exam_mark' => $request->exam_mark,
            'pass_mark' => $request->pass_mark,
            'fee' => $request->fee,
            'status' => $request->status ?? 1,
            'order' => $request->order ?? 0,
        ]);

        if ($request->file('image')) {
            $file = $request->file('image');
            $filename = date('YmdHi').$file->getClientOriginalName();
            $file->move(public_path('upload/model'), $filename);
            ModelTest::where('id', $model->id)->update(['model' => $filename]);
        }

        flash()->addSuccess("Final Model Test updated successfully.");
        return redirect()->route('admin.model.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $model = ModelTest::findOrFail($id);
        @unlink(public_path('upload/model/'.$model->image));
        $model->delete();

        flash()->addSuccess("Final Model Test deleted successfully.");
        return redirect()->route('admin.model.index');
    }

    public function getDepartments($admission_id)
    {
        $departments = Department::where('admission_id', $admission_id)->get();
        return response()->json($departments);
    }
    
    public function getGroups($department_id)
    {
        $groups = Group::where('department_id', $department_id)->get();
        return response()->json($groups);
    }

}
