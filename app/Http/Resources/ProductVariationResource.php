<?php

namespace App\Http\Resources;

use App\Models\ProductVariation;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;


class ProductVariationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */

    public function toArray($request)
    {
//        dump('out then resource' );
//        dump($this->resource);
        if ($this->resource instanceof  Collection)
        {
//            dump('inside then resourse' );
//            dump($this->resource);
            return ProductVariationResource::collection($this->resource);
        }
//dd('get out ');
        return [
            'id'=>$this->id ,
            'name'=>$this->name ,
            'price'=>$this->FormattedPrice ,
            'productVaries'=>$this->productVaries() ,
            'stock_count'=>(int)$this->stockCount() ,
            'in_stock'=>$this->inStock() ,
            'product'=>new ProductIndexResource($this->product)
        ];

    }
}
