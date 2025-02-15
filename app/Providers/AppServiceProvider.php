<?php

namespace App\Providers;

use App\Models\Book;
use App\Observers\BookObserver;
use App\Services\Author\AuthorRepositoryHandler;
use App\Services\Author\AuthorRepository;
use App\Services\Book\BookRepository;
use App\Services\Book\BookRepositoryHandler;
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
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Book::observe(BookObserver::class);
    }
}
