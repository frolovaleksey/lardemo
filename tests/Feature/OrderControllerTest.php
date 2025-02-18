<?php

namespace Tests\Feature;

use App\Services\Cart\Cart;
use App\Services\Order\OrderRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

/*
 * php artisan test --filter OrderControllerTest
 */

class OrderControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $cartMock;
    protected $orderRepositoryMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->cartMock = Mockery::mock(Cart::class);
        $this->orderRepositoryMock = Mockery::mock(OrderRepository::class);

        $this->app->instance(Cart::class, $this->cartMock);
        $this->app->instance(OrderRepository::class, $this->orderRepositoryMock);
    }

    public function test_create_redirects_if_cart_is_empty()
    {
        $this->cartMock->shouldReceive('isEmpty')->once()->andReturn(true);

        $response = $this->get(route('order.create'));

        $response->assertRedirect(route('checkout'));
        $response->assertSessionHas('success', __('Cart is empty'));
    }

    public function test_create_renders_order_create_page_if_cart_is_not_empty()
    {
        $this->cartMock->shouldReceive('isEmpty')->once()->andReturn(false);

        $response = $this->get(route('order.create'));

        $response->assertInertia(fn (Assert $page) => $page->component('Order/Create'));
    }

    public function test_store_creates_order_and_redirects()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $orderData = ['email' => 'test@test.com'];

        $this->cartMock->shouldReceive('getCart')->once()->andReturn([]);
        $this->cartMock->shouldReceive('clean')->once();

        $response = $this->post(route('order.store'), $orderData);

        $response->assertRedirect(route('book.index'));
        $response->assertSessionHas('success', __('Order created successfully!'));
    }
}
