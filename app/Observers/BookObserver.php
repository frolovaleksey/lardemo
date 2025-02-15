<?php

namespace App\Observers;

use App\Models\Book;
use Illuminate\Support\Facades\Storage;

class BookObserver
{
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
        Storage::disk('public')->delete(str_replace('storage/', '', $book->image_url));
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
