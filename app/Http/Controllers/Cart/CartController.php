<?php

namespace App\Http\Controllers\Cart;

use App\cart\Cart;
use App\Http\Controllers\Controller;
use App\Http\Requests\Cart\CartStoreRequest;
use App\Http\Requests\Cart\CartUpdateRequest;
use App\Http\Resources\Cart\CartResource;
use App\Models\ProductVariation;
use Illuminate\Http\Request;

class CartController extends Controller
{

    public function __construct(){
        $this->middleware(['auth:api']);

    }
    public function index(Request $request , Cart $cart)
    {
        $cart->sync();

        $request->user()->load([
            'cart.product' ,'cart.product.variations.stock' , 'cart.stock'
        ]);

        return (new CartResource($request->user()))->additional([
            'meta'=>$this->meta($cart , $request)
        ]);

    }
    protected function meta(Cart $cart , Request $request){

        return [
            'empty'=>$cart->CheckIfEmpty() ,
            'subTotal'=>$cart->subTotal()->formatted(),
            'total'=>$cart->withShipping($request->shipping_method_id)->total()->formatted(),
            'changed'=>$cart->hasChanged()
        ];
    }
    public function store(CartStoreRequest $request , Cart $cart)
    {
        $cart->add($request->products);
    }
    public function update(ProductVariation $ProductVariation , CartUpdateRequest $request , Cart $cart)
    {
        $cart->update($ProductVariation->id , $request->quantity);
    }
    public function destroy(ProductVariation $ProductVariation , Cart $cart)
    {
        $cart->delete($ProductVariation->id);

    }
    public function emptyTheCart(Cart $cart){
        $cart->emptyCart();

    }
    public function CheckIfCartEmpty(Cart $cart){
$cart->CheckIfEmpty();
    }

}
