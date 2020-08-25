<?php

namespace App\Models;

use App\Models\Traits\canBeScoped;
use App\Models\Traits\HasPrice;
use Illuminate\Database\Eloquent\Model;




class Product extends Model
{
    use canBeScoped , HasPrice;

    public function getRouteKeyName()
    {
        return 'slug';

    }


    public function Categories(){

        return $this->belongsToMany(Category::class,'category_product' , 'product_id' ,
        'category_id');

    }

    public function stockCount()
    {
        $totalCount = 0 ;
        foreach($this->variations as $variation)
        {
            $totalCount = $totalCount+$variation->stockCount();
        }
       return $totalCount ;


//        return $this->variations->sum(function($variation){
//            dd($variation);
//
//           return $variation->stockCount();
//
//        });



    }
    public function inStock(){
        return $this->stockCount()> 0 ;

    }
    public function variations(){


        return $this->hasMany(ProductVariation::class , 'product_id' , 'id')
            ->orderBy('order','asc');

    }



}

