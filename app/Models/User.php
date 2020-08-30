<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;


class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'gateway_customer_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
         'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getJWTIdentifier()
    {
        // identifier of user (id) for example
        return $this->id;
    }

    public function getJWTCustomClaims()
    {
        // to add more info to your payload (the second part of jwt that
        // carries the data )
        return [];

    }
    public static function boot()
    {
        parent::boot();
        static::creating(function($user)
        {
            $user->password = bcrypt($user->password);
        }
        );
    }
    public function cart(){
        return $this->belongsToMany(ProductVariation::class , 'cart_user' , 'user_id' ,
            'product_variation_id' )->withPivot('quantity')->withTimestamps();
        //withTimestamps tells you when item updated or created

    }
    public function addresses()
    {
        return $this->hasMany(Address::class , 'user_id', 'id');
    }
    public function orders()
    {
        return $this->hasMany(Order::class , 'user_id','id');
    }
    public function paymentMethods()
    {
        return $this->hasMany(PaymentMethod::class , 'user_id' , 'id');
    }
    public function transactions(){
        return $this->hasMany(Transaction::class , 'user_id' , 'id');
    }

}
