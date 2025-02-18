<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookRequest;
use App\Services\Author\AuthorSelectOptionsHelper;
use App\Services\Book\BookRepository;
use App\Services\Book\BookRepositoryWithAuthorHandler;
use App\Services\Cart\Cart;
use Illuminate\Http\Request;
use Inertia\Inertia;


class BookController extends Controller
{
    protected BookRepository $bookRepository;
    protected AuthorSelectOptionsHelper $authorSelectOptionsHelper;
    public function __construct(BookRepositoryWithAuthorHandler $bookRepository, AuthorSelectOptionsHelper $authorSelectOptionsHelper)
    {
        $this->bookRepository = $bookRepository;
        $this->authorSelectOptionsHelper = $authorSelectOptionsHelper;
    }
    public function index(Request $request, Cart $cart)
    {
        $filters = $request->only(['id', 'title', 'last_name']);

        $items = $this->bookRepository
            ->setPagination(4)
            ->getFilteredPaginateItems($filters);

        return Inertia::render('Book/Index', [
            'items' => $items,
            'filters' => $filters,
            'canAddBook' => $this->userCan('Http_Controller_BookController_create'),
            'canEditBook' => $this->userCan('Http_Controller_BookController_edit'),
            'canDeleteBook' => $this->userCan('Http_Controller_BookController_destroy'),
            'cartCount' => $cart->getItemsCount(),
            'translations' => [
                'filter_id' => __('Filter by ID'),
                'filter_title' => __('Filter by Title'),
                'filter_last_name' => __('Filter by Author Last Name'),
                'book_list' => __('Book list'),
                'add_book' => __('Add Book'),
                'price' => __('Price'),
                'authors' => __('Authors'),
                'edit' => __('Edit'),
                'add_to_cart' => __('Add to Cart'),
                'cart' => __('Open Cart'),
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

        return Inertia::render('Book/Edit', [
            'book' => $this->bookRepository->findById($id),
            'authorOptions' => $this->authorSelectOptionsHelper::all(),
            'translations' => [
                'edit_author' => 'Edit Book',
            ],
        ]);
    }

    public function update(StoreBookRequest $request, $id)
    {
        $this->abortNotCan('Http_Controller_BookController_update');

        $this->bookRepository->update($id, $request->validated());

        return redirect()->route('book.index')->with('success', 'Book updated successfully!');
    }

    public function destroy($id)
    {
        $this->abortNotCan('Http_Controller_BookController_destroy');

        $this->bookRepository->delete($id);

        return redirect()->route('book.index')->with('success', __('Book deleted successfully!'));
    }
}
