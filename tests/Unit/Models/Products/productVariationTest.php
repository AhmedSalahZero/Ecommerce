<?php

namespace Tests\Unit\Models\Products;

use App\cart\Cart;
use App\cart\Money;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\ProductVariationType;
use App\Models\Stock;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;


class productVariationTest extends TestCase
{
use RefreshDatabase;



    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_it_has_one_variation_type()
    {
        $variation = factory(ProductVariation::class)->create();

        $this->assertInstanceOf(ProductVariationType::class ,$variation->type);

    }
    public function test_it_belongs_to_a_product()
    {

       $variation = factory(ProductVariation::class)->create();
     $this->assertInstanceOf(Product::class ,  $variation->product);

    }
    public function test_it_returns_formatted_price(){
      //  $product = factory(Product::class)->create();
        $variation = factory(ProductVariation::class)->create([
            'price'=>1000
        ]);

        $this->assertEquals($variation->FormattedPrice,'$10.00');

    }

    public function test_it_returns_money_instance_for_the_price(){
      $productVariation  = factory(ProductVariation::class)->create();
      $this->assertInstanceOf(Money::class , $productVariation->price);
    }
    public function test_it_returns_the_product_price_if_price_is_null(){
        $product = factory(Product::class)->create([
            'price'=>1000
        ]);
        $variation = factory(ProductVariation::class)->Create([
            'price'=>null ,
            'product_id'=>$product->id
        ]) ;

        $this->assertEquals($variation->price->amount() , $product->price->amount());

    }

    public function test_it_can_check_the_productVariation_price_is_different_to_the_product(){
      $product = factory(Product::class)->create([
          'price'=>1000
      ]);
      $variation = factory(ProductVariation::class)->create([
          'price'=>2000 ,
          'product_id'=>$product->id
      ]);

      $this->assertTrue($variation->productVaries());

    }

    public function test_it_has_many_stocks(){


        $productVariation = factory(ProductVariation::class)->create();
        $stock1 = factory(Stock::class)->create([
            'product_variation_id'=>$productVariation->id
        ]);
        $stock2 = factory(Stock::class)->create([
            'product_variation_id'=>$productVariation->id
        ]);

        $this->assertEquals(2,$productVariation->stocks->count());

    }
    public function test_it_has_stock_information()
    {

        $productVariation = factory(ProductVariation::class )->create();
        $productVariation->stocks()->save(
            factory(Stock::class)->make([
                'quantity'=>780
            ])
        );
        $this->assertInstanceOf(ProductVariation::class,$productVariation->stock->first());
    }
    public function test_it_has_stock_count()
    {

        $productVariation = factory(ProductVariation::class )->create();
        $productVariation->stocks()->save(
            factory(Stock::class)->make([
                'quantity'=>780
            ])
        );
//        dd($productVariation->stock->first()->pivot->stock);
        $this->assertEquals($productVariation->stock->first()->pivot->stock , 780);

    }
    public function test_it_in_stock()
    {
        $productVariation = factory(ProductVariation::class )->create();
      $productVariation->stocks()->save(
            factory(Stock::class)->make([
                'quantity'=>780
            ])
        );
        $this->assertTrue($productVariation->inStock());
    }

    public function test_it_can_get_the_minimum_for_a_given_value(){
        $user = factory(User::class)->create();
        $product= factory(ProductVariation::class)->create();
        $user->cart()->attach($product ,['quantity'=>200]);
        $stock =factory(Stock::class)->create([
            'quantity'=>60 ,
            'product_variation_id'=>$product->id
        ]);
        $this->assertEquals($product->minStock($user->cart->first()->pivot->quantity) , 60);




    }

}
