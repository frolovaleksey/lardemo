<?php

namespace App\Http\Controllers;


use App\Services\Cart\Cart;
use App\Services\Cart\CartCheckoutDecorator;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CartController extends Controller
{
    public function checkout(CartCheckoutDecorator $cartCheckoutDecorator)
    {
        $books = $cartCheckoutDecorator::decorate(app(Cart::class));

        $totalPrice = $books->sum('total');

        return Inertia::render('Checkout/Index', [
            'cart' => $books,
            'totalPrice' => $totalPrice,
        ]);
    }

    public function addToCart(Request $request, Cart $cart)
    {
        $cart->addItem($request->id);

        return back();
    }

    public function getCartCount(Cart $cart)
    {
        return response()->json(['cartCount' => $cart->getItemsCount()]);
    }

    public function update(Request $request, Cart $cart)
    {
        $cart->setItemQuantity($request->id, $request->quantity);

        return back();
    }

    public function remove(Request $request, Cart $cart)
    {
        $cart->removeItem($request->id);

        return back();
    }
}
