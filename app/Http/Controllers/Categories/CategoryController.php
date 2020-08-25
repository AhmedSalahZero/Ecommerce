<?php

namespace App\Http\Controllers\Categories;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CategoryController extends Controller
{
    public function index(){
//       return Response()->json(Category::select(['name','slug'])->get());
//dump(1);

//(Category::with('children')->get());

//      return CategoryResource::collection(Category::with('children')->Parents()->Ordered()->get());
//       dd(CategoryResource::collection(Category::with('children')->Parents()->Ordered()->get()));
      return  CategoryResource::collection(Category::with('children')->Parents()->Ordered()->get());




    }
}
