<?php

namespace App\Services\Book;

use App\Models\Author;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class BookRepositoryWithAuthorHandler extends BookRepositoryHandler
{
    protected array $whereLike = [
        'title',
        'last_name'
    ];
    protected function baseQuery(): Builder
    {
        return $this->setWith( $this->model::query()
                ->leftJoin('book_author', 'books.id', '=', 'book_author.book_id')
                ->leftJoin('authors', 'book_author.author_id', '=', 'authors.id')

                ->select(
                    'books.id',
                    'books.title',
                    'books.price',
                    'books.image_url'
                )
                ->groupBy('id')
            )
            ;
    }
}
