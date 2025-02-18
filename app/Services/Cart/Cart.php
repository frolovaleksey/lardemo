<?php

namespace App\Services\Cart;

use Illuminate\Support\Facades\Session;

interface Cart
{
    public function isEmpty(): bool;
    public function getCart(): array;

    public function getItemsCount(): int;

    public function addItem(int $id): void;

    public function setItemQuantity(int $id, int $quantity): void;

    public function removeItem(int $id): void;

    public function clean(): void;

}
