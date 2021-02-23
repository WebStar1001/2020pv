<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DisputePolicy
{
    use HandlesAuthorization;

    public function before($user, $ability) {

    }

	public function index(User $user)
	{
		return $user->isSuperAdmin();
	}

	public function view(User $user)
	{
		return $user->isSuperAdmin();
	}
}
