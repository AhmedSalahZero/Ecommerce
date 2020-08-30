<?php

use App\Models\Category;
use App\Models\ProductVariation;

Route::get('/' , function(){

    return Category::Parents()->Ordered('desc')->get();

});
Route::group(['prefix'=>'auth' ] , function(){
    Route::post('register' , 'Auth\RegisterController@action');
    Route::post('login' , 'Auth\LoginController@action');
    Route::get('me' , 'Auth\MeController@action');
});
Route::resource('categories' , 'Categories\CategoryController');
Route::resource('products' ,'Products\ProductController');
Route::resource('addresses' ,'Addresses\AddressController');
Route::resource('countries' ,'Countries\CountryController');
Route::resource('orders' ,'Orders\OrderController');
Route::resource('payment-methods' ,'paymentMethods\paymentMethodController');
Route::get('addresses/{address}/shipping' ,'Addresses\AddressShippingController@action');

//Route::get('test','Products\ProductController@test_fn');
//Route::get('test_collection','Products\ProductController@test_new_method_for_creating_collection');
Route::resource('cart' ,'Cart\CartController'  , [
    'parameters'=>[
        'cart'=>'ProductVariation'
    ]
]);

Route::post('cart/empty' , 'cart\CartController@emptyTheCart');
route::get('checkIfCartEmpty' , 'cart\CartController@CheckIfCartEmpty');




