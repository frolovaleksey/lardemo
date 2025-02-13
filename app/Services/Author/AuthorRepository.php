<?php

namespace App\Services\Author;

use App\Models\Author;
use App\Services\Repository\Repository;
use Illuminate\Database\Eloquent\Builder;

class AuthorRepository extends Repository
{
    protected array $whereLike = [
        'first_name',
        'last_name'
    ];

    protected function baseQuery(): Builder
    {
        return Author::query();
    }

}
