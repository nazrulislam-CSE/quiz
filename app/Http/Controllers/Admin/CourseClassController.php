<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CourseClass;
use App\Models\CourseType;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CourseClassController extends Controller
{
    public function index()
    {
        $pageTitle    = 'Course Class List';
        $courseClasses = CourseClass::with('courseType')->latest()->get();
        $courseTypes   = CourseType::where('status', 'active')->orderBy('name')->get();

        return view('admin.course_class.index', compact('courseClasses', 'courseTypes', 'pageTitle'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'course_type_id' => 'required|exists:course_types,id',
            'name'           => 'required|string|max:255|unique:course_classes,name',
            'status'         => 'nullable|in:active,inactive',
        ]);

        CourseClass::create([
            'course_type_id' => $request->course_type_id,
            'name'           => $request->name,
            'slug'           => Str::slug($request->name),
            'status'         => $request->status ?? 'active',
        ]);

        flash()->addSuccess('Course Class Created Successfully.');
        return redirect()->route('admin.course.class.index');
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'course_type_id' => 'required|exists:course_types,id',
            'name'           => 'required|string|max:255|unique:course_classes,name,' . $id,
            'status'         => 'nullable|in:active,inactive',
        ]);

        $courseClass = CourseClass::findOrFail($id);

        $courseClass->update([
            'course_type_id' => $request->course_type_id,
            'name'           => $request->name,
            'slug'           => Str::slug($request->name),
            'status'         => $request->status ?? 'active',
        ]);

        flash()->addSuccess('Course Class Updated Successfully.');
        return redirect()->route('admin.course.class.index');
    }

    public function destroy(string $id)
    {
        $courseClass = CourseClass::findOrFail($id);
        $courseClass->delete();

        flash()->addError('Course Class Deleted Successfully.');
        return redirect()->route('admin.course.class.index');
    }
}