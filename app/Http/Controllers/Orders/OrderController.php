<?php

namespace App\Http\Controllers\Orders;

use App\cart\Cart;
use App\Http\Controllers\Controller;
use App\Http\Requests\Orders\OrderStoreRequest;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected $cart ;
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function store(OrderStoreRequest $request , Cart $cart)
    {
        $this->cart = $cart ;
        $this->createOrder($request );
    }
    protected function createOrder(Request $request )
    {
        $request->user()->orders()->create(
        array_merge(
            $request->only(['address_id','shipping_method_id']) ,[
            'subtotal'=>$this->cart->subTotal()->amount()
        ]));
    }

}
