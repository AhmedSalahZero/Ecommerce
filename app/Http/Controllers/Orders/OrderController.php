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

    public function index(Request $request)
    {
        $order = $request->user()->orders()->with(['address','shippingMethod' , 'products','products.stock'
        ,'products.product' , 'products.product.variations','products.product.variations.stock'
        ])->latest()->paginate(10);
        return OrderResource::collection($order);
    }
    public function store(OrderStoreRequest $request , Cart $cart)
    {

        $cart->sync();

        if($cart->CheckIfEmpty())
        {
            return response(null , 400);

        }

        $this->cart = $cart ;

         $order = $this->createOrder($request )->with(['address' , 'products','shippingMethod'])->first();
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
