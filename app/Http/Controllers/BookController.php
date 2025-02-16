<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookRequest;
use App\Models\Book;
use App\Services\Author\AuthorSelectOptionsHelper;
use App\Services\Book\BookRepository;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    protected BookRepository $bookRepository;
    protected AuthorSelectOptionsHelper $authorSelectOptionsHelper;
    public function __construct(BookRepository $bookRepository, AuthorSelectOptionsHelper $authorSelectOptionsHelper)
    {
        $this->bookRepository = $bookRepository;
        $this->authorSelectOptionsHelper = $authorSelectOptionsHelper;
    }
    public function index(Request $request)
    {
        $filters = $request->only(['id', 'title']);

        $books = $this->bookRepository
            ->setPagination(5)
            ->getFilteredPaginateItems($filters);

        return Inertia::render('Book/Index', [
            'books' => $books,
            'filters' => $filters,
            'canAddBook' => $this->userCan('Http_Controller_BookController_create'),
            'translations' => [
                'filter_id' => __('Filter by ID'),
                'filter_title' => __('Filter by Title'),
                'book_list' => __('Book list'),
                'add_book' => __('Add Book'),
                'price' => __('Price'),
                'authors' => __('Authors'),
                'edit' => __('Edit'),
                'buy' => __('Add to Cart'),
            ],
        ]);
    }

    public function create()
    {
        $this->abortNotCan('Http_Controller_BookController_create');
        return Inertia::render('Book/Create', [
            'authorOptions' => $this->authorSelectOptionsHelper::all(),
        ]);
    }

    public function store(StoreBookRequest $request)
    {
        $this->abortNotCan('Http_Controller_BookController_store');

        $this->bookRepository->create($request->validated());

        return redirect()->route('book.index')->with('success', __('Book created successfully!'));
    }


    public function edit($id)
    {
        $this->abortNotCan('Http_Controller_BookController_edit');

        return Inertia('Book/Edit', [
            'book' => $this->bookRepository->findById($id),
            'authorOptions' => $this->authorSelectOptionsHelper::all(),
            'translations' => [
                'edit_author' => 'Edit Book',
            ],
        ]);
    }

    public function update(StoreBookRequest $request, $id)
    {
        dd($request);
        $this->abortNotCan('Http_Controller_BookController_update');

        $this->bookRepository->update($id, $request->all());

        return redirect()->route('book.index')->with('success', 'Book updated successfully!');
    }

    public function destroy($id)
    {
        $this->abortNotCan('Http_Controller_BookController_destroy');

        $this->bookRepository->delete($id);

        return redirect()->route('book.index')->with('success', __('Book deleted successfully!'));
    }
}
