<?php

namespace App\Providers;

use App\Models\Book;
use App\Observers\BookObserver;
use App\Services\Author\AuthorRepositoryHandler;
use App\Services\Author\AuthorRepository;
use App\Services\Book\BookRepository;
use App\Services\Book\BookRepositoryHandler;
use App\Services\File\BookFile;
use App\Services\File\BookFileHandler;
use App\Services\File\File;
use App\Services\File\FileHandler;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(AuthorRepository::class, AuthorRepositoryHandler::class);
        $this->app->bind(BookRepository::class, BookRepositoryHandler::class);
        $this->app->bind(File::class, FileHandler::class);
        $this->app->bind(BookFile::class, BookFileHandler::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Book::observe(BookObserver::class);
    }
}
