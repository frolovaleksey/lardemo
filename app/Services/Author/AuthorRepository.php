<?php

namespace App\Services\Author;

use App\Models\Author;

interface AuthorRepository
{
    public function findByFirstLastName(string $firstName, string $lastName): ?Author;
}
