<?php

namespace App\Events\Orders;

use App\Models\Order;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderPaymentFailed
{
    public $order ;
    use Dispatchable, SerializesModels;


    public function __construct(Order $order)
    {
        $this->order = $order;
    }

}
