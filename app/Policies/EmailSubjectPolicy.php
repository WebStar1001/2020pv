<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Cache;

class EmailSubjectPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability) {

    }

	public function view_email_subjects(User $user)
	{
		return true;
	}

	public function view_all_email_subjects(User $user)
	{
		return $user->isSuperAdmin() || $user->isAdmin();
	}

	public function create_email_subjects(User $user)
	{
		return $user->isCustomer() || $user->isAdvisor();
	}

	public function view_email_messages(User $user)
	{
		return true;
	}

	public function create_email_messages(User $user)
	{
		return $user->isCustomer() || $user->isAdvisor();
	}

	public function create_invoices(User $user)
	{
		return $user->isAdvisor();
	}

	public function cancel_invoices(User $user)
	{
		return $user->isCustomer() || $user->isAdvisor();
	}

	public function reject_invoices(User $user)
	{
		return $user->isCustomer();
	}

	public function accept_invoices(User $user)
	{
		return $user->isCustomer();
	}

	public function upload_files(User $user)
	{
		return $user->isCustomer() || $user->isAdvisor();
	}

	public function remove_files(User $user)
	{
		return $user->isCustomer() || $user->isAdvisor();
	}

	public function create_reports(User $user)
	{
		return $user->isCustomer();
	}

	public function create_reviews(User $user)
	{
		return $user->isCustomer();
	}

	public function download_history(User $user)
	{
		return $user->isSuperAdmin() || $user->isAdmin() || Cache::has('logged-as-user-' . auth()->id()) ? true : false;
	}

}
