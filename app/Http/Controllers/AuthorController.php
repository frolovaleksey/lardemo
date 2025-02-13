<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAuthorRequest;
use App\Models\Author;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    public function index(Request $request)
    {
        $query = Author::query();

        if ($request->filled('id')) {
            $query->where('id', $request->id);
        }

        if ($request->filled('first_name')) {
            $query->where('first_name', 'like', '%' . $request->first_name . '%');
        }

        if ($request->filled('last_name')) {
            $query->where('last_name', 'like', '%' . $request->last_name . '%');
        }

        $authors = $query->paginate(5)->withQueryString();

        return Inertia::render('Author/Index', [
            'authors' => $authors,
            'filters' => $request->only(['id', 'first_name', 'last_name']),
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
        return Inertia::render('Author/Create');
    }

    public function store(StoreAuthorRequest $request)
    {
        Author::create($request->validated());

        return redirect()->route('author.index')->with('success', __('Author created successfully!'));
        //return Inertia::location(route('author.index'))->with('success', __('Author created successfully!'));
        //return redirect()->route('author.index')->with('flash', ['success' => __('Author created successfully!')]);
    }
}
