<?php

namespace App\Services\Shop;

interface OrderableItem
{
    public function getAmount(): int;
    public function getId(): int;
    public function getType(): string;
}
