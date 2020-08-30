<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\RefreshDatabaseState;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Tymon\JWTAuth\Contracts\JWTSubject;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication ;
    use RefreshDatabase;
    public function jsonAs(JWTSubject $user , $method , $endpoint , $data=[] , $header=[])
    {
        $token = Auth()->tokenById($user->id);
        return $this->json($method , $endpoint , $data , array_merge($header , [
            'Authorization'=>'bearer ' . $token
        ]));
    }

}
