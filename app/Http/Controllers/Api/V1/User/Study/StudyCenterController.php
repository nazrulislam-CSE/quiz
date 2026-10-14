<?php

namespace App\Http\Controllers\Api\V1\User\Study;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseOrder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudyCenterController extends Controller
{
    /**
     * Study Center — Course List
     * 
     * Query Params:
     * - per_page (int, default 15, max 100)
     * - page (int, default 1)
     * - search (string)
     * - course_category_id (int)
     * - course_class_id (int)
     * - course_type_id (int)
     * - is_free (boolean: 0/1)
     * - sort (latest|price_low|price_high|popular)
     */
    public function index(Request $request): JsonResponse
    {
        /* ============================================================
           VALIDATION
        ============================================================ */
        $validated = $request->validate([
            'per_page'           => 'nullable|integer|min:1|max:100',
            'page'               => 'nullable|integer|min:1',
            'search'             => 'nullable|string|max:255',
            'course_category_id' => 'nullable|integer|exists:course_categories,id',
            'course_class_id'    => 'nullable|integer|exists:course_classes,id',
            'course_type_id'     => 'nullable|integer|exists:course_types,id',
            'is_free'            => 'nullable|in:0,1,true,false',
            'sort'               => 'nullable|in:latest,oldest,price_low,price_high,popular',
        ]);

        /* ============================================================
           QUERY BUILD
        ============================================================ */
        $query = Course::with(['courseType', 'courseClass', 'courseCategory'])
                       ->where('status', 'active');

        // Filter: Search (title + description)
        if (! empty($validated['search'])) {
            $search = $validated['search'];
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter: Course Category
        if (! empty($validated['course_category_id'])) {
            $query->where('course_category_id', $validated['course_category_id']);
        }

        // Filter: Course Class
        if (! empty($validated['course_class_id'])) {
            $query->where('course_class_id', $validated['course_class_id']);
        }

        // Filter: Course Type
        if (! empty($validated['course_type_id'])) {
            $query->where('course_type_id', $validated['course_type_id']);
        }

        // Filter: Is Free
        if (isset($validated['is_free']) && $validated['is_free'] !== '') {
            $isFree = in_array($validated['is_free'], ['1', 'true', true, 1], true);
            $query->where('is_free', $isFree);
        }

        /* ============================================================
           SORTING
        ============================================================ */
        $sort = $validated['sort'] ?? 'latest';

        switch ($sort) {
            case 'oldest':
                $query->oldest();
                break;

            case 'price_low':
                $query->orderByRaw('COALESCE(discount_price, price) ASC');
                break;

            case 'price_high':
                $query->orderByRaw('COALESCE(discount_price, price) DESC');
                break;

            case 'popular':
                $query->orderByDesc('view_count')->latest();
                break;

            case 'latest':
            default:
                $query->latest();
                break;
        }

        /* ============================================================
           PAGINATION
        ============================================================ */
        $perPage = (int) ($validated['per_page'] ?? 15);
        $page    = (int) ($validated['page'] ?? 1);

        $courses = $query->paginate($perPage, ['*'], 'page', $page);

        /* ============================================================
           TRANSFORM
        ============================================================ */
        $transformed = collect($courses->items())->map(function ($course) {
            return $this->transformCourse($course);
        });

        /* ============================================================
           RESPONSE
        ============================================================ */
        return response()->json([
            'success' => true,
            'message' => 'Study center courses retrieved successfully.',
            'data'    => $transformed,
            'meta'    => [
                'current_page'  => $courses->currentPage(),
                'per_page'      => $courses->perPage(),
                'total'         => $courses->total(),
                'last_page'     => $courses->lastPage(),
                'from'          => $courses->firstItem(),
                'to'            => $courses->lastItem(),
                'has_more'      => $courses->hasMorePages(),
            ],
            'links' => [
                'first' => $courses->url(1),
                'prev'  => $courses->previousPageUrl(),
                'next'  => $courses->nextPageUrl(),
                'last'  => $courses->url($courses->lastPage()),
            ],
            'filters' => [
                'search'             => $validated['search'] ?? null,
                'course_category_id' => $validated['course_category_id'] ?? null,
                'course_class_id'    => $validated['course_class_id'] ?? null,
                'course_type_id'     => $validated['course_type_id'] ?? null,
                'is_free'            => $validated['is_free'] ?? null,
                'sort'               => $sort,
            ],
        ], 200);
    }

    /**
     * Single Course Details
     */
    public function show(string $slug): JsonResponse
    {
        $course = Course::with(['courseType', 'courseClass', 'courseCategory'])
            ->where('slug', $slug)
            ->where('status', 'active')
            ->first();

        if (! $course) {
            return response()->json([
                'success' => false,
                'message' => 'Course not found.',
                'data'    => null,
            ], 404);
        }

        $course->increment('view_count');

        $relatedCourses = Course::with(['courseType', 'courseClass', 'courseCategory'])
            ->where('course_category_id', $course->course_category_id)
            ->where('id', '!=', $course->id)
            ->where('status', 'active')
            ->latest()
            ->take(3)
            ->get()
            ->map(fn ($c) => $this->transformCourse($c));

        $alreadyOwned = false;
        if (Auth::check()) {
            $alreadyOwned = CourseOrder::where('user_id', Auth::id())
                ->where('course_id', $course->id)
                ->where('payment_status', 'paid')
                ->exists();
        }

        return response()->json([
            'success' => true,
            'message' => 'Course details retrieved successfully.',
            'data'    => [
                'course'          => $this->transformCourse($course),
                'already_owned'   => $alreadyOwned,
                'related_courses' => $relatedCourses,
            ],
        ], 200);
    }

    /**
     * Transform course data for API
     */
    private function transformCourse(Course $course): array
    {
        return [
            'id'             => $course->id,
            'title'          => $course->title,
            'slug'           => $course->slug,
            'description'    => $course->description,
            'thumbnail'      => $course->thumbnail
                                    ? asset('storage/' . $course->thumbnail)
                                    : 'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?w=600&q=80',
            'price'          => (float) $course->price,
            'discount_price' => $course->discount_price !== null ? (float) $course->discount_price : null,
            'final_price'    => (float) $course->final_price,
            'has_discount'   => $course->has_discount,
            'discount_percent' => $course->discount_percent,
            'is_free'        => (bool) $course->is_free,
            'is_featured'    => (bool) $course->is_featured,
            'duration'       => $course->duration,
            'total_class'    => $course->total_class,
            'view_count'     => (int) $course->view_count,
            'status'         => $course->status,
            'course_type'    => $course->courseType ? [
                'id'   => $course->courseType->id,
                'name' => $course->courseType->name,
            ] : null,
            'course_class'   => $course->courseClass ? [
                'id'   => $course->courseClass->id,
                'name' => $course->courseClass->name,
            ] : null,
            'course_category' => $course->courseCategory ? [
                'id'   => $course->courseCategory->id,
                'name' => $course->courseCategory->name,
            ] : null,
            'created_at'     => $course->created_at?->toDateTimeString(),
        ];
    }
}