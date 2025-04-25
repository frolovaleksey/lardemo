<?php

namespace Tests\Feature;

use App\Services\Cart\Cart;
use App\Services\Cart\CartCheckoutDecorator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

/*
 * php artisan test --filter CartControllerTest
 */

class CartControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_checkout_returns_correct_data()
    {
        $cartMock = $this->createMock(Cart::class);
        $this->app->instance(Cart::class, $cartMock);

        $this->app->bind(CartCheckoutDecorator::class, function () {
            return new class extends CartCheckoutDecorator
            {
                public static function decorate(Cart $cartHandler): Collection
                {
                    return collect([
                        ['id' => 1, 'title' => 'Book 1', 'total' => 100],
                        ['id' => 2, 'title' => 'Book 2', 'total' => 150],
                    ]);
                }
            };
        });

        $response = $this->get(route('checkout'));

        $response->assertStatus(200);
        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Checkout/Index')
            ->has('cart', 2)
            ->where('totalPrice', 250));
    }

    public function test_add_to_cart()
    {
        $cartMock = $this->createMock(Cart::class);
        $cartMock->expects($this->once())->method('addItem')->with(1);

        $this->app->instance(Cart::class, $cartMock);

        $response = $this->post(route('cart.addItem'), ['id' => 1]);
        $response->assertRedirect();
    }

    public function test_get_cart_count()
    {
        $cartMock = $this->createMock(Cart::class);
        $cartMock->method('getItemsCount')->willReturn(5);

        $this->app->instance(Cart::class, $cartMock);

        $response = $this->get(route('cart.count'));

        $response->assertStatus(200);
        $response->assertJson(['cartCount' => 5]);
    }

    public function test_update_cart()
    {
        $cartMock = $this->createMock(Cart::class);
        $cartMock->expects($this->once())->method('setItemQuantity')->with(1, 3);

        $this->app->instance(Cart::class, $cartMock);

        $response = $this->post(route('cart.update'), ['id' => 1, 'quantity' => 3]);

        $response->assertRedirect();
    }

    public function test_remove_from_cart()
    {
        $cartMock = $this->createMock(Cart::class);
        $cartMock->expects($this->once())->method('removeItem')->with(1);

        $this->app->instance(Cart::class, $cartMock);

        $response = $this->post(route('cart.remove'), ['id' => 1]);

        $response->assertRedirect();
    }
}
