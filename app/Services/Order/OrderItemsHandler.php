<?php

namespace App\Services\Order;

use App\Models\Book;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\Cart\Cart;
use Illuminate\Database\Eloquent\Model;

class OrderItemsHandler implements OrderItems
{
    protected $cartHandler;

    public function __construct(Cart $cartHandler)
    {
        $this->cartHandler = $cartHandler;
    }

    public function addCartItemsToOrder(Order $order): void
    {
        $cart = $this->cartHandler->getCart();
        foreach ($cart as $itemId => $itemCount) {
            for ($i = 0; $i < $itemCount; $i++) {
                $this->addOrderItemToOrder($order, Book::find($itemId));
            }
        }
    }

    public function addOrderItemToOrder(Order $order, Model $model): Model
    {
        $item = new OrderItem;
        $item->order_id = $order->id;
        $item->amount = $model->getAmount();
        $item->orderable_id = $model->getId();
        $item->orderable_type = $model->getType();
        $item->save();

        return $item;
    }
}
