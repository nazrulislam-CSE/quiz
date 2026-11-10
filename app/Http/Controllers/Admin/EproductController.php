<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Eproduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EproductController extends Controller
{
    /* ============================================================
       INDEX — List + Add form (same page)
    ============================================================ */
    public function index()
    {
        $pageTitle = 'E-Product List';

        $products = Eproduct::latest()->paginate(15);

        /* ===== Stats ===== */
        $stats = [
            'total'     => Eproduct::count(),
            'active'    => Eproduct::where('status', 'active')->count(),
            'featured'  => Eproduct::where('is_featured', true)->count(),
            'out_stock' => Eproduct::where('quantity', '<=', 0)->count(),
        ];

        return view('admin.eproduct.index', compact('pageTitle', 'products', 'stats'));
    }

    /* ============================================================
       STORE
    ============================================================ */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_name'   => 'required|string|max:255',
            'product_photo'  => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'brand_name'     => 'nullable|string|max:255',
            'size'           => 'nullable|string|max:255',
            'color'          => 'nullable|string|max:255',
            'price'          => 'required|numeric|min:0',
            'discount'       => 'nullable|numeric|min:0|max:100',
            'customer_price' => 'required|numeric|min:0',
            'commission'     => 'required|numeric|min:0',
            'quantity'       => 'required|integer|min:0',
            'description'    => 'nullable|string',
            'warranty'       => 'nullable|string|max:255',
            'status'         => 'required|in:active,inactive',
            'is_featured'    => 'nullable|boolean',
        ], [
            'product_name.required'   => 'Product name is required.',
            'price.required'          => 'Price is required.',
            'customer_price.required' => 'Customer price is required.',
            'commission.required'     => 'Commission is required.',
            'quantity.required'       => 'Quantity is required.',
        ]);

        if ($request->hasFile('product_photo')) {
            $validated['product_photo'] = $request->file('product_photo')
                ->store('eproducts', 'public');
        }

        $validated['is_featured'] = $request->has('is_featured');
        $validated['discount']    = $validated['discount'] ?? 0;

        Eproduct::create($validated);

        return back()->with('success', '✅ Product added successfully.');
    }

    /* ============================================================
       UPDATE
    ============================================================ */
    public function update(Request $request, $id)
    {
        $product = Eproduct::findOrFail($id);

        $validated = $request->validate([
            'product_name'   => 'required|string|max:255',
            'product_photo'  => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'brand_name'     => 'nullable|string|max:255',
            'size'           => 'nullable|string|max:255',
            'color'          => 'nullable|string|max:255',
            'price'          => 'required|numeric|min:0',
            'discount'       => 'nullable|numeric|min:0|max:100',
            'customer_price' => 'required|numeric|min:0',
            'commission'     => 'required|numeric|min:0',
            'quantity'       => 'required|integer|min:0',
            'description'    => 'nullable|string',
            'warranty'       => 'nullable|string|max:255',
            'status'         => 'required|in:active,inactive',
            'is_featured'    => 'nullable|boolean',
        ]);

        /* ===== Replace photo if new uploaded ===== */
        if ($request->hasFile('product_photo')) {
            if ($product->product_photo
                && Storage::disk('public')->exists($product->product_photo)) {
                Storage::disk('public')->delete($product->product_photo);
            }
            $validated['product_photo'] = $request->file('product_photo')
                ->store('eproducts', 'public');
        }

        $validated['is_featured'] = $request->has('is_featured');
        $validated['discount']    = $validated['discount'] ?? 0;

        $product->update($validated);

        return back()->with('success', '✅ Product updated successfully.');
    }

    /* ============================================================
       DESTROY
    ============================================================ */
    public function destroy($id)
    {
        $product = Eproduct::findOrFail($id);

        /* ===== Delete photo ===== */
        if ($product->product_photo
            && Storage::disk('public')->exists($product->product_photo)) {
            Storage::disk('public')->delete($product->product_photo);
        }

        $product->delete();

        return back()->with('success', '🗑 Product deleted successfully.');
    }
}