<?php

namespace Tests\Feature\Categories;

use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryIndexTest extends TestCase
{
    use RefreshDatabase;


    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_it_returns_json()
    {

//        dump(3);
//        $x = 'alo';

        $categories = factory(Category::class, 2)->create()->each(function ($category) {
            $this->json('get', 'api/categories')->assertJsonFragment([
                'slug' => $category->slug
            ]);
        });
//        dump(4);

//       $this->json( 'get','api/categories')->assertJsonFragment(
//           ['slug'=>$category->get(0)->slug] );

    }

    public function test_it_returns_only_parents()
    {

        $category = factory(Category::class)->create();
        $category->children()->save(
            factory(Category::class)->create()
        );
        $this->json('get', 'api/categories')->assertJsonCount(1, 'data');
    }

    public function test_it_returns_categories_in_thier_given_order()
    {

        $category = factory(Category::class)->create([
            'order' => 2
        ]);
        $another_category = factory(Category::class)->create([
            'order' => 1
        ]);

        $this->json('get' , 'api/categories' )->assertSeeInOrder([
            $another_category->slug  , $category->slug  // then i expect that i see $another_category->slug then
            // $category->slug (in that order )
        ]);



    }

}
