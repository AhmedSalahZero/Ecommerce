<?php
namespace App\Models\Traits;
use App\cart\Money;

trait  HasPrice
{

    public function getFormattedPriceAttribute()
    {
       return $this->price->formatted();
    }
    public function getPriceAttribute($value)
    {
        return new Money($value) ;
    }


}
