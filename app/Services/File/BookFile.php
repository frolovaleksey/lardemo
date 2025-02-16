<?php

namespace App\Services\File;

use App\Models\Book;
use Illuminate\Http\UploadedFile;

interface BookFile
{
    public static function delete(Book $book): bool;

    public static function storeUploadedFile(UploadedFile $data): string;

    public static function refreshUploadedFile(Book $book, UploadedFile $data): string;
}
