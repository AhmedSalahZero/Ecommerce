<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariationType extends Model
{
    protected $table = "product_variation_type" ;
    public $incrementing = false;

protected $primaryKey ='id';
    protected $fillable =['id' ,'name'];



}
