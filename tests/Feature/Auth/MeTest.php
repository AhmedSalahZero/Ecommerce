<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Faker\Generator as Faker;

class MeTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_it_fails_if_user_is_not_authenticated()
    {
        $this->json('get' , 'api/auth/me')->assertStatus(401);

    }
    public function test_it_returns_user_credential_if_authenticated()
    {
       $user = factory(User::class)->create();
       $this->jsonAs($user , 'get' , 'api/auth/me' , [] , []) ->assertJsonFragment([
           'email'=>$user->email
       ]);

    }
}
