<?php

namespace Tests\Unit\Cart;

use App\cart\Cart;
use App\cart\Money;
use App\Models\Address;
use App\Models\Country;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\ShippingMethod;
use App\Models\Stock;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use Tests\TestCase;


class CartTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_it_can_add_to_cart()
    {

//        $user = factory(User::class)->create();
//        $product = factory(ProductVariation::class)->create();
//        $user->cart()->save($product , ['quantity'=>3]);
//
//        $this->assertEquals(1,$user->cart->count());

        // another Way
        $user = factory(User::class)->create();
        $cart = new Cart($user);
        $product = factory(ProductVariation::class)->create() ;
        $cart->add(
            [
                [  'id'=>$product->id ,
              'quantity'=>6
                ]
            ]
        );

        $this->assertEquals(1,$user->fresh()->cart->count());


    }

    public function test_it_increments_quantity_when_adding_to_existing_product()
    {


        $user = factory(User::class)->create();
        $product1 = factory(ProductVariation::class)->create();
        $cart = new Cart($user);
        $cart->add(
            [
                ['id' => $product1->id,
                    'quantity' => 6
                ]
            ]
        );
        // to simulate that it is different request
        $cart = new Cart($user);
        $cart->add(
            [
                ['id' => $product1->id,
                    'quantity' => 6
                ]
            ]
        );


        $this->assertEquals(12, $user->cart->first()->pivot->quantity);

    }
    public function test_it_can_update_quantity_in_the_Cart()
    {
        $user = factory(User::class)->create();
        $product =factory(ProductVariation::class)->create();
        $user->cart()->attach($product , [
            'quantity'=>50
        ]);
         $cart = new Cart($user);
         $cart->update($product->id , 101);
         $this->assertEquals(101,$user->cart()->first()->pivot->quantity);


    }
    public function test_it_can_delete_product_from_the_cart()
    {
        $user = factory(User::class)->create();
        $product=factory(ProductVariation::class)->create();
        $user->cart()->attach($product ,['quantity'=>81]);
        $this->assertDatabaseHas('cart_user' , [
            'product_variation_id'=>$product->id ,
            'quantity'=>81
        ]);
        $cart = new Cart($user);
        $cart->delete($product->id);
        $this->assertEquals(0,$user->cart->count());

    }
    public function test_it_can_empty_the_Cart()
    {
        $user = factory(User::class)->create();
        $product = factory(ProductVariation::class)->create();
        $user->cart()->attach($product , ['quantity'=>5]) ;
        $cart= new cart($user);
        $cart->emptyCart();
        $this->assertEquals(0 , $user->cart->count());

    }
    public function test_it_can_check_if_the_cart_is_empty()
    {
        $user = factory(User::class)->create();
        $product = factory(ProductVariation::class)->create();
        $user->cart()->save($product , [
            'quantity'=>5
        ]);
        $cart = new Cart($user);
        $this->assertFalse($cart->CheckIfEmpty());




    }
    public function test_it_syncs_the_cart()
    {
        $user = factory(User::class)->create();
        $product = factory(ProductVariation::class)->create();
        $user->cart()->attach($product , [
            'quantity'=>100
        ]);
        $cart = new Cart($user);
        $cart->sync();

        $this->assertDatabaseHas('cart_user' , [
            'quantity'=>0
        ]);
    }

    public function test_it_can_check_if_the_cart_has_changed_after_syncing()
    {
        $user = factory(User::class)->create();
        $product = factory(ProductVariation::class)->create();
        $user->cart()->attach($product , [
            'quantity'=>100
        ]);
        $cart = new Cart($user);
        $cart->sync();
        $this->assertTrue($cart->hasChanged());
    }
    public function test_it_return_money_instance_for_subtotal(){
        $user = factory(User::class)->create();
        $cart = new Cart($user);
        $this->assertInstanceOf(Money::class , $cart->subTotal());



    }
    public function test_it_return_money_instance_for_total(){
        $user = factory(User::class)->create();
        $cart = new Cart($user);
        $this->assertInstanceOf(Money::class , $cart->total());



    }

    public function test_it_get_the_correct_subtotal(){
        $user = factory(User::class)->create();
        $cart = new Cart($user);
        $product = factory(ProductVariation::class)->create([
            'price'=>1000
        ]);
        $user->cart()->attach($product , [
            'quantity'=>2
        ]);

        $this->assertEquals($cart->subTotal()->amount() , 2000);
    }
    public function test_it_can_return_total_cost_without_shipping()
    {
        $user = factory(User::class)->create();
        $cart = new Cart($user);
        $product = factory(ProductVariation::class)->create([
            'price'=>200
        ]);
        $stock = factory(Stock::class)->create([
            'product_variation_id'=>$product->id
        ]);

        $user->cart()->save($product,[
            'quantity'=>2
        ]);

        $this->jsonAs($user  , 'get' , 'api/cart' );
        $this->assertEquals('$4.00',$cart->total()->formatted());

    }
    public function test_it_can_return_total_cost_with_shipping()
    {
        $user = factory(User::class)->create();
        $cart = new Cart($user);
        $product = factory(ProductVariation::class)->create([
            'price'=>200
        ]);
        $stock = factory(Stock::class)->create([
            'product_variation_id'=>$product->id
        ]);
        $user->cart()->save($product,[
            'quantity'=>2
        ]);
        $country = factory(Country::class)->create();
        $shipping_method= factory(ShippingMethod::class)->create([
            'price'=>400
        ]);

        $country->shippingMethods()->save($shipping_method);
        $address = factory(Address::class)->create([
            'user_id'=>$user->id ,
            'country_id'=>$country->id
        ]);
        $this->jsonAs($user  , 'get' , 'api/cart' );
        $this->assertEquals('$8.00',$cart->withShipping($shipping_method->id)->total()->formatted());

    }
}
