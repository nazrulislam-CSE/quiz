<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CourseType;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CourseTypeController extends Controller
{
    public function index()
    {
        $pageTitle   = 'Course Type List';
        $courseTypes = CourseType::latest()->get();

        return view('admin.course_type.index', compact('courseTypes', 'pageTitle'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'   => 'required|string|max:255|unique:course_types,name',
            'status' => 'nullable|in:active,inactive',
        ]);

        CourseType::create([
            'name'   => $request->name,
            'slug'   => Str::slug($request->name),
            'status' => $request->status ?? 'active',
        ]);

        flash()->addSuccess('Course Type Created Successfully.');
        return redirect()->route('admin.course.type.index');
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'name'   => 'required|string|max:255|unique:course_types,name,' . $id,
            'status' => 'nullable|in:active,inactive',
        ]);

        $courseType = CourseType::findOrFail($id);

        $courseType->update([
            'name'   => $request->name,
            'slug'   => Str::slug($request->name),
            'status' => $request->status ?? 'active',
        ]);

        flash()->addSuccess('Course Type Updated Successfully.');
        return redirect()->route('admin.course.type.index');
    }

    public function destroy(string $id)
    {
        $courseType = CourseType::findOrFail($id);
        $courseType->delete();

        flash()->addError('Course Type Deleted Successfully.');
        return redirect()->route('admin.course.type.index');
    }
}