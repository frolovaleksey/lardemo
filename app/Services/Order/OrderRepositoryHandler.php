<?php

namespace App\Services\Order;

use App\Models\Order;
use App\Services\Cart\Cart;
use App\Services\Repository\Repository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class OrderRepositoryHandler extends Repository implements OrderRepository
{
    protected function initModel(): void
    {
        $this->model = Order::class;
    }

    public function create(array $data): Model
    {
        $data['date'] = Carbon::now();

        $model = parent::create($data);

        app(OrderItemsHandler::class)
            ->addCartItemsToOrder($model);

        app(Cart::class)
            ->clean();

        return $model;
    }
}
