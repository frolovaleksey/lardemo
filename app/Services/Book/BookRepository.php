<?php

namespace App\Services\Book;

use App\Models\Book;
use App\Services\Repository\Repository;
use Illuminate\Database\Eloquent\Builder;

class BookRepository extends Repository
{

    protected array $whereLike = [
        'title'
    ];

    protected array $whereStrict = [
        'id'
    ];
    protected function baseQuery(): Builder
    {
        return Book::query();
    }
}
