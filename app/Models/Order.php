<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    const PENDING = 'pending' ;
    const PROCESSING = 'processing';
    const PAYMENT_FAILED = 'payment_failed';
    const COMPLETED = 'completed' ;
    protected $fillable = ['status' , 'address_id' , 'shipping_method_id' , 'subtotal'];

    public static function boot()
    {
        parent::boot();
        static::creating(function($order){
            $order->status = self::PENDING ;
        });

    }

    public function user()
    {
        return $this->belongsTo(User::class);

    }
    public function address()
    {
        return $this->belongsTo(Address::class);

    }
    public function shippingMethod()
    {
        return $this->belongsTo(ShippingMethod::class);

    }
    public function products()
    {
        return $this->belongsToMany(ProductVariation::class , 'product_variation_order',
            'order_id','product_variation_id'
        )->withPivot(['quantity'])->withTimestamps();

    }



}