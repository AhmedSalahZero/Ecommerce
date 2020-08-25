<?php

namespace App\Http\Requests\Cart;

use Illuminate\Foundation\Http\FormRequest;
use function GuzzleHttp\Psr7\parse_header;

class CartStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'products'=>'required|array',
            'products.*.id'=>'required|exists:product_variations,id',
            'products.*.quantity'=>'numeric|min:1'
        ];
    }

    public function messages()
    {
        return [
            'products.required' =>'must enter a product  ' ,
            'products.*.id.exists'=>'This products is not available now . ' ,
            'products.*.quantity.min'=>'You Do Not choose Any quantity . ' ,
            'products.*.id.required'=>'must have an id' ,
            ''
        ];// TODO: Change the autogenerated stub
    }
}