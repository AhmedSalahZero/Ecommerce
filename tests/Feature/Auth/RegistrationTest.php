<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegistrationTest extends TestCase
{

use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_it_requires_name()
    {
       $this->json('POST' , 'api/auth/register')->assertJsonValidationErrors(['name']);

    }
    public function test_it_requires_email()
    {
        $this->json('POST' , 'api/auth/register')->assertJsonValidationErrors(['email']);

    }
    public function test_it_requires_valid_email()
    {
        $this->json('POST' , 'api/auth/register' , ['email'=>'ahmed'])->assertJsonValidationErrors(['email']);

    }
    public function test_it_requires_unique_email()
{
    $user = factory(User::class )->create();
    $this->json('POST' , 'api/auth/register' , ['email'=>$user->email])->assertJsonValidationErrors(['email']);

}
    public function test_it_requires_password()
    {
        $this->json('POST' , 'api/auth/register')->assertJsonValidationErrors(['password']);

    }
    public function test_it_registers_a_user(){
        $this->json('POST','api/auth/register' , [
            'name'=>$name='saed',
            'email'=>$email='khaled@yahoo.com' ,
            'password'=>'123456'
        ]) ;
        $this->assertDatabaseHas('users' , [
            'name'=>$name ,
            'email'=>$email ,
        ]);

    }
    public function test_it_returns_a_user_on_registration(){
        $user = $this->json('POST','api/auth/register' , [
            'name'=>$name='saed',
            'email'=>$email='khaled@yahoo.com' ,
            'password'=>'123456'
        ])->assertJsonFragment(['email'=>$email]);

    }




}
