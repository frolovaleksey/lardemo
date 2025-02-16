<?php

namespace App\Observers;

use App\Models\Book;
use App\Services\File\BookFile;

class BookObserver
{
    protected BookFile $fileHandler;

    public function __construct(BookFile $fileHandler)
    {
        $this->fileHandler = $fileHandler;
    }

    /**
     * Handle the Book "created" event.
     */
    public function created(Book $book): void
    {
        //
    }

    /**
     * Handle the Book "updated" event.
     */
    public function updated(Book $book): void
    {
        //
    }

    public function deleting(Book $book)
    {
        $this->fileHandler::delete($book);
        $book->authors()->detach();
    }

    /**
     * Handle the Book "deleted" event.
     */
    public function deleted(Book $book): void
    {

    }

    /**
     * Handle the Book "restored" event.
     */
    public function restored(Book $book): void
    {
        //
    }

    /**
     * Handle the Book "force deleted" event.
     */
    public function forceDeleted(Book $book): void
    {
        //
    }
}
