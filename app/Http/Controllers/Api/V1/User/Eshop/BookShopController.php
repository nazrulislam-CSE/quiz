<?php

namespace App\Http\Controllers\Api\V1\User\Eshop;

use App\Http\Controllers\Controller;
use App\Models\Ebook;
use Illuminate\Http\Request;

class BookShopController extends Controller
{
    /**
     * Get all active books
     */
    public function index()
    {
        $books = Ebook::where('status', 'active')
            ->latest()
            ->paginate(12);

        return response()->json([
            'success' => true,
            'message' => 'বইয়ের তালিকা পাওয়া গেছে।',
            'data' => $books->items(),
            'pagination' => [
                'current_page' => $books->currentPage(),
                'per_page' => $books->perPage(),
                'total' => $books->total(),
                'last_page' => $books->lastPage(),
                'next_page_url' => $books->nextPageUrl(),
                'prev_page_url' => $books->previousPageUrl(),
            ],
        ], 200);
    }

    /**
     * Get book details by slug
     */
    public function show(string $slug)
    {
        $book = Ebook::where('slug', $slug)
            ->where('status', 'active')
            ->first();

        if (!$book) {
            return response()->json([
                'success' => false,
                'message' => 'বইটি খুঁজে পাওয়া যায়নি।',
                'data' => null,
            ], 404);
        }

        // Increase view count
        $book->increment('view_count');

        // Refresh view count in response
        $book->refresh();

        // Related books by the same author
        $relatedBooks = Ebook::where('status', 'active')
            ->where('id', '!=', $book->id)
            ->where('author_name', $book->author_name)
            ->latest()
            ->take(4)
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'বইয়ের বিবরণ পাওয়া গেছে।',
            'data' => [
                'book' => $book,
                'related_books' => $relatedBooks,
            ],
        ], 200);
    }

    /**
     * Get book orders
     *
     * TODO: Implement using your actual order model.
     */
    public function orders()
    {
        return response()->json([
            'success' => true,
            'message' => 'Book orders API প্রস্তুত, তবে অর্ডার মডেল যুক্ত করা বাকি।',
            'data' => [],
        ], 200);
    }
}