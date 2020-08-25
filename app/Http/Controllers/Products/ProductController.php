<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductIndexResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\testCollection;
use App\Http\Resources\testResource;
use App\Models\Category;
use App\Models\Product;
use App\Scoping\Scopes\CategoryScope;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class ProductController extends Controller
{
    public function index(){
//        $product = Product::get()->load('Categories');
//        dd($product);
//
//        dd(Product::with('Categories')->get());


//        $scopes = ['category'=>CategoryScope::class] ;

        // arr::only($scopes , array_keys($this->request->all()) );

        // category = tea ;
        // 0 => category ;


//        $data = array('name'=>'ahmed' , 'age'=>15  , 'address'=>'cairo');
//        $only = arr::only($data , ['x'=>'name' ]) ;
//
//        dd($only);
       // $product = Product::paginate(10);
//       $x = Product::has('Categories')->get();
//       dd($x);


//        dd(Product::whereHas('Categories' , function(Builder $builder){
//
//           $builder ->where('id' , '=' , 1);
//
//        })->with('Categories')->get());
//        dd(Product::has('Categories')->where('slug' ,'tesst')->get());


//        dump(1);

        $product = Product::with('variations.stock')->WithScopes($this->scopes())
                        ->paginate(10);
        return ProductIndexResource::collection($product);




    }

    public function scopes(){
//        dump(2);

        return [
            'category'=>new CategoryScope()

        ];

    }
    public function show(Product $product){
        $product->load(['variations.type' , 'variations.stock','variations.product',
            'variations']);



        // return new ProductIndexResource($product); //we must use(new resource)(because we deal with one item [first()])
// now we want to add some attributes (in addition to that is exists in ProductIndexResource)

        return new ProductResource($product);




    }

//    public function test_fn(){
//        return new testResource(Product::where('id', 1)->first());
//
//
//
//    }
//    public function test_new_method_for_creating_collection(){
//
//
//        return  new testCollection(Product::where('id', 1)->get());
//
//    }
}
