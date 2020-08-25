<?php
namespace App\Scoping\Scopes ;

use App\Models\Product;
use App\Scoping\Contracts\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class CategoryScope implements Scope {
    public function apply(Builder $builder  , $value)
    {

//        return $builder->whereHas('Categories')
//

        return $builder->whereHas('Categories' , function($builder) use ($value){

;
           $builder->where('slug',$value);


        });

    }

}

