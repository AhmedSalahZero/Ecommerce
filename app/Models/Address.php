<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = ['name' , 'address_1' , 'city' , 'postal_code' ,'country_id','default'];

    public function user()
    {
        return $this->belongsTo(User::class , 'user_id' , 'id');

    }
    public function country()
    {
        return $this->belongsTo(Country::class , 'country_id' , 'id');

    }
    public static function boot()
    {
        parent::boot();
        static::creating(function($address){
            if ($address->default)
            {
                $address->user->addresses()->update([
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

