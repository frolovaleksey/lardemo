<?php

namespace App\Services\Cart;

use Illuminate\Support\Facades\Session;

class CartHandler implements Cart
{
    protected $sessionKey='cart';

    protected function getKey(): string
    {
        return $this->sessionKey;
    }

    public function isEmpty(): bool
    {
        return (empty($this->getCart()));
    }
    public function getCart(): array
    {
        return Session::get($this->getKey(), []);
    }

    public function getItemsCount(): int
    {
        return array_sum( $this->getCart() );
    }

    public function addItem(int $id): void
    {
        $cart = $this->getCart();

        if (!isset($cart[$id])) {
            $cart[$id] = 1;
        } else {
            $cart[$id]++;
        }

        $this->updateCart($cart);
    }

    public function setItemQuantity(int $id, int $quantity): void
    {
        $cart = $this->getCart();

        $cart[$id] = $quantity;

        $this->updateCart($cart);
    }

    public function removeItem(int $id): void
    {
        $cart = $this->getCart();
        unset($cart[$id]);
        $this->updateCart($cart);
    }

    public function clean(): void
    {
        $this->updateCart([]);
    }

    protected function updateCart(array $cart): void
    {
        session([$this->getKey() => $cart]);
    }
}
