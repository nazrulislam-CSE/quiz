<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Ebook;
use Illuminate\Http\Request;

class BookShopController extends Controller
{
    public function index()
    {
        $pageTitle = 'আমাদের বুক শপ';

        $books = Ebook::where('status', 'active')
            ->latest()
            ->paginate(12);

        return view('user.eshop.book.index', compact('pageTitle', 'books'));
    }

    public function show($slug)
    {
        $pageTitle = 'বইয়ের বিবরণ';

        $book = Ebook::where('slug', $slug)
            ->where('status', 'active')
            ->firstOrFail();

        // ভিউ কাউন্ট বাড়ান
        $book->increment('view_count');

        // একই লেখকের অন্যান্য বই (related)
        $relatedBooks = Ebook::where('status', 'active')
            ->where('id', '!=', $book->id)
            ->where(function ($q) use ($book) {
                $q->where('author_name', $book->author_name);
            })
            ->latest()
            ->take(4)
            ->get();

        return view('user.eshop.book.show', compact('pageTitle', 'book', 'relatedBooks'));
    }
}