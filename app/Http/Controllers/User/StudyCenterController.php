<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class StudyCenterController extends Controller
{
    /**
     * Study Center — course list
     */
    public function index()
    {
        $pageTitle = 'স্টাডি সেন্টার';

        $courses = Course::with(['courseType', 'courseClass', 'courseCategory'])
                         ->where('status', 'active')
                         ->latest()
                         ->get();

        return view('user.study.center.index', compact('pageTitle', 'courses'));
    }

    /**
     * Single Course Details
     */
    public function show(string $slug)
    {
        $course = Course::with(['courseType', 'courseClass', 'courseCategory'])
                        ->where('slug', $slug)
                        ->where('status', 'active')
                        ->firstOrFail();

        // View count increment (session-based dedup)
        $viewKey = 'viewed_course_' . $course->id;
        if (!session()->has($viewKey)) {
            $course->increment('view_count');
            session()->put($viewKey, true);
        }

        $pageTitle = $course->title;

        // Related courses (same category, exclude current)
        $relatedCourses = Course::with(['courseType', 'courseClass'])
                                ->where('course_category_id', $course->course_category_id)
                                ->where('id', '!=', $course->id)
                                ->where('status', 'active')
                                ->take(3)
                                ->get();

        return view('user.study.center.show', compact('pageTitle', 'course', 'relatedCourses'));
    }
}