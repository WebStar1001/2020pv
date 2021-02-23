<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PendingPaymentEmailPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability) {

    }

    public function get_pending_payment_emails(User $user)
    {
        return $user->isSuperAdmin() || $user->isAdmin();
    }

    public function approvePaymentEmail(User $user, User $model)
    {
	    return $user->isSuperAdmin() || $user->isAdmin();
    }

}
