<?php

namespace App\Models;

use App\cart\Money;
use App\Models\Traits\HasPrice;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Money\Currency;
use Money\Money as BasedMoney;

class ProductVariation extends Model
{
    use HasPrice ;

    public function getPriceAttribute($value)
    {
//        $money = new Money($value);
//        dd( $money->getAmount());

        if($value == NULL)
            return $this->product->price;
        return new Money($value) ;
    }
    public function stockCount()
    {
       return ($this->stock->sum('pivot.stock'));

        // or
        // return $this->stock->first()->pivot->stock;
    }
    public function minStock($count)
    {
        return min($this->stockCount()  , $count);

    }
    public function inStock()
    {
        return $this->stockCount()>0 ;
    }


    public function type(){
        return $this->belongsTo(ProductVariationType::class , 'product_variation_type_id' ,
        'id');
    }


    public function product()
    {
        return $this->belongsTo(Product::class , 'product_id' , 'id');
    }

    public function productVaries(){
        return $this->price->amount() !== $this->product->price->amount() ;

    }

    public function stocks()
    {
        return $this->hasMany(Stock::class,'product_variation_id','id' );

    }
    public function stock()
    {
       return $this->belongsToMany(ProductVariation::class , 'product_variation_stock_view' )
           ->withPivot(['stock','in_stock']);
    }


}
