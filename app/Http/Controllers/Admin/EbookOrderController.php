<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BookOrder;
use App\Models\Ebook;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EbookOrderController extends Controller
{
    /* ============================================================
       ORDER LIST
    ============================================================ */
    public function index(Request $request)
    {
        $pageTitle = 'E-Book Order List';

        $query = BookOrder::with(['user', 'ebook'])->latest();

        /* ===== Filters ===== */
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_no', 'like', "%{$search}%")
                  ->orWhere('merchant_transaction_id', 'like', "%{$search}%")
                  ->orWhere('buyer_name', 'like', "%{$search}%")
                  ->orWhere('buyer_email', 'like', "%{$search}%")
                  ->orWhere('buyer_phone', 'like', "%{$search}%")
                  ->orWhereHas('ebook', fn($q) => $q->where('book_name', 'like', "%{$search}%"));
            });
        }

        if ($request->filled('status')) {
            $query->where('payment_status', $request->status);
        }

        if ($request->filled('book_id')) {
            $query->where('ebook_id', $request->book_id);
        }

        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $orders = $query->paginate(20)->withQueryString();

        /* ===== Stats ===== */
        $stats = [
            'total_orders'   => BookOrder::count(),
            'paid_orders'    => BookOrder::where('payment_status', 'paid')->count(),
            'pending_orders' => BookOrder::where('payment_status', 'pending')->count(),
            'failed_orders'  => BookOrder::whereIn('payment_status', ['failed', 'cancelled'])->count(),
            'total_revenue'  => BookOrder::where('payment_status', 'paid')->sum('paid_amount'),
            'today_revenue'  => BookOrder::where('payment_status', 'paid')
                                        ->whereDate('paid_at', today())
                                        ->sum('paid_amount'),
        ];

        $books = Ebook::orderBy('book_name')->get(['id', 'book_name']);

        return view('admin.ebook-order.index', compact('pageTitle', 'orders', 'stats', 'books'));
    }

    /* ============================================================
       ORDER DETAILS
    ============================================================ */
    public function show($id)
    {
        $pageTitle = 'Order Details';

        $order = BookOrder::with(['user', 'ebook'])->findOrFail($id);

        return view('admin.ebook-order.show', compact('pageTitle', 'order'));
    }

    /* ============================================================
       UPDATE STATUS
    ============================================================ */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'payment_status' => 'required|in:pending,paid,failed,cancelled',
            'status'         => 'required|in:pending,completed,cancelled',
        ]);

        $order = BookOrder::findOrFail($id);

        $order->update([
            'payment_status' => $request->payment_status,
            'status'         => $request->status,
            'paid_at'        => $request->payment_status === 'paid'
                                    ? ($order->paid_at ?? now())
                                    : $order->paid_at,
        ]);

        return back()->with('success', 'Order status updated successfully.');
    }

    /* ============================================================
       DELETE ORDER
    ============================================================ */
    public function destroy($id)
    {
        $order = BookOrder::findOrFail($id);

        // Stock ফেরত দাও
        if ($order->payment_status === 'paid' && $order->ebook) {
            $order->ebook->increment('quantity');
        }

        $order->delete();

        return back()->with('success', 'Order deleted successfully.');
    }

    /* ============================================================
       EXPORT CSV
    ============================================================ */
    public function export(Request $request)
    {
        $query = BookOrder::with(['user', 'ebook'])->latest();

        if ($request->filled('status')) {
            $query->where('payment_status', $request->status);
        }

        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $orders = $query->get();

        $filename = 'ebook-orders-' . now()->format('Y-m-d-His') . '.csv';
        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($orders) {
            $file = fopen('php://output', 'w');
            fputcsv($file, [
                'Order No', 'Book Name', 'Buyer Name', 'Buyer Email',
                'Buyer Phone', 'Amount', 'Payment Method', 'Txn ID',
                'Payment Status', 'Order Status', 'Paid At', 'Created At',
            ]);

            foreach ($orders as $order) {
                fputcsv($file, [
                    $order->order_no,
                    $order->ebook->book_name ?? '—',
                    $order->buyer_name,
                    $order->buyer_email,
                    $order->buyer_phone,
                    $order->paid_amount,
                    $order->payment_method,
                    $order->merchant_transaction_id,
                    $order->payment_status,
                    $order->status,
                    $order->paid_at?->format('Y-m-d H:i:s'),
                    $order->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}