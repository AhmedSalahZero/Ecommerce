<?php

namespace Tests\Unit\Listeners;

use App\cart\Cart;
use App\Listener\Orders\EmptyCart;
use App\Models\ProductVariation;
use App\Models\User;
use Tests\TestCase;

class EmptyCartListenerTest extends TestCase
{
    public function test_it_should_empty_the_cart()
    {
        $cart = new Cart(
            $user = factory(User::class)->create()
        );
        $user->cart()->attach(
            $product=factory(ProductVariation::class)->create()
        );
        $listener = new EmptyCart($cart);
        $listener->handle();
        $this->assertEmpty($user->cart);

    }
}
