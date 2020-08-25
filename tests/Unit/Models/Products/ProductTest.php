<?php

namespace Tests\Unit\Models\Products;

use App\cart\Money;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\Stock;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\App;
use Tests\TestCase;


class ProductTest extends TestCase
{

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_it_uses_slug_as_route_key_name()
    {
        $product = new \App\Models\Product() ;
       $this->assertEquals('slug' ,$product->getRouteKeyName());
    }
    public function test_it_has_many_category(){
        $product = factory(Product::class)->create();
        $product1 = factory(Product::class)->create();
        $product1->Categories()->attach(
            factory(Category::class,3)->create()
        );

        $this->assertEquals(3 , $product1->Categories()->get()->count());

    }
    public function test_it_has_many_variations(){
    $product = factory(Product::class)->create();
        $product1 = factory(Product::class)->create();
    $product1->variations()->save(
        factory(ProductVariation::class)->create(['id'=>1000])
    );

    $this->assertEquals(1, $product1->variations()->get()->count());
    }
    public function test_it_returns_money_instance()
    {
        $product = factory(Product::class)->create();
        $this->assertInstanceOf(Money::class , $product->price);
    }
    public function test_it_returns_formatted_price()
    {
        $product = factory(Product::class)->create([
            'price'=>8410
        ]);

        $this->assertEquals($product->FormattedPrice,'$84.10');
    }
    public function test_it_has_a_stock(){
        $product = factory(Product::class)->create();
       $product->variations()->save(
           $product_variation= factory(ProductVariation::class)->create([
               'product_id'=>$product->id
           ])
       );
       $addToStock = factory(Stock::class)->create([
           'product_variation_id'=>$product_variation->id ,
           'quantity'=>420
       ]);

      $this->assertEquals($product->stockCount() , 420);

    }
    public function test_it_has_at_least_one_quantity_in_stock()
    {
        $product = factory(Product::class)->create();
        $product->variations()->save
        (
            $product_variation= factory(ProductVariation::class)->create
            (
                ['product_id'=>$product->id]
            )
        );

        $addToStock = factory(Stock::class)->create
        (
            [
            'product_variation_id'=>$product_variation->id ,
            'quantity'=>420
            ]
        );
        $this->assertTrue($product->inStock());

    }




}
