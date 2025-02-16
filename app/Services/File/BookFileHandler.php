<?php

namespace App\Services\File;

use App\Models\Book;
use Illuminate\Http\UploadedFile;

class BookFileHandler implements BookFile
{
    public static function delete(Book $book): bool
    {
        $file = app(File::class);
        return $file::delete($book->image_url);
    }

    public static function storeUploadedFile(UploadedFile $data): string
    {
        $file = app(File::class);
        return $file::storeUploadedFile($data, 'book_images');
    }

    public static function refreshUploadedFile(Book $book, UploadedFile $data): string
    {
        self::delete($book);

        $file = app(File::class);
        return $file::storeUploadedFile($data, 'book_images');
    }
}
