<?php

namespace Tests\Feature\PaymentMethodsTest;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\PaymentMethod;


class PaymentMethodIndexTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_it_must_be_authencticated()
    {
        $user = factory(User::class)->create();

       $this->json( 'get' , 'api/payment-methods' )->assertStatus(401);
       
    }
    public function test_it_returns_a_collections_of_payment_methods()
    {
        $user = factory(User::class)->create();
        $paymentMethod = factory(PaymentMethod::class)->create(['user_id'=>$user->id ,'card_type'=>'x'] );
        $this->jsonAs($user,'get' , 'api/payment-methods');
        $this->assertDatabaseHas('payment_methods' , [
            'card_type'=>'x'
        ]);

    }
}
