<?php
namespace App\cart\Payments\Gateways;
use App\cart\Payments\Gateway;
use App\Models\User;
use Stripe\Customer;

class StripeGateway implements Gateway
{
    protected  $user ;
    public function withUser(User $user){
        $this->user = $user ;
        return $this ;

    }
    public function user()
    {
        return $this->user ;
    }
    public function createCustomer(){

        // if the user exists in the our stripe
        if($this->user->gateway_customer_id)
        {
           return $this->getCustomer();
        }
        // if not exist create him in out stripe
        $customer =  new StripeGatewayCustomer(
            $this , $this->createStripeCustomer()
        );

        $this->user->update([
            'gateway_customer_id'=>$customer->id()
        ]);

        return $customer ;
    }
    public function getCustomer()
    {
        return new StripeGatewayCustomer($this,Customer::retrieve($this->user->gateway_customer_id));

    }
    protected function createStripeCustomer()
    {
      return Customer::create(['email'=>$this->user->email]);
        // note that : customer class is exist in stripe class
    }

}
