<?php

namespace App\Services\Author;

use App\Models\Author;
use App\Services\Repository\Repository;

class AuthorRepositoryHandler extends Repository implements AuthorRepository
{
    protected array $whereLike = [
        'first_name',
        'last_name',
    ];

    protected function initModel(): void
    {
        $this->model = Author::class;
    }

    public function findByFirstLastName(string $firstName, string $lastName): ?Author
    {
        return Author::where('first_name', $firstName)->where('last_name', $lastName)->first();
    }
}
