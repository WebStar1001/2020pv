<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MenuPolicy
{
    use HandlesAuthorization;

    public function dashboard($user)
    {
        return true;
    }

	public function chat(User $user)
	{
		return $user->isCustomer() || $user->isAdvisor();
	}
}
