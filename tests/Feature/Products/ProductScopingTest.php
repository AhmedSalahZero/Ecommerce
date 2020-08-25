<?php

namespace Tests\Feature\Products;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductScopingTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_it_can_scope_by_category()
    {
        $product = factory(Product::class )->create();
        $product1 = factory(Product::class)->create([
            'id'=>100
        ]);
        $product1->Categories()->save(
         $category=   factory(Category::class)->create());

        $this->json('Get' , "api/products/?category={$category->slug}")->assertJsonCount(1,'data');



    }
}
