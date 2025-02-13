<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::query();

        // Фильтр по ID
        if ($request->filled('id')) {
            $query->where('id', $request->id);
        }

        // Фильтр по title (LIKE %search%)
        if ($request->filled('title')) {
            $query->where('title', 'like', '%' . $request->title . '%');
        }

        $books = $query->paginate(5)->withQueryString();

        return Inertia::render('Book/Index', [
            'books' => $books,
            'filters' => $request->only(['id', 'title']),
            'translations' => [
                'filter_id' => __('Filter by ID'),
                'filter_title' => __('Filter by Title'),
                'book_list' => __('Book list')
            ],
        ]);
    }
}
