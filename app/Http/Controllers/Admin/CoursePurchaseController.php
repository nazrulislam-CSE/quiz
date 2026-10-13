<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseOrder;
use App\Models\CourseType;
use App\Models\User;
use Illuminate\Http\Request;

class CoursePurchaseController extends Controller
{
    /* ============================================================
       INDEX — All course purchases (with filters)
    ============================================================ */
    public function index(Request $request)
    {
        $pageTitle = 'Course Purchase List';

        $query = CourseOrder::with(['user', 'course.courseType', 'course.courseClass', 'course.courseCategory']);

        // Filter: User
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter: Course
        if ($request->filled('course_id')) {
            $query->where('course_id', $request->course_id);
        }

        // Filter: Course Type
        if ($request->filled('course_type_id')) {
            $query->whereHas('course', function ($q) use ($request) {
                $q->where('course_type_id', $request->course_type_id);
            });
        }

        // Filter: Payment Status
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        // Filter: Payment Method
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        // Filter: Date range
        if ($request->filled('from_date')) {
            $query->whereDate('paid_at', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('paid_at', '<=', $request->to_date);
        }

        // Filter: Search (order no / txn id)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_no', 'like', "%{$search}%")
                  ->orWhere('merchant_transaction_id', 'like', "%{$search}%")
                  ->orWhere('eps_transaction_id', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%")
                         ->orWhere('phone', 'like', "%{$search}%");
                  });
            });
        }

        $orders = $query->latest()->get();

        // Filter dropdowns
        $courseTypes = CourseType::where('status', 'active')->orderBy('name')->get();
        $courses     = Course::orderBy('title')->get(['id', 'title']);
        $users       = User::orderBy('full_name')->get(['id', 'full_name']);

        // Summary stats
        $stats = [
            'total_orders'      => CourseOrder::count(),
            'paid_orders'       => CourseOrder::where('payment_status', 'paid')->count(),
            'total_revenue'     => CourseOrder::where('payment_status', 'paid')->sum('paid_amount'),
            'total_commission'  => \App\Models\Transaction::where('out', 'referral')
                                    ->where('purpose', 'like', '%Course%')
                                    ->sum('amount'),
        ];

        return view('admin.course_purchase.index', compact(
            'orders',
            'courseTypes',
            'courses',
            'users',
            'stats',
            'pageTitle'
        ));
    }

    /* ============================================================
       SHOW — Single Purchase Details
    ============================================================ */
    public function show(string $id)
    {
        $pageTitle = 'Purchase Details';

        $order = CourseOrder::with([
            'user',
            'course.courseType',
            'course.courseClass',
            'course.courseCategory',
        ])->findOrFail($id);

        // Get related transactions for this order (course purchase + commissions)
        $transactions = \App\Models\Transaction::where(function ($q) use ($order) {
            $q->where('from_id', $order->user_id)
              ->where('amount', $order->paid_amount)
              ->where('purpose', 'like', '%Course Purchase%');
        })->orWhere(function ($q) use ($order) {
            $q->where('from_id', $order->user_id)
              ->where('purpose', 'like', '%Course%')
              ->where('out', 'referral');
        })->get();

        return view('admin.course_purchase.show', compact(
            'order',
            'transactions',
            'pageTitle'
        ));
    }

    /* ============================================================
       DESTROY — Delete Purchase Record
    ============================================================ */
    public function destroy(string $id)
    {
        $order = CourseOrder::findOrFail($id);

        // Optional: also reverse commission? 
        // চাইলে এখানে commission reversal logic যোগ করা যাবে
        // Default: শুধু order delete

        $order->delete();

        flash()->addError('Purchase record deleted successfully.');
        return redirect()->route('admin.course.purchase.index');
    }

    /* ============================================================
    VOUCHER — Printable Invoice
    ============================================================ */
    public function voucher(string $id)
    {
        $order = CourseOrder::with([
            'user',
            'course.courseType',
            'course.courseClass',
            'course.courseCategory',
        ])->findOrFail($id);

        // Get related commission transactions for this order
        $transactions = \App\Models\Transaction::where('from_id', $order->user_id)
            ->whereIn('purpose', [
                'Course Purchase Cashback (20%) - ' . $order->course->title,
                'Direct Referral Course Commission (10%) - ' . $order->course->title,
                '1st Generation Course Commission (5%) - ' . $order->course->title,
            ])
            ->get();

        return view('admin.course_purchase.voucher', compact('order', 'transactions'));
    }
}