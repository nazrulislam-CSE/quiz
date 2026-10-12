<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseType;
use App\Models\CourseClass;
use App\Models\CourseCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CourseController extends Controller
{
    /* ============================================================
       INDEX — List + Filter
    ============================================================ */
    public function index(Request $request)
    {
        $pageTitle = 'Course List';

        $query = Course::with([
            'courseType',
            'courseClass',
            'courseCategory'
        ]);

        if ($request->filled('course_type_id')) {
            $query->where('course_type_id', $request->course_type_id);
        }

        if ($request->filled('course_class_id')) {
            $query->where('course_class_id', $request->course_class_id);
        }

        if ($request->filled('course_category_id')) {
            $query->where('course_category_id', $request->course_category_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $courses = $query->latest()->get();

        $courseTypes = CourseType::where('status', 'active')
            ->orderBy('name')->get();

        $courseClasses = CourseClass::where('status', 'active')
            ->orderBy('name')->get();

        $courseCategories = CourseCategory::where('status', 'active')
            ->orderBy('name')->get();

        return view('admin.course.index', compact(
            'courses',
            'courseTypes',
            'courseClasses',
            'courseCategories',
            'pageTitle'
        ));
    }

    /* ============================================================
       STORE — Create Course
    ============================================================ */
    public function store(Request $request)
    {
        $request->validate([
            'course_type_id'     => 'required|exists:course_types,id',
            'course_class_id'    => 'required|exists:course_classes,id',
            'course_category_id' => 'required|exists:course_categories,id',

            'title'              => 'required|string|max:255|unique:courses,title',
            'description'        => 'nullable|string',

            'thumbnail'          => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            // Teacher information
            'teacher_name'        => 'nullable|string|max:255',
            'teacher_designation' => 'nullable|string|max:255',
            'teacher_photo'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'teacher_experience'  => 'nullable|string|max:255',

            // Pricing
            'price'              => 'required|numeric|min:0',
            'discount_price'     => 'nullable|numeric|min:0|lt:price',
            'is_free'            => 'nullable|boolean',

            // Extra information
            'duration'            => 'nullable|string|max:100',
            'total_class'         => 'nullable|integer|min:0',

            // Status
            'status'              => 'nullable|in:active,inactive',
        ], [
            'discount_price.lt' => 'Discount price must be less than price.',
            'thumbnail.max' => 'Course image size must be less than 2MB.',
            'thumbnail.mimes' => 'Course image must be jpg, jpeg, png, or webp.',
            'teacher_photo.max' => 'Teacher image size must be less than 2MB.',
            'teacher_photo.mimes' => 'Teacher image must be jpg, jpeg, png, or webp.',
        ]);

        // Upload course thumbnail
        $thumbnailPath = null;

        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')
                ->store('courses', 'public');
        }

        // Upload teacher photo
        $teacherPhotoPath = null;

        if ($request->hasFile('teacher_photo')) {
            $teacherPhotoPath = $request->file('teacher_photo')
                ->store('teachers', 'public');
        }

        Course::create([
            'course_type_id'     => $request->course_type_id,
            'course_class_id'    => $request->course_class_id,
            'course_category_id' => $request->course_category_id,

            'title'              => $request->title,
            'slug'               => Str::slug($request->title) . '-' . uniqid(),
            'description'        => $request->description,
            'thumbnail'          => $thumbnailPath,

            // Teacher information
            'teacher_name'        => $request->teacher_name,
            'teacher_designation' => $request->teacher_designation
                ?: 'Senior Instructor',
            'teacher_photo'       => $teacherPhotoPath,
            'teacher_experience'  => $request->teacher_experience,

            // Pricing
            'price'              => $request->price,
            'discount_price'     => $request->discount_price,
            'is_free'            => $request->boolean('is_free'),

            // Extra information
            'duration'           => $request->duration,
            'total_class'        => $request->total_class,

            // Status
            'status'             => $request->status ?? 'active',
        ]);

        flash()->addSuccess('Course Created Successfully.');

        return redirect()->route('admin.course.index');
    }

    /* ============================================================
       UPDATE — Update Course
    ============================================================ */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'course_type_id'     => 'required|exists:course_types,id',
            'course_class_id'    => 'required|exists:course_classes,id',
            'course_category_id' => 'required|exists:course_categories,id',

            'title'              => 'required|string|max:255|unique:courses,title,' . $id,
            'description'        => 'nullable|string',

            'thumbnail'          => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            // Teacher information
            'teacher_name'        => 'nullable|string|max:255',
            'teacher_designation' => 'nullable|string|max:255',
            'teacher_photo'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'teacher_experience'  => 'nullable|string|max:255',

            // Pricing
            'price'              => 'required|numeric|min:0',
            'discount_price'     => 'nullable|numeric|min:0|lt:price',
            'is_free'            => 'nullable|boolean',

            // Extra information
            'duration'            => 'nullable|string|max:100',
            'total_class'         => 'nullable|integer|min:0',

            // Status
            'status'              => 'nullable|in:active,inactive',
        ], [
            'discount_price.lt' => 'Discount price must be less than price.',
            'thumbnail.max' => 'Course image size must be less than 2MB.',
            'thumbnail.mimes' => 'Course image must be jpg, jpeg, png, or webp.',
            'teacher_photo.max' => 'Teacher image size must be less than 2MB.',
            'teacher_photo.mimes' => 'Teacher image must be jpg, jpeg, png, or webp.',
        ]);

        $course = Course::findOrFail($id);

        // Update course thumbnail
        $thumbnailPath = $course->thumbnail;

        if ($request->hasFile('thumbnail')) {
            if (
                $course->thumbnail &&
                Storage::disk('public')->exists($course->thumbnail)
            ) {
                Storage::disk('public')->delete($course->thumbnail);
            }

            $thumbnailPath = $request->file('thumbnail')
                ->store('courses', 'public');
        }

        // Update teacher photo
        $teacherPhotoPath = $course->teacher_photo;

        if ($request->hasFile('teacher_photo')) {
            if (
                $course->teacher_photo &&
                Storage::disk('public')->exists($course->teacher_photo)
            ) {
                Storage::disk('public')->delete($course->teacher_photo);
            }

            $teacherPhotoPath = $request->file('teacher_photo')
                ->store('teachers', 'public');
        }

        $course->update([
            'course_type_id'     => $request->course_type_id,
            'course_class_id'    => $request->course_class_id,
            'course_category_id' => $request->course_category_id,

            'title'              => $request->title,
            'slug'               => Str::slug($request->title) . '-' . $course->id,
            'description'        => $request->description,
            'thumbnail'          => $thumbnailPath,

            // Teacher information
            'teacher_name'        => $request->teacher_name,
            'teacher_designation' => $request->teacher_designation
                ?: 'Senior Instructor',
            'teacher_photo'       => $teacherPhotoPath,
            'teacher_experience'  => $request->teacher_experience,

            // Pricing
            'price'              => $request->price,
            'discount_price'     => $request->discount_price,
            'is_free'            => $request->boolean('is_free'),

            // Extra information
            'duration'           => $request->duration,
            'total_class'        => $request->total_class,

            // Status
            'status'             => $request->status ?? 'active',
        ]);

        flash()->addSuccess('Course Updated Successfully.');

        return redirect()->route('admin.course.index');
    }

    /* ============================================================
       DESTROY — Delete Course + Images
    ============================================================ */
    public function destroy(string $id)
    {
        $course = Course::findOrFail($id);

        // Delete course thumbnail
        if (
            $course->thumbnail &&
            Storage::disk('public')->exists($course->thumbnail)
        ) {
            Storage::disk('public')->delete($course->thumbnail);
        }

        // Delete teacher photo
        if (
            $course->teacher_photo &&
            Storage::disk('public')->exists($course->teacher_photo)
        ) {
            Storage::disk('public')->delete($course->teacher_photo);
        }

        $course->delete();

        flash()->addError('Course Deleted Successfully.');

        return redirect()->route('admin.course.index');
    }

    /* ============================================================
       Dependent Dropdown JSON Endpoints
    ============================================================ */

    public function getClasses($typeId)
    {
        $classes = CourseClass::where('course_type_id', $typeId)
            ->where('status', 'active')
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json($classes);
    }

    public function getCategories($classId)
    {
        $categories = CourseCategory::where('course_class_id', $classId)
            ->where('status', 'active')
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json($categories);
    }
}