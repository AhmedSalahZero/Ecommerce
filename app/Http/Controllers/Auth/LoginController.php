<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\PrivateUserResource;
use http\Env\Response;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function action(LoginRequest $request){

        if(!$token = Auth()->attempt($request->only('email','password')))
        {
            return Response()->json([
                'errors' => [
                    'email'=>'could not sign you in with those credentials'
                ]
            ] , 422);
        }
        return (new PrivateUserResource($request->user()))->additional([
         'meta'=>[
             'token'=>$token
         ]
        ]);
        // $request->user() returns an instance of the authenticated user...
        // $request->user() == Auth::user() ;




    }
}
