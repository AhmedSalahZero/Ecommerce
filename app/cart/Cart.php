<?php


namespace App\cart;


use App\Models\ShippingMethod;
use App\Models\User;

class Cart
{
    protected  $user ;
    protected  $shipping ;
    protected $changed = false;
    public function __construct(User $user)
    {
        $this->user = $user ;
    }
    public function products()
    {
        return $this->user->cart ;

    }
    public function add($products){


        //        $test = collect(['first'=>5 , 'second'=>6 , 'third'=>3 ,'forth'=>4 , 'fifth'=>5]);
//        $new = $test->map(function ($item ){
//          return $item*2 ;
//        });
//        dd($new->all());


//        $products = $request->products ;
//        dd($products);

//        $products =
        $this->user->cart()->syncWithoutDetaching($this->getStorePayload($products));



    }
    public function getStorePayload($products){
        return collect($products)->keyBy('id')->map(function ($product){
            return [
                'quantity'=>$product['quantity']+$this->getCurrentQuantity($product['id'])
            ];
        })->toArray();



        // another way

//       $request->user()->cart()->syncWithoutDetaching([50 => [
//           'quantity'=>10 ,
////           'created_at'=>now() ,
//    // and so on   ''=>''
//       ]]);


    }
    public function withShipping($shippingId)
    {


        $this->shipping = ShippingMethod::find($shippingId);
        return $this ;
    }
    protected function getCurrentQuantity($productId)
    {
        if ($product = $this->user->cart()->where('id',$productId)->first())
        {

            return $product->pivot->quantity ;

        }
        return 0 ;
    }
    public function update($productId ,$quantity)
    {


        $this->user->cart()->updateExistingPivot($productId , [
            'quantity'=>$quantity
        ]);

    }
    public function delete($productId)
    {

        $this->user->cart()->detach($productId);


    }
    public function emptyCart(){
        $this->user->cart()->detach();

    }
    public function sync()
    {
        $this->user->cart()->each(function($product){
          //grip min quantity
            $quantity=$product->minStock($product->pivot->quantity);
            //note That :  i want always the quantity i request to be less than the quantity that exists in the stock
            // note that : if the quantity i request is less than the quantity in the stock then there is not change will be happened
if ($quantity != $product->pivot->quantity)
{
    $this->changed=true ;
}
$product->pivot->update(['quantity'=>$quantity]) ;
        });
    }
    public function hasChanged()
    {
        return $this->changed;

    }
    public function CheckIfEmpty()
    {
 return       $this->user->cart->sum('pivot.quantity') == 0 ;

    }
    public function subTotal()
    {
        $subtotal = $this->user->cart->sum(function($product){
           return $product->price->amount() * $product->pivot->quantity ;

        });
        return new Money($subtotal);


    }
    public function total(){


        if($this->shipping)
        {
            // add the subtotal and the sipping price
            return $this->subTotal()->add($this->shipping->price);
        }

        return $this->subTotal();

    }
}
