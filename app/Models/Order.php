<?php

namespace App\Models;

use App\cart\Money;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    const PENDING = 'pending' ;
    const PROCESSING = 'processing';
    const PAYMENT_FAILED = 'payment_failed';
    const COMPLETED = 'completed' ;
    protected $fillable = ['status' , 'address_id' , 'shipping_method_id' , 'subtotal' ,'user_id' , 'total' ,'payment_method_id'];

    public static function boot()
    {
        parent::boot();
        static::creating(function($order){
            $order->status = self::PENDING ;
        });

    }

    public function getSubtotalAttribute($subtotal){
        return new Money($subtotal);

    }

    public function total()
    {

        return $this->subtotal->add($this->shippingMethod->price);
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
    public function paymentMethod()
    {
        return $this->belongsTo(paymentMethod::class,'payment_method_id','id');
    }
    public function products()
    {
        return $this->belongsToMany(ProductVariation::class , 'product_variation_order',
            'order_id','product_variation_id'
        )->withPivot(['quantity'])->withTimestamps();

    }
    public function transactions()
    {
        return $this->hasMany(Transaction::class , 'order_id' , 'id');

    }



}
