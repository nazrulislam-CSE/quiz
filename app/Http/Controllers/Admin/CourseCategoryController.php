<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CourseCategory;
use App\Models\CourseClass;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CourseCategoryController extends Controller
{
    public function index()
    {
        $pageTitle      = 'Course Category List';
        $courseCategory = CourseCategory::with('courseClass')->latest()->get();
        $courseClasses  = CourseClass::where('status', 'active')->orderBy('name')->get();

        return view('admin.course_category.index', [
            'pageTitle'      => $pageTitle,
            'courseCategories' => $courseCategory,
            'courseClasses'  => $courseClasses,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'course_class_id' => 'required|exists:course_classes,id',
            'name'            => 'required|string|max:255|unique:course_categories,name',
            'status'          => 'nullable|in:active,inactive',
        ]);

        CourseCategory::create([
            'course_class_id' => $request->course_class_id,
            'name'            => $request->name,
            'slug'            => Str::slug($request->name),
            'status'          => $request->status ?? 'active',
        ]);

        flash()->addSuccess('Course Category Created Successfully.');
        return redirect()->route('admin.course.category.index');
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'course_class_id' => 'required|exists:course_classes,id',
            'name'            => 'required|string|max:255|unique:course_categories,name,' . $id,
            'status'          => 'nullable|in:active,inactive',
        ]);

        $courseCategory = CourseCategory::findOrFail($id);

        $courseCategory->update([
            'course_class_id' => $request->course_class_id,
            'name'            => $request->name,
            'slug'            => Str::slug($request->name),
            'status'          => $request->status ?? 'active',
        ]);

        flash()->addSuccess('Course Category Updated Successfully.');
        return redirect()->route('admin.course.category.index');
    }

    public function destroy(string $id)
    {
        $courseCategory = CourseCategory::findOrFail($id);
        $courseCategory->delete();

        flash()->addError('Course Category Deleted Successfully.');
        return redirect()->route('admin.course.category.index');
    }
}