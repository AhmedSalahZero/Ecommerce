<?php

namespace App\Http\Controllers\PaymentMethods;

use App\cart\Payments\Gateway;
use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentMethods\PaymentMethodStoreRequest;
use Illuminate\Http\Request;
use App\http\Resources\paymentMethodResource;
class PaymentMethodController extends Controller
{
    protected $gateway ;
    public function __construct(Gateway $gateway)
    {
        $this->middleware('auth:api');
        $this->gateway = $gateway ;

    }
    public function index(Request $request)
    {
        return paymentMethodResource::collection($request->user()->paymentMethods);
    }
    public function store(PaymentMethodStoreRequest $request)
    {


       $card =  $this->gateway->withUser($request->user())
            ->createCustomer()->addCard($request->token);

       return new PaymentMethodResource($card);


    }
}
