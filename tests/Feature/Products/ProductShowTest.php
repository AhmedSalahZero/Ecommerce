<?php

namespace Tests\Feature\Products;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use const http\Client\Curl\PROXY_HTTP;

class ProductShowTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_it_fails_if_a_product_cannot_be_found()
    {
         $this->json('get' , "api/products/tesst")->assertStatus(404);

    }
    public function test_it_returns_a_product()
    {
        $product = factory(Product::class)->create();

        $this->json('get' , "api/products/{$product->slug}")->assertJsonFragment([
            'slug'=>$product->slug
        ]);

    }

}
