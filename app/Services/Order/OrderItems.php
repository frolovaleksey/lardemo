<?php

namespace App\Services\Order;

use App\Models\Order;
use Illuminate\Database\Eloquent\Model;

interface OrderItems
{
    public function addCartItemsToOrder(Order $order): void;

    public function addOrderItemToOrder(Order $order, Model $model): Model;
}
