<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseType;
use App\Models\CourseClass;
use App\Models\CourseCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CourseController extends Controller
{
   public function index(Request $request)
    {
        $pageTitle = 'Course List';

        $query = Course::with(['courseType', 'courseClass', 'courseCategory']);

        // Filter: Course Type
        if ($request->filled('course_type_id')) {
            $query->where('course_type_id', $request->course_type_id);
        }

        // Filter: Course Class
        if ($request->filled('course_class_id')) {
            $query->where('course_class_id', $request->course_class_id);
        }

        // Filter: Course Category
        if ($request->filled('course_category_id')) {
            $query->where('course_category_id', $request->course_category_id);
        }

        // Filter: Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter: Search by title
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $courses = $query->latest()->get();

        $courseTypes      = CourseType::where('status', 'active')->orderBy('name')->get();
        $courseClasses    = CourseClass::where('status', 'active')->orderBy('name')->get();
        $courseCategories = CourseCategory::where('status', 'active')->orderBy('name')->get();

        return view('admin.course.index', compact(
            'courses',
            'courseTypes',
            'courseClasses',
            'courseCategories',
            'pageTitle'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'course_type_id'     => 'required|exists:course_types,id',
            'course_class_id'    => 'required|exists:course_classes,id',
            'course_category_id' => 'required|exists:course_categories,id',
            'title'              => 'required|string|max:255|unique:courses,title',
            'description'        => 'nullable|string',
            'price'              => 'required|numeric|min:0',
            'discount_price'     => 'nullable|numeric|min:0|lt:price',
            'is_free'            => 'nullable|boolean',
            'duration'           => 'nullable|string|max:100',
            'total_class'        => 'nullable|integer|min:0',
            'status'             => 'nullable|in:active,inactive',
        ], [
            'discount_price.lt' => 'Discount price must be less than price.',
        ]);

        Course::create([
            'course_type_id'     => $request->course_type_id,
            'course_class_id'    => $request->course_class_id,
            'course_category_id' => $request->course_category_id,
            'title'              => $request->title,
            'slug'               => Str::slug($request->title) . '-' . uniqid(),
            'description'        => $request->description,
            'price'              => $request->price,
            'discount_price'     => $request->discount_price,
            'is_free'            => $request->boolean('is_free'),
            'duration'           => $request->duration,
            'total_class'        => $request->total_class,
            'status'             => $request->status ?? 'active',
        ]);

        flash()->addSuccess('Course Created Successfully.');
        return redirect()->route('admin.course.index');
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'course_type_id'     => 'required|exists:course_types,id',
            'course_class_id'    => 'required|exists:course_classes,id',
            'course_category_id' => 'required|exists:course_categories,id',
            'title'              => 'required|string|max:255|unique:courses,title,' . $id,
            'description'        => 'nullable|string',
            'price'              => 'required|numeric|min:0',
            'discount_price'     => 'nullable|numeric|min:0|lt:price',
            'is_free'            => 'nullable|boolean',
            'duration'           => 'nullable|string|max:100',
            'total_class'        => 'nullable|integer|min:0',
            'status'             => 'nullable|in:active,inactive',
        ], [
            'discount_price.lt' => 'Discount price must be less than price.',
        ]);

        $course = Course::findOrFail($id);

        $course->update([
            'course_type_id'     => $request->course_type_id,
            'course_class_id'    => $request->course_class_id,
            'course_category_id' => $request->course_category_id,
            'title'              => $request->title,
            'slug'               => Str::slug($request->title) . '-' . $course->id,
            'description'        => $request->description,
            'price'              => $request->price,
            'discount_price'     => $request->discount_price,
            'is_free'            => $request->boolean('is_free'),
            'duration'           => $request->duration,
            'total_class'        => $request->total_class,
            'status'             => $request->status ?? 'active',
        ]);

        flash()->addSuccess('Course Updated Successfully.');
        return redirect()->route('admin.course.index');
    }

    public function destroy(string $id)
    {
        $course = Course::findOrFail($id);
        $course->delete();

        flash()->addError('Course Deleted Successfully.');
        return redirect()->route('admin.course.index');
    }

    /* ============================================================
       Dependent Dropdown JSON endpoints
    ============================================================ */

    /**
     * Get course classes by course type (for dependent dropdown)
     */
    public function getClasses($typeId)
    {
        $classes = CourseClass::where('course_type_id', $typeId)
                              ->where('status', 'active')
                              ->orderBy('name')
                              ->get(['id', 'name']);

        return response()->json($classes);
    }

    /**
     * Get course categories by course class (for dependent dropdown)
     */
    public function getCategories($classId)
    {
        $categories = CourseCategory::where('course_class_id', $classId)
                                    ->where('status', 'active')
                                    ->orderBy('name')
                                    ->get(['id', 'name']);

        return response()->json($categories);
    }
}