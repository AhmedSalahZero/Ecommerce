<?php

namespace Tests\Feature\Cart;

use App\Models\ProductVariation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CartUpdateTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_it_fails_if_the_user_is_not_authenticated()
    {
        $this->json('PATCH','api/cart/1')->assertStatus(401);

    }
    public function test_it_fails_if_product_can_not_be_found(){
        $user = factory(User::class)->create();
       $this->jsonAs($user , 'PUT' , 'api/cart/1')->assertStatus(404);

    }
    public function test_it_required_a_quantity_to_update(){
        $user = factory(User::class)->create();
        $product = factory(ProductVariation::class)->create();
        $this->jsonAs($user , 'PUT' , "api/cart/$product->id")->assertJsonValidationErrors(['quantity']);


    }
    public function test_it_required_a_numeric_quantity_to_update(){
        $user = factory(User::class)->create();
        $product = factory(ProductVariation::class)->create();
        $this->jsonAs($user , 'PUT' , "api/cart/$product->id" , ['quantity'=>'ahmed'])
             ->assertJsonValidationErrors(['quantity']);


    }
    public function test_it_required_quantity_at_least_one(){
        $user = factory(User::class)->create();
        $product = factory(ProductVariation::class)->create();
        $this->jsonAs($user , 'PUT' , "api/cart/$product->id" , ['quantity'=>0])
            ->assertJsonValidationErrors(['quantity']);


    }
    public function test_it_actually_update_the_cart(){
        $user = factory(User::class)->create();
        $product = factory(ProductVariation::class)->create();
        $user->cart()->attach($product , ['quantity'=>30]);
        $this->jsonAs($user , 'PUT' , "api/cart/$product->id" , ['quantity'=>60]);
        $this->assertDatabaseHas('cart_user' , [
            'quantity'=>60
        ]);



    }

}
