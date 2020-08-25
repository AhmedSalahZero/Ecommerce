<?php

namespace App\Http\Controllers\Addresses;

use App\Http\Controllers\Controller;
use App\Http\Requests\Address\AddressStoreRequest;
use App\Http\Resources\AddressResource;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function __construct(){
        $this->middleware('auth:api');
    }
    public function index(Request $request)
    {

        return  AddressResource::collection($request->user()->addresses);
    }
    public function store(AddressStoreRequest $request){
         $newAddress = $request->only(['name' , 'address_1' , 'postal_code' , 'country_id' ,'city' ,'default']) ;
       $address = $request->user()->addresses()->create($newAddress);
        return new  AddressResource($address);
    }
}
