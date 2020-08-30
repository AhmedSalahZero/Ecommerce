<?php


namespace App\cart\Payments;


use App\Models\User;

interface Gateway
{
    public function withUser(User $user);
    public function createCustomer();
}
