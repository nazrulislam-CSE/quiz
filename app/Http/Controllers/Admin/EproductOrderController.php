<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Eproduct;
use App\Models\EproductOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EproductOrderController extends Controller
{
    /* ============================================================
       ORDER LIST
    ============================================================ */
    public function index(Request $request)
    {
        $pageTitle = 'E-Product Order List';

        $query = EproductOrder::with(['user', 'eproduct'])->latest();

        /* ===== Filters ===== */
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_no', 'like', "%{$search}%")
                  ->orWhere('merchant_transaction_id', 'like', "%{$search}%")
                  ->orWhere('buyer_name', 'like', "%{$search}%")
                  ->orWhere('buyer_email', 'like', "%{$search}%")
                  ->orWhere('buyer_phone', 'like', "%{$search}%")
                  ->orWhereHas('eproduct', fn($q) => $q->where('product_name', 'like', "%{$search}%"));
            });
        }

        if ($request->filled('status')) {
            $query->where('payment_status', $request->status);
        }

        if ($request->filled('delivery_type')) {
            $query->where('delivery_type', $request->delivery_type);
        }

        if ($request->filled('product_id')) {
            $query->where('eproduct_id', $request->product_id);
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
            'total_orders'   => EproductOrder::count(),
            'paid_orders'    => EproductOrder::where('payment_status', 'paid')->count(),
            'pending_orders' => EproductOrder::where('payment_status', 'pending')->count(),
            'failed_orders'  => EproductOrder::whereIn('payment_status', ['failed', 'cancelled'])->count(),
            'total_revenue'  => EproductOrder::where('payment_status', 'paid')->sum('paid_amount'),
            'today_revenue'  => EproductOrder::where('payment_status', 'paid')
                                            ->whereDate('paid_at', today())
                                            ->sum('paid_amount'),
            'total_shipping' => EproductOrder::where('payment_status', 'paid')->sum('shipping_charge'),
        ];

        $products = Eproduct::orderBy('product_name')->get(['id', 'product_name']);

        return view('admin.eproduct-order.index', compact('pageTitle', 'orders', 'stats', 'products'));
    }

    /* ============================================================
       ORDER DETAILS
    ============================================================ */
    public function show($id)
    {
        $pageTitle = 'Order Details';

        $order = EproductOrder::with(['user', 'eproduct'])->findOrFail($id);

        return view('admin.eproduct-order.show', compact('pageTitle', 'order'));
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

        $order = EproductOrder::findOrFail($id);

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
        $order = EproductOrder::findOrFail($id);

        /* Stock ফেরত দাও (শুধু paid হলে) */
        if ($order->payment_status === 'paid' && $order->eproduct) {
            $order->eproduct->increment('quantity');
        }

        $order->delete();

        return back()->with('success', 'Order deleted successfully.');
    }
}