<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Services\Book\BookRepository;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BookController extends Controller
{
    protected BookRepository $bookRepository;
    public function __construct(BookRepository $bookRepository)
    {
        $this->bookRepository = $bookRepository;
    }
    public function index(Request $request)
    {
        $filters = $request->only(['id', 'title']);

        $books = $this->bookRepository
            ->setPagination(5)
            ->getFilteredItems($filters);

        return Inertia::render('Book/Index', [
            'books' => $books,
            'filters' => $filters,
            'translations' => [
                'filter_id' => __('Filter by ID'),
                'filter_title' => __('Filter by Title'),
                'book_list' => __('Book list')
            ],
        ]);
    }
}
