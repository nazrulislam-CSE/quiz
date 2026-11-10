<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Eproduct;

class EproductShopController extends Controller
{
    /* ============================================================
       INDEX — All active products
    ============================================================ */
    public function index()
    {
        $pageTitle = 'আমাদের প্রোডাক্ট শপ';

        $products = Eproduct::where('status', 'active')
            ->latest()
            ->paginate(12);

        return view('user.eshop.eproduct.index', compact('pageTitle', 'products'));
    }

    /* ============================================================
       SHOW — Product details by slug
    ============================================================ */
    public function show($slug)
    {
        $pageTitle = 'প্রোডাক্টের বিবরণ';

        $product = Eproduct::where('slug', $slug)
            ->where('status', 'active')
            ->firstOrFail();

        // View count বাড়ান
        $product->increment('view_count');

        // Same brand এর অন্যান্য প্রোডাক্ট (related)
        $relatedProducts = Eproduct::where('status', 'active')
            ->where('id', '!=', $product->id)
            ->where(function ($q) use ($product) {
                if ($product->brand_name) {
                    $q->where('brand_name', $product->brand_name);
                }
            })
            ->latest()
            ->take(4)
            ->get();

        return view('user.eshop.eproduct.show',
            compact('pageTitle', 'product', 'relatedProducts'));
    }
}