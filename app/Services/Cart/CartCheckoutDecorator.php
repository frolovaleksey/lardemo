<?php

namespace App\Services\Cart;

use App\Models\Book;
use Illuminate\Support\Collection;

class CartCheckoutDecorator
{
    public static function decorate(Cart $cartHandler): Collection
    {
        $cart = $cartHandler->getCart();
        return Book::whereIn('id', array_keys($cart))->get()->map(function ($book) use ($cart) {
            return [
                'id' => $book->id,
                'title' => $book->title,
                'price' => $book->price,
                'image_url' => $book->image_url,
                'quantity' => $cart[$book->id] ?? 1,
                'total' => $book->price * ($cart[$book->id] ?? 1),
            ];
        });
    }
}
