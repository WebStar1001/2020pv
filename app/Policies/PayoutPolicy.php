<?php

namespace App\Policies;

use App\Payment;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PayoutPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability) {

    }

	public function view_own_payouts(User $user)
	{
		return $user->isAdvisor();
	}

	public function view_all_payouts(User $user)
	{
		return $user->isSuperAdmin();
	}

	public function make_payouts(User $user)
	{
		return $user->isSuperAdmin();
	}
}
