<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Log;

class UserPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability) {

    }

    public function index(User $user)
    {
        return $user->isSuperAdmin() || $user->isAdmin();
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\User  $model
     * @return mixed
     */
    public function view(User $user, User $model)
    {
    	if ($model->id === $user->id) {
    		return true;
	    }

	    return $user->isSuperAdmin() || $user->isAdmin();
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
	    return $user->isSuperAdmin() || $user->isAdmin();
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\User  $model
     * @return mixed
     */
    public function update(User $user, User $model)
    {
	    return $user->isSuperAdmin() || $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\User  $model
     * @return mixed
     */
    public function delete(User $user, User $model)
    {
	    return $user->isSuperAdmin();
    }

	public function change_password(User $user, User $model)
	{
		return $user->isSuperAdmin() || $user->isAdmin();
	}

	public function reset_password(User $user, User $model)
	{
		return $user->isSuperAdmin() || $user->isAdmin();
	}

	public function get_free_minutes(User $user)
	{
		return true;
	}

	public function add_free_minutes(User $user)
	{
		return $user->isSuperAdmin() || $user->isAdmin();
	}

	public function approve_account(User $user, User $model)
	{
		return $user->isSuperAdmin() || $user->isAdmin();
	}

	public function decline_account(User $user, User $model)
	{
		return $user->isSuperAdmin() || $user->isAdmin();
	}

	public function get_payment_settings(User $user)
	{
		return $user->isAdvisor();
	}

	public function view_my_clients(User $user)
	{
		return $user->isAdvisor();
	}

	public function view_my_psychics(User $user)
	{
		return $user->isCustomer();
	}

	public function block_customers(User $user)
	{
		return $user->isAdvisor();
	}

	public function update_favorites(User $user)
	{
		return $user->isCustomer();
	}

	public function update_status(User $user)
	{
		return true;
	}

	public function update_payouts(User $user)
	{
		return $user->isAdvisor();
	}

	public function update_activity(User $user)
	{
		return true;
	}

	public function login_as_user(User $user)
	{
		return $user->isSuperAdmin();
	}

	public function login_as_superadmin(User $user)
	{
		return $user->isAdvisor() || $user->isCustomer();
	}

	public function view_reviews(User $user)
	{
		return $user->isSuperAdmin() || $user->isAdmin();
	}

	public function edit_reviews(User $user)
	{
		return $user->isSuperAdmin() || $user->isAdmin();
	}

	public function delete_reviews(User $user)
	{
		return $user->isSuperAdmin();
	}

	public function send_newsletter(User $user)
	{
		return $user->isSuperAdmin() || $user->isAdmin();
	}

	public function view_global_settings(User $user)
	{
		return $user->isSuperAdmin() || $user->isAdmin();
	}

	public function edit_global_settings(User $user)
	{
		return $user->isSuperAdmin() || $user->isAdmin();
	}

	public function view_discounts(User $user)
	{
		return $user->isSuperAdmin() || $user->isAdmin();
	}

	public function create_discounts(User $user)
	{
		return $user->isSuperAdmin() || $user->isAdmin();
	}

	public function edit_discounts(User $user)
	{
		return $user->isSuperAdmin() || $user->isAdmin();
	}

	public function delete_discounts(User $user)
	{
		return $user->isSuperAdmin() || $user->isAdmin();
	}
}
