<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */


    public function toArray($request)
    {
//        JsonResource::withoutWrapping();

        // you can access properties and relations using $this->
//        dump(2);

        return [

            'name'=>$this->name  , // property
            'slug'=>$this->slug ,//property
            'children'=>CategoryResource::collection($this->whenLoaded('children')) // relationship

        ];
    }




}
