<?php

namespace App\Http\Controllers\Api\V1\User\Study;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\JsonResponse;

class StudyCenterController extends Controller
{
    /**
     * Study Center — Course List
     */
    public function index(): JsonResponse
    {
        $courses = Course::with([
                'courseType',
                'courseClass',
                'courseCategory'
            ])
            ->where('status', 'active')
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Study center courses retrieved successfully.',
            'data' => $courses,
        ], 200);
    }

    /**
     * Single Course Details
     */
    public function show(string $slug): JsonResponse
    {
        $course = Course::with([
                'courseType',
                'courseClass',
                'courseCategory'
            ])
            ->where('slug', $slug)
            ->where('status', 'active')
            ->first();

        if (!$course) {
            return response()->json([
                'success' => false,
                'message' => 'Course not found.',
                'data' => null,
            ], 404);
        }

        /*
        |--------------------------------------------------------------------------
        | View Count
        |--------------------------------------------------------------------------
        | Session-based deduplication is not ideal for API.
        | API request হলে প্রতিবার view count না বাড়াতে হলে
        | পরে user_id / IP based tracking করা যেতে পারে।
        */
        $course->increment('view_count');

        // Related Courses
        $relatedCourses = Course::with([
                'courseType',
                'courseClass',
                'courseCategory'
            ])
            ->where('course_category_id', $course->course_category_id)
            ->where('id', '!=', $course->id)
            ->where('status', 'active')
            ->latest()
            ->take(3)
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Course details retrieved successfully.',
            'data' => [
                'course' => $course,
                'related_courses' => $relatedCourses,
            ],
        ], 200);
    }
}