<?php


namespace App\Models\Collections;


use Illuminate\Database\Eloquent\Collection;

class ProductVariationCollection extends Collection
{
    public function forSyncing(){
// $this is the productVariationCollection so focus ;

        return $this->keyBy('id')->map(function($product){
            return [
                'quantity'=>min($product->pivot->quantity , $product->stockCount())
            ];
        })->toArray();
    }
}
