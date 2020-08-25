<?php

namespace App\Models;

use App\Models\Traits\HasPrice;
use Illuminate\Database\Eloquent\Model;

class ShippingMethod extends Model
{
    use HasPrice ;
    public function countries(){
        return $this->belongsToMany(Country::class , 'country_shipping_method'
            , 'shipping_method_id','country_id');


    }
}
