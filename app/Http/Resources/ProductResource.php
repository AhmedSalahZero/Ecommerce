<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends ProductIndexResource //then now it extends ProductIndexResource and JsonResource
    // because ProductResource extends ProductIndexResource and ProductIndexResource extends JsonResource
    // then ProductResource extends JsonResource also
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
////        dd($this->variations);
//dd($this->variations()->with('type')->get()
//    ->groupBy('type.name'));



        return array_merge(parent::toArray($request) , [
            'variation'=> ProductVariationResource::collection( $this->variations()->with('type')->get()
                ->groupBy('type.name'))
        ]);

        // then parent::toarray($request) give us all data in ProductIndexResource
        //then we merge it with the new data we wants to add
    }
}
