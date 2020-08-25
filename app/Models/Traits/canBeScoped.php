<?php

namespace App\Models\Traits;

use App\Scoping\Scoper;
use Illuminate\Database\Eloquent\Builder;

trait canBeScoped{


    public function scopeWithScopes(Builder $builder , $scopes=[])
    {

        return (new Scoper(request()))->apply($builder , $scopes);
    }



}
