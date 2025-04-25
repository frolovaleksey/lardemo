<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Services\Cart\Cart;
use App\Services\Order\OrderRepository;
use App\Services\Order\OrderRepositoryHandler;
use Inertia\Inertia;

class OrderController extends Controller
{
    protected OrderRepository $orderRepository;

    protected Cart $cart;

    public function __construct(OrderRepositoryHandler $orderRepository, Cart $cart)
    {
        $this->orderRepository = $orderRepository;
        $this->cart = $cart;
    }

    public function create()
    {
        if ($this->cart->isEmpty()) {
            return redirect()->route('checkout')->with('success', __('Cart is empty'));
        }

        return Inertia::render('Order/Create');
    }

    public function store(StoreOrderRequest $request)
    {
        $this->orderRepository->create($request->validated());

        return redirect()->route('book.index')->with('success', __('Order created successfully!'));
    }
}
