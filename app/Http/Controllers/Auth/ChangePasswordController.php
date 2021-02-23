<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Hash;
use Illuminate\Support\Facades\Auth;
use Validator;

class ChangePasswordController extends Controller
{

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

	/**
	 * Change password.
	 *
	 * @param Request $request
	 * @return $this|\Illuminate\Http\RedirectResponse
	 */
	public function changePassword(Request $request)
	{
		$user = Auth::getUser();
		$this->validator($request->all())->validate();
		if (Hash::check($request->get('current_password'), $user->password)) {
			$user->password = $request->get('new_password');
			$user->save();
			return response()->json(['success' => 'Password change successfully!']);
		} else {
			return response()->json(['success' => 'Current password is incorrect']);
		}
	}

	public function adminChangePassword(Request $request, User $user)
	{
		$this->adminValidator($request->all())->validate();

		$user->update(['password' => Hash::make($request->new_password)]);
    }

	public function sendResetPasswordLink(User $user)
	{
		$user->sendPasswordSetNotification();
    }

	/**
	 * Get a validator for an incoming change password request.
	 *
	 * @param  array  $data
	 * @return \Illuminate\Contracts\Validation\Validator
	 */
	protected function validator(array $data)
	{
		return Validator::make($data, [
			'current_password' => 'required',
			'new_password' => 'required|min:6|confirmed',
		]);
	}

	protected function adminValidator(array $data)
	{
		return Validator::make($data, [
			'new_password' => 'required|min:6|confirmed',
		]);
	}
}
