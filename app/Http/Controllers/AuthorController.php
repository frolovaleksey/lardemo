<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAuthorRequest;
use App\Services\Author\AuthorRepository;
use Illuminate\Http\Request;
use Inertia\Inertia;

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

        $items = $this->authorRepository
            ->setPagination(2)
            ->getFilteredPaginateItems($filters);

        return Inertia::render('Author/Index', [
            'items' => $items,
            'filters' => $filters,
            'canAddAuthor' => $this->userCan('Http_Controller_AuthorController_create'),
            'translations' => [
                'filter_id' => __('ID'),
                'first_name' => __('First name'),
                'last_name' => __('Last Name'),
                'title' => __('Author list'),
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

        $this->authorRepository->create($request->validated());

        return redirect()->route('author.index')->with('success', __('Author created successfully!'));
    }

    public function edit($id)
    {
        $this->abortNotCan('Http_Controller_AuthorController_edit');

        return Inertia('Author/Edit', [
            'author' => $this->authorRepository->findById($id),
            'translations' => [
                'edit_author' => 'Edit Author',
            ],
        ]);
    }

    public function update(StoreAuthorRequest $request, $id)
    {
        $this->abortNotCan('Http_Controller_AuthorController_update');

        $this->authorRepository->update($id, $request->all());

        return redirect()->route('author.index')->with('success', 'Author updated successfully!');
    }

    public function destroy($id)
    {
        $this->abortNotCan('Http_Controller_AuthorController_destroy');

        $this->authorRepository->delete($id);

        return redirect()->route('author.index')->with('success', __('Author deleted successfully!'));
    }
}
