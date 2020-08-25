<?php
namespace App\Scoping ;


use App\Scoping\Contracts\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class Scoper
{
    protected  $request  ;
    public function __construct(Request $request)
    {
//        dump(4);
        $this->request = $request ;
    }
    public function apply(Builder $builder  , array  $scopes)
    {
//        dump(5);
//
//        dd($value);
      //  dd($value);
        // return $builder->where('slug',$value);
        foreach ($this->limitScopes($scopes) as $key=>$scope)
        {

     //       dd(Request()->get($key));
            if (! $scope instanceof Scope)
                    continue;
            $scope->apply($builder , $this->request->get($key));

        }


        return $builder;


    }

    protected function  limitScopes(array $Scopes)
    {





     return arr::only($Scopes , array_keys($this->request->all()));

    }
}
