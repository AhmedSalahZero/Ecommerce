<?php

namespace Tests\Unit\Models\Categories;

use App\Models\Category;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTest extends TestCase
{

use RefreshDatabase;




    public function test_it_has_many_children()
    {



        $category = factory(Category::class)->create();

        $category->children()->save(
            factory(Category::class)->create()
        );
        $this->assertInstanceOf(Category::class ,$category->children()->first());
    }

    public function test_it_can_fetch_only_parents()
    {



        $category = factory(Category::class)->create([
            'order'=>8
        ]);

        $category->children()->save(
            factory(Category::class)->create()
        );

        $this->assertEquals(1 ,$category->parents()->count());
    }

    public function test_it_can_ordered_by_ordered_number()
    {



        $category = factory(Category::class)->create([
            'order'=>2
        ]);
        $anotherCategory = factory(Category::class)->create([
            'order'=>1
        ]);


        $this->assertEquals($anotherCategory->name , Category::ordered()->first()->name);

    }
    public function test_it_has_many_products (){
//        dd(factory(Category::class)->create());
        $category = factory(Category::class)->create();
        $category->products()->attach(
            factory(product::class,3 )->create()
        );



    //    $this->assertEquals(3,$category->products()->count());


      $this->assertInstanceOf(Product::class, $category->products()->first());


    }




    /**
     * A basic unit test example.
     *
     * @return void
     */





}
