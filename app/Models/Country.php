<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    public $timestamps = false ;
    protected $fillable =['name', 'code'];

    public function shippingMethods(){
        return $this->belongsToMany(ShippingMethod::class,'country_shipping_method' , 'country_id'
        ,'shipping_method_id');

    }

}
