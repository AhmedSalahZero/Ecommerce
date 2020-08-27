<?php

namespace Tests\Unit\Collections;

use App\Models\Collections\ProductVariationCollection;
use App\Models\ProductVariation;
use App\Models\User;
use Tests\TestCase;

class ProductVariationsCollectionTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_it_can_get_a_syncing_array()
    {
        $user = factory(User::class)->create();
        $product =factory(ProductVariation::class)->create();
        $user->cart()->attach($product , ['quantity'=>$quan=2]);
        $collection = new ProductVariationCollection($user->cart);

        $this->assertEquals($collection->forSyncing() , [
            $product->id =>[
                'quantity'=>min($quan , $product->stockCount())
            ]
        ]);

    }
}
