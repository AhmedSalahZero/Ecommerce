<?php

namespace Tests\Feature\Cart;

use App\Models\ProductVariation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CartDestroyTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_it_fail_unauthenticated()
    {
       $this->json('DELETE' , 'api/cart/20' )->assertStatus(401);
    }
    public function test_if_fails_if_product_do_not_founded()
    {
        $user =factory(User::Class)->create();
        $this->jsonAs($user,'DELETE' , 'api/cart/1' )->assertStatus(404);
    }
    public function test_it_removes_the_item()
    {
        $user=factory(User::class)->create();
        $product=factory(ProductVariation::class)->create();
        $user->cart()->attach($product);
        $this->jsonAs($user , 'delete' , "api/cart/{$product->id}") ;
        $this->assertDatabaseMissing('cart_user',[
            'product_variation_id'=>$product->id
        ]);


    }
}
