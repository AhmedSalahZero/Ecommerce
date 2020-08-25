<?php

namespace App\Policies;

use App\Models\Address;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Http\Request;

class AddressPolicy
{
    use HandlesAuthorization;
    public function show(User $user, Address $address)
    {
        return $user->id == $address->user_id ;
    }
}
