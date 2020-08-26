<?php

namespace App\Http\Controllers\Orders;

use App\cart\Cart;
use App\Events\Orders\OrderCreated;
use App\Http\Controllers\Controller;
use App\Http\Requests\Orders\OrderStoreRequest;
use App\Http\Resources\OrderResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;

class OrderController extends Controller
{
    protected $cart ;
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function store(OrderStoreRequest $request , Cart $cart)
    {

        if($cart->CheckIfEmpty())
        {
            return response(null , 400);

        }

        $this->cart = $cart ;

         $order = $this->createOrder($request );
        $order->products()->sync($cart->products()->forSyncing());

        // this sync not the sync we have created before

      Event(new OrderCreated($order));
        return new OrderResource($order);


    }
    protected function createOrder(Request $request )
    {
       return  $request->user()->orders()->create(
        array_merge(
            $request->only(['address_id','shipping_method_id']) ,[
            'subtotal'=>$this->cart->subTotal()->amount()
        ]));
    }

}
