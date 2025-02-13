<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAuthorRequest;
use App\Models\Author;
use App\Services\Author\AuthorRepository;
use Inertia\Inertia;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    protected AuthorRepository $authorRepository;

    public function __construct(AuthorRepository $authorRepository)
    {
        $this->authorRepository = $authorRepository;
    }
    public function index(Request $request)
    {
        $filters = $request->only(['first_name', 'last_name']);

        $authors = $this->authorRepository
            ->setPagination(5)
            ->getFilteredItems($filters);

        return Inertia::render('Author/Index', [
            'authors' => $authors,
            'filters' => $filters,
            'canAddAuthor' => $this->userCan('Http_Controller_AuthorController_create'),
            'translations' => [
                'filter_id' => __('ID'),
                'first_name' => __('First name'),
                'last_name' => __('Last Name'),
                'title' => __('Author list')
            ],
        ]);
    }

    public function create()
    {
        $this->abortNotCan('Http_Controller_AuthorController_create');

        return Inertia::render('Author/Create');
    }

    public function store(StoreAuthorRequest $request)
    {
        $this->abortNotCan('Http_Controller_AuthorController_store');

        Author::create($request->validated());

        return redirect()->route('author.index')->with('success', __('Author created successfully!'));
    }

    public function edit(Author $author)
    {
        $this->abortNotCan('Http_Controller_AuthorController_edit');

        return Inertia('Author/Edit', [
            'author' => $author,
            'translations' => [
                'edit_author' => 'Edit Author',
            ],
        ]);
    }

    public function update(StoreAuthorRequest $request, Author $author)
    {
        $this->abortNotCan('Http_Controller_AuthorController_update');

        $author->update($request->all());

        return redirect()->route('author.index')->with('success', 'Author updated successfully!');
    }

    public function destroy($id)
    {
        $this->abortNotCan('Http_Controller_AuthorController_destroy');

        $author = Author::findOrFail($id);
        $author->delete();

        return redirect()->route('author.index')->with('success', __('Author deleted successfully!'));
    }
}
