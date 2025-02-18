<?php

namespace App\Providers;

use App\Models\Book;
use App\Observers\BookObserver;
use App\Services\Author\AuthorRepositoryHandler;
use App\Services\Author\AuthorRepository;
use App\Services\Book\BookRepository;
use App\Services\Book\BookRepositoryHandler;
use App\Services\Cart\Cart;
use App\Services\Cart\CartHandler;
use App\Services\File\BookFile;
use App\Services\File\BookFileHandler;
use App\Services\File\File;
use App\Services\File\FileHandler;
use App\Services\Order\OrderItemsHandler;
use App\Services\Order\OrderRepository;
use App\Services\Order\OrderRepositoryHandler;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(AuthorRepository::class, AuthorRepositoryHandler::class);
        $this->app->bind(BookRepository::class, BookRepositoryHandler::class);
        $this->app->bind(OrderRepository::class, OrderRepositoryHandler::class);
        $this->app->bind(OrderItemsHandler::class, OrderItemsHandler::class);
        $this->app->bind(File::class, FileHandler::class);
        $this->app->bind(BookFile::class, BookFileHandler::class);
        $this->app->bind(Cart::class, CartHandler::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Book::observe(BookObserver::class);
    }
}
