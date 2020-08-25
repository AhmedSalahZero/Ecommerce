<?php

namespace Tests\Feature\Products;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductIndexTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_it_returns_collection_of_products()
    {
        $product = factory(Product::class)->create();
        $this->json('Get' , 'api/products')->assertJsonFragment([
            'name'=>$product->name
        ]);

    }
    public function test_it_has_paginated_data()
    {
        $product = factory(Product::class)->create();
        $this->json('Get' , 'api/products')->assertJsonStructure([
            'links' , 'meta' // assert that there exists links and meta as keys in that api request
        ]);

    }
}
