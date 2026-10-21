<?php

namespace App\Http\Controllers\Api\V1\User\Study;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseOrder;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudyCenterController extends Controller
{
    /* ============================================================
       INDEX — Course List (with filters, pagination, search)
    ============================================================ */
    public function index(Request $request): JsonResponse
    {
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

        $query = Course::with(['courseType', 'courseClass', 'courseCategory'])
                       ->where('status', 'active');

        // Search
        if (! empty($validated['search'])) {
            $search = $validated['search'];
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filters
        if (! empty($validated['course_category_id'])) {
            $query->where('course_category_id', $validated['course_category_id']);
        }
        if (! empty($validated['course_class_id'])) {
            $query->where('course_class_id', $validated['course_class_id']);
        }
        if (! empty($validated['course_type_id'])) {
            $query->where('course_type_id', $validated['course_type_id']);
        }
        if (isset($validated['is_free']) && $validated['is_free'] !== '') {
            $isFree = in_array($validated['is_free'], ['1', 'true', true, 1], true);
            $query->where('is_free', $isFree);
        }

        // Sorting
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
            default:
                $query->latest();
                break;
        }

        // Pagination
        $perPage = (int) ($validated['per_page'] ?? 15);
        $page    = (int) ($validated['page'] ?? 1);

        $courses = $query->paginate($perPage, ['*'], 'page', $page);

        // Transform
        $transformed = collect($courses->items())->map(fn ($c) => $this->transformCourse($c));

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

    /* ============================================================
       SHOW — Course Details
       ✅ FIX: alreadyOwned এখন student_id দিয়ে check
       ✅ NEW: can_purchase_self / can_purchase_gift flags
    ============================================================ */
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

        /* ============================================================
           ✅ FIX: alreadyOwned — student_id based (gift-ও ধরবে)
           কারণ: আমি gift-এও এই course পেয়ে থাকতে পারি
        ============================================================ */
        $alreadyOwned = false;
        if (Auth::check()) {
            $alreadyOwned = CourseOrder::where('student_id', Auth::id())
                ->where('course_id', $course->id)
                ->where('payment_status', 'paid')
                ->exists();
        }

        $isFree = (bool) $course->is_free;

        return response()->json([
            'success' => true,
            'message' => 'Course details retrieved successfully.',
            'data'    => [
                'course'          => $this->transformCourse($course),
                'already_owned'   => $alreadyOwned,
                'is_free'         => $isFree,

                /* ============================================================
                   🎯 PURCHASE OPTIONS (Mobile app needs these flags)
                   - can_purchase_self: নিজে কিনতে পারবে?
                   - can_purchase_gift: অন্যকে গিফট করতে পারবে?
                   - must_purchase_as_gift: শুধু gift করা যাবে?
                ============================================================ */
                'purchase_options' => [
                    'can_purchase_self'       => !$isFree && !$alreadyOwned,
                    'can_purchase_gift'       => !$isFree,
                    'must_purchase_as_gift'   => !$isFree && $alreadyOwned,
                ],

                'related_courses' => $relatedCourses,
            ],
        ], 200);
    }

     /* ============================================================
       ✅ NEW: STUDENTS — Gift purchase-এর জন্য student list
       Query: ?search= (name/username/phone/email দিয়ে)
       Query: ?per_page= (default 200, max 500)
    ============================================================ */
    public function students(Request $request): JsonResponse
    {
        $user = Auth::user();

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
            ], 401);
        }

        $validated = $request->validate([
            'search'   => 'nullable|string|max:255',
            'per_page' => 'nullable|integer|min:1|max:500',
        ]);

        $query = User::whereNotIn('id', [1, 2])
            ->where('id', '!=', $user->id)
            ->where('status', '1');

        // Search (name / username / phone / email)
        if (! empty($validated['search'])) {
            $search = $validated['search'];
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $perPage = (int) ($validated['per_page'] ?? 200);

        $students = $query->orderBy('full_name')
            ->limit($perPage)
            ->get(['id', 'full_name', 'username', 'email', 'phone'])
            ->map(function ($student) {
                return [
                    'id'        => $student->id,
                    'full_name' => $student->full_name,
                    'username'  => $student->username,
                    'email'     => $student->email,
                    'phone'     => $student->phone,

                    // Display label (mobile app dropdown-এ দেখানোর জন্য ready-made)
                    'display_label' => trim(
                        $student->full_name
                        . ($student->username ? ' — ' . $student->username : '')
                        . ($student->phone ? ' — ' . $student->phone : '')
                    ),
                ];
            });

        return response()->json([
            'success' => true,
            'message' => 'Students list retrieved successfully.',
            'data'    => $students,
            'meta'    => [
                'total' => $students->count(),
            ],
        ], 200);
    }

    /* ============================================================
       TRANSFORM — Course data for API
    ============================================================ */
    private function transformCourse(Course $course): array
    {
        return [
            'id'                => $course->id,
            'title'             => $course->title,
            'slug'              => $course->slug,
            'description'       => $course->description,

            'thumbnail'         => $course->thumbnail
                ? asset('storage/' . $course->thumbnail)
                : 'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?w=600&q=80',

            'teacher' => [
                'name'        => $course->teacher_name,
                'designation' => $course->teacher_designation,
                'photo'       => $course->teacher_photo
                    ? asset('storage/' . $course->teacher_photo)
                    : null,
                'experience'  => $course->teacher_experience,
            ],

            'price'             => (float) $course->price,
            'discount_price'    => $course->discount_price !== null
                ? (float) $course->discount_price
                : null,
            'final_price'       => (float) $course->final_price,
            'has_discount'      => $course->has_discount,
            'discount_percent'  => $course->discount_percent,
            'is_free'           => (bool) $course->is_free,

            'duration'          => $course->duration,
            'total_class'       => $course->total_class,
            'view_count'        => (int) $course->view_count,
            'is_featured'       => (bool) $course->is_featured,
            'status'            => $course->status,

            'course_type' => $course->courseType ? [
                'id'   => $course->courseType->id,
                'name' => $course->courseType->name,
            ] : null,

            'course_class' => $course->courseClass ? [
                'id'   => $course->courseClass->id,
                'name' => $course->courseClass->name,
            ] : null,

            'course_category' => $course->courseCategory ? [
                'id'   => $course->courseCategory->id,
                'name' => $course->courseCategory->name,
            ] : null,

            'created_at' => $course->created_at?->toDateTimeString(),
        ];
    }
}