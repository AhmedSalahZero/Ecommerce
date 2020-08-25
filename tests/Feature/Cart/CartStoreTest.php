<?php

namespace Tests\Feature\Cart;

use App\Models\ProductVariation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CartStoreTest extends TestCase
{

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_it_fails_if_user_not_authenticated()
    {
        $this->json('post' ,'api/cart')->assertStatus(401);
    }
    public function test_it_requires_products(){
        $user = factory(User::class)->create();
        //  dd($this->jsonAs($user , 'post' , 'api/cart' )->getContent());

        $this->jsonAs($user , 'post' , 'api/cart' )->assertJsonValidationErrors(['products']);
    }
    public function test_it_requires_products_to_be_an_array(){
    $user = factory(User::class)->create();
    //  dd($this->jsonAs($user , 'post' , 'api/cart' )->getContent());
    $this->jsonAs($user , 'post' , 'api/cart'  , ['products'=>2]
    )->assertJsonValidationErrors(['products']);
    }
    public function test_it_requires_products_to_have_an_ID(){
        $user = factory(User::class)->create();

        $this->jsonAs($user , 'post' , 'api/cart'  , ['products'=>
            [
                ['quantity'=>3]
            ]
            ]
        )->assertJsonValidationErrors(['products.0.id']);
    }
    public function test_it_requires_products_to_exists(){
        $user = factory(User::class)->create();

        $this->jsonAs($user , 'post' , 'api/cart'  , ['products'=>
                [
                    ['quantity'=>3 , 'id'=>99 ]
                ]
            ]
        )->assertJsonValidationErrors(['products.0.id']);
    }

    public function test_it_requires_products_numeric_quantity(){
        $user = factory(User::class)->create();

        $this->jsonAs($user , 'post' , 'api/cart'  , ['products'=>
                [
                    ['quantity'=>'lol' , 'id'=>99 ]
                ]
            ]
        )->assertJsonValidationErrors(['products.0.quantity']);
    }
    public function test_it_requires_products_quantity_at_least_one(){
        $user = factory(User::class)->create();


        $this->jsonAs($user , 'post' , 'api/cart'  , ['products'=>
                [
                    ['quantity'=>0 , 'id'=>99 ]
                ]
            ]
        )->assertJsonValidationErrors(['products.0.quantity']);
    }
    public function test_it_can_add_products_to_the_cart(){
        $user = factory(User::class)->create();
        $product = factory(ProductVariation::class)->create();

        $this->jsonAs($user , 'post' , 'api/cart'  , ['products'=>
                [
                    ['quantity'=>20 , 'id'=>$product->id ]
                ]
            ]
        );
        $this->assertDatabaseHas('cart_user' , [
            'quantity'=>20 ,
            'product_variation_id'=>$product->id
        ]);


    }





    }
