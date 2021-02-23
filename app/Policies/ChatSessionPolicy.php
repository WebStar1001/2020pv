<?php

namespace App\Policies;

use App\ChatSession;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Cache;

class ChatSessionPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability) {

    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\ChatSession  $model
     * @return mixed
     */
    public function view(User $user, ChatSession $model)
    {
    	return $user->isSuperAdmin() || $user->isAdmin() || $model->customer_id === $user->id || $model->advisor_id === $user->id;
    }

	public function start_chat(User $user)
	{
		return $user->isCustomer();
	}

	public function accept_call(User $user)
	{
		return $user->isAdvisor();
	}

	public function cancel_call(User $user)
	{
		return $user->isCustomer() || $user->isAdvisor();
	}

	public function send_messages(User $user)
	{
		return $user->isCustomer() || $user->isAdvisor();
	}

	public function end_chat(User $user)
	{
		return $user->isCustomer() || $user->isAdvisor();
	}

	public function upload_files(User $user)
	{
		return $user->isCustomer() || $user->isAdvisor();
	}

	public function create_reviews(User $user)
	{
		return $user->isCustomer();
	}

	public function create_reports(User $user)
	{
		return $user->isCustomer();
	}

	public function send_out_of_money(User $user)
	{
		return $user->isCustomer() || $user->isAdvisor();
	}

	public function send_advisor_is_inactive(User $user)
	{
		return $user->isCustomer();
	}

	public function view_reports(User $user)
	{
		return $user->isSuperAdmin() || $user->isAdmin();
	}

	public function view_chat_sessions(User $user)
	{
		return $user->isSuperAdmin() || $user->isAdmin();
	}

	public function view_reading_history(User $user)
	{
		return $user->isAdvisor() || $user->isCustomer();
	}

	public function delete_reading_history(User $user)
	{
		return $user->isCustomer();
	}

	public function download_history(User $user)
	{
		return $user->isSuperAdmin() || $user->isAdmin() || Cache::has('logged-as-user-' . auth()->id()) ? true : false;
	}

}
