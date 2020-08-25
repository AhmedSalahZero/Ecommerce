<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_it_requires_an_email()
    {
        $this->json('Post' , 'api/auth/login' )->assertJsonValidationErrors(['email']);
    }
    public function test_it_requires_a_password()
    {
        $this->json('Post' , 'api/auth/login' )->assertJsonValidationErrors(['password']);
    }
    public function test_it_returns_a_validation_errors_if_credential_not_identical()
    {
        $user = factory(User::class )->create([
            'password'=>'123'
        ]);
        $this->json('Post' , 'api/auth/login' , ['email'=>$user->email , 'password' =>'456']  )
            ->assertJsonValidationErrors('email');

    }
    public function test_it_returns_a_token_if_credential_do_match()
    {
        $user = factory(User::class )->create([
            'password'=>'123456789'
        ]);
        $this->json('Post' , 'api/auth/login' , ['email'=>$user->email , 'password' =>'123456789'])
            ->assertJsonStructure([
                'meta'=>[
                    'token'
                ]
            ]);
    }
    public function test_it_returns_a_user_if_credential_do_match()
    {
        $user = factory(User::class )->create([
            'password'=>'123456789'
        ]);

        $this->json('Post' , 'api/auth/login' , ['email'=>$user->email , 'password' =>'123456789'])
            ->assertJsonFragment([
                'email'=>$user->email
            ]);
    }




}
