<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ebook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class EbookController extends Controller
{
    /* ============================================================
       INDEX — E-Book List + Filters + Stats
    ============================================================ */
    public function index(Request $request)
    {
        $pageTitle = 'E Book List';

        $query = Ebook::query();

        // Filter: Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter: Featured
        if ($request->filled('is_featured')) {
            $query->where('is_featured', $request->boolean('is_featured'));
        }

        // Filter: Stock
        if ($request->filled('stock')) {
            if ($request->stock === 'in') {
                $query->where('quantity', '>', 0);
            } elseif ($request->stock === 'out') {
                $query->where('quantity', '<=', 0);
            }
        }

        // Search (book name + author + description)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('book_name', 'like', "%{$search}%")
                  ->orWhere('author_name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $ebooks = $query->latest()->get();

        // Summary stats (always for full dataset, not filtered)
        $stats = [
            'total_books'       => Ebook::count(),
            'active_books'      => Ebook::where('status', 'active')->count(),
            'out_of_stock'      => Ebook::where('quantity', '<=', 0)->count(),
            'total_stock_value' => Ebook::sum(DB::raw('sell_price * quantity')),
        ];

        return view('admin.ebook.index', compact('ebooks', 'stats', 'pageTitle'));
    }

    /* ============================================================
       STORE — Create E-Book (with image upload)
    ============================================================ */
    public function store(Request $request)
    {
        $request->validate([
            'book_name'   => 'required|string|max:255|unique:ebooks,book_name',
            'author_name' => 'required|string|max:255',
            'book_photo'  => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'total_page'  => 'nullable|integer|min:1',
            'price'       => 'required|numeric|min:0',
            'discount'    => 'nullable|numeric|min:0|max:100',
            'sell_price'  => 'nullable|numeric|min:0',
            'commission'  => 'nullable|numeric|min:0|max:100',
            'quantity'    => 'nullable|integer|min:0',
            'description' => 'nullable|string',
            'status'      => 'nullable|in:active,inactive',
            'is_featured' => 'nullable|boolean',
        ], [
            'book_name.unique' => 'এই নামে ইতিমধ্যে একটি বই আছে।',
            'book_photo.max'   => 'ছবির সাইজ 2MB এর কম হতে হবে।',
            'book_photo.mimes' => 'শুধু jpg, jpeg, png, webp ফরম্যাট সাপোর্টেড।',
            'discount.max'     => 'ডিসকাউন্ট 100% এর বেশি হতে পারবে না।',
            'commission.max'   => 'কমিশন 100% এর বেশি হতে পারবে না।',
        ]);

        try {
            // Handle image upload
            $photoPath = null;
            if ($request->hasFile('book_photo')) {
                $photoPath = $request->file('book_photo')->store('ebooks', 'public');
            }

            // Calculate sell_price
            $price     = (float) $request->price;
            $discount  = (float) ($request->discount ?? 0);
            $sellPrice = (float) ($request->sell_price ?? 0);

            if ($sellPrice <= 0 && $price > 0) {
                $sellPrice = $discount > 0
                    ? round($price - ($price * $discount / 100), 2)
                    : $price;
            }

            Ebook::create([
                'book_name'   => $request->book_name,
                'author_name' => $request->author_name,
                'book_photo'  => $photoPath,
                'total_page'  => $request->total_page,
                'price'       => $price,
                'discount'    => $discount,
                'sell_price'  => $sellPrice,
                'commission'  => $request->commission ?? 0,
                'quantity'    => $request->quantity ?? 0,
                'description' => $request->description,
                'status'      => $request->status ?? 'active',
                'is_featured' => $request->boolean('is_featured'),
            ]);

            flash()->addSuccess('E-Book Created Successfully.');
            return redirect()->route('admin.ebook.index');

        } catch (\Exception $e) {
            Log::error('Ebook create failed', [
                'message' => $e->getMessage(),
                'input'   => $request->except('book_photo'),
            ]);

            // Delete uploaded image on failure
            if (isset($photoPath) && $photoPath && Storage::disk('public')->exists($photoPath)) {
                Storage::disk('public')->delete($photoPath);
            }

            flash()->addError('Failed to create E-Book: ' . $e->getMessage());
            return back()->withInput();
        }
    }

    /* ============================================================
       UPDATE — Update E-Book (with image replace)
    ============================================================ */
    public function update(Request $request, string $id)
    {
        $ebook = Ebook::findOrFail($id);

        $request->validate([
            'book_name'   => 'required|string|max:255|unique:ebooks,book_name,' . $id,
            'author_name' => 'required|string|max:255',
            'book_photo'  => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'total_page'  => 'nullable|integer|min:1',
            'price'       => 'required|numeric|min:0',
            'discount'    => 'nullable|numeric|min:0|max:100',
            'sell_price'  => 'nullable|numeric|min:0',
            'commission'  => 'nullable|numeric|min:0|max:100',
            'quantity'    => 'nullable|integer|min:0',
            'description' => 'nullable|string',
            'status'      => 'nullable|in:active,inactive',
            'is_featured' => 'nullable|boolean',
        ], [
            'book_name.unique' => 'এই নামে ইতিমধ্যে একটি বই আছে।',
            'book_photo.max'   => 'ছবির সাইজ 2MB এর কম হতে হবে।',
            'book_photo.mimes' => 'শুধু jpg, jpeg, png, webp ফরম্যাট সাপোর্টেড।',
        ]);

        try {
            // Handle image replacement
            $photoPath = $ebook->book_photo;
            if ($request->hasFile('book_photo')) {
                // Delete old image
                if ($ebook->book_photo && Storage::disk('public')->exists($ebook->book_photo)) {
                    Storage::disk('public')->delete($ebook->book_photo);
                }
                $photoPath = $request->file('book_photo')->store('ebooks', 'public');
            }

            // Calculate sell_price
            $price     = (float) $request->price;
            $discount  = (float) ($request->discount ?? 0);
            $sellPrice = (float) ($request->sell_price ?? 0);

            if ($sellPrice <= 0 && $price > 0) {
                $sellPrice = $discount > 0
                    ? round($price - ($price * $discount / 100), 2)
                    : $price;
            }

            $ebook->update([
                'book_name'   => $request->book_name,
                'author_name' => $request->author_name,
                'book_photo'  => $photoPath,
                'total_page'  => $request->total_page,
                'price'       => $price,
                'discount'    => $discount,
                'sell_price'  => $sellPrice,
                'commission'  => $request->commission ?? 0,
                'quantity'    => $request->quantity ?? 0,
                'description' => $request->description,
                'status'      => $request->status ?? 'active',
                'is_featured' => $request->boolean('is_featured'),
            ]);

            flash()->addSuccess('E-Book Updated Successfully.');
            return redirect()->route('admin.ebook.index');

        } catch (\Exception $e) {
            Log::error('Ebook update failed', [
                'message'  => $e->getMessage(),
                'ebook_id' => $id,
                'input'    => $request->except('book_photo'),
            ]);

            flash()->addError('Failed to update E-Book: ' . $e->getMessage());
            return back()->withInput();
        }
    }

    /* ============================================================
       DESTROY — Delete E-Book (with image cleanup)
    ============================================================ */
    public function destroy(string $id)
    {
        try {
            $ebook = Ebook::findOrFail($id);

            // Delete image file
            if ($ebook->book_photo && Storage::disk('public')->exists($ebook->book_photo)) {
                Storage::disk('public')->delete($ebook->book_photo);
            }

            $ebook->delete();

            flash()->addError('E-Book Deleted Successfully.');

        } catch (\Exception $e) {
            Log::error('Ebook delete failed', [
                'message'  => $e->getMessage(),
                'ebook_id' => $id,
            ]);

            flash()->addError('Failed to delete E-Book: ' . $e->getMessage());
        }

        return redirect()->route('admin.ebook.index');
    }
}