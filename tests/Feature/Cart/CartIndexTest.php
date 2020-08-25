<?php

namespace Tests\Feature\Cart;

use App\Models\ProductVariation;
use App\Models\Stock;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CartIndexTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_you_have_to_be_authenticated()
    {
        $this->json('get' , 'api/cart')->assertStatus(401);

    }
    public function test_it_shows_products_in_user_cart()
    {
        $user = factory(User::class)->create();
        $product = factory(ProductVariation::class)->create();
        $user->cart()->sync($product);
      //  dd($this->jsonAs($user , 'get' , 'api/cart'));
        $this->jsonAs($user , 'get' , 'api/cart')->assertJsonFragment([

               'id'=>$product->id
        ]);
    }

    public function test_it_shows_if_the_Cart_is_empty()
    {
        $user = factory(user::class)->create();
        $product = factory(ProductVariation::class)->create();
        $stock = factory(Stock::class)->create([
            'quantity'=>1000  ,
            'product_variation_id'=>$product->id
        ]);
        $user->cart()->attach($product , ['quantity'=>2]);

        $this->jsonAs($user , 'get' , 'api/cart' )->assertJsonFragment([
            'empty'=>false
        ]);
    }
    public function test_it_sync_the_cart1(){
        $user = factory(User::class)->create();
        $product = factory(ProductVariation::class)->create();
        $user->cart()->attach($product , [
            'quantity'=>200
        ]);
        $this->jsonAs($user , 'GET' , 'api/cart')->assertJsonFragment([
            'changed'=>true
        ]);

    }
}
