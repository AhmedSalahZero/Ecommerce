<?php

namespace Tests\Unit\Models\PaymentMethods;
use Tests\TestCase ; 
use App\Models\PaymentMethod ; 
use App\Models\User; 

class PaymentMethodTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_it_sets_the_old_default_to_false_if_the_new_one_is_true()
    {
        $user = factory(User::class)->create();
        $paymentMethodOld = factory(PaymentMethod::class)->create([
            'default'=>true, 
            'user_id'=>$user->id
        ]);
        $paymentMethodNew = factory(PaymentMethod::class)->create([
            'user_id'=>$user->id , 
            'default'=>true  ,
    
        ]);
        
        $this->assertEquals($paymentMethodOld->refresh()->default,0);
        // note that refresh is must here ; 
        // note that 0 represents 'false' in the database 
        // refresh() : because the value in the variable $paymentMethodOld is false 
        // then after that is changed to true in the database by the fn in the paymentMethod model
        // we want to refresh the value in the variable with the new one in the database ; 
        // so that we used refresh() method ;

    }
    public function test_it_belongs_to_a_user()
    {
        $user = factory(User::class)->create();
        $paymentMethod = factory(PaymentMethod::class)->create([
            'user_id'=>$user->id 
        ]);
        $this->assertInstanceOF(User::class , $paymentMethod->user);
        
    }
}
