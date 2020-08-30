<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $fillable = ['card_type','last_four','default','provider_id' ];

    public function user()
    {
        return $this->belongsTo(User::class , 'user_id' , 'id');

    }
    public static function boot()
    {
        parent::boot();
        static::creating(function($paymethod){
            if($paymethod->default)
            {
                $paymethod->user->paymentMethods()->update([
                    'default'=>false
                ]);
            }
        });
    }

    public function setDefaultAttribute($value)
    {
        $this->attributes['default'] = ($value == "true" ? true : false);
    }

}
