<?php

namespace App\Policies;

use App\Payment;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PaymentPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability) {

    }

    public function index(User $user)
    {
        return $user->isCustomer() || $user->isAdvisor();
    }

    public function view(User $user, Payment $model)
    {
	    return $user->isCustomer() || $user->isAdvisor();
    }

	public function pay(User $user)
	{
		return $user->isCustomer();
	}

	public function view_payment_settings(User $user)
	{
		return $user->isAdvisor();
	}

	public function update_payment_settings(User $user)
	{
		return $user->isAdvisor();
	}

	public function view_pending_payment_emails(User $user)
	{
		return $user->isAdmin() || $user->isSuperAdmin();
	}

	public function update_payment_emails(User $user)
	{
		return $user->isAdmin() || $user->isSuperAdmin();
	}

	public function view_transactions(User $user)
	{
		return $user->isSuperAdmin();
	}

}
