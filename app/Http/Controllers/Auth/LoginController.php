<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Role;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/admin/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

	protected function authenticated(Request $request, $user)
	{
		if ($user->isCustomer() || $user->isAdvisor()) {
			Auth::logoutOtherDevices( $request->password );
		}

		Cache::forget('user-is-offline-' . auth()->id());

		$user->update([
			'last_activity' => now(),
			'is_logged_in' => 1
		]);
	}

	public function login(Request $request)
	{
		$user = User::where(['email' => $request->email])->first();

		if ($user && $user->password === 'migrated') {
			return response()->json('Your account was migrated from the old system. Please <a href="/reset-password">reset your password</a>');
		}

		$this->validateLogin($request);

		// If the class is using the ThrottlesLogins trait, we can automatically throttle
		// the login attempts for this application. We'll key this by the username and
		// the IP address of the client making these requests into this application.
		if ($this->hasTooManyLoginAttempts($request)) {
			$this->fireLockoutEvent($request);

			return response()->json('Too many attempts');
		}

		if ($this->attemptLogin($request)) {
			return $this->sendLoginResponse($request);
		}

		// If the login attempt was unsuccessful we will increment the number of attempts
		// to login and redirect the user back to the login form. Of course, when this
		// user surpasses their maximum number of attempts they will get locked out.
		$this->incrementLoginAttempts($request);

		return response()->json('Wrong email or password');
	}

	protected function sendLoginResponse(Request $request)
	{
		$request->session()->regenerate();

		$this->clearLoginAttempts($request);

		if (!$this->authenticated($request, $this->guard()->user())) {
			$user = auth()->user();
			$user->status = $user->status();

			$roles = [
				'SUPERADMIN' => Role::getSuperAdminId(),
				'ADMIN' => Role::getAdminId(),
				'CUSTOMER' => Role::getCustomerId(),
				'ADVISOR' => Role::getAdvisorId(),
			];

			return response()->json([
				'user' => $user,
				'roles' => $roles,
				'csrf_token' => csrf_token()
			]);
		} else {
			return response()->json('Not Authenticated');
		}
	}

	/**
	 * Log the user out of the application.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function logout(Request $request)
	{
		$user = auth()->user();

		$active_chat_session = $user->activeChatSession();

		if ($active_chat_session) {
			$active_chat_session->end('manually');
		}

		$expiresAt = now()->addHours(24);
		Cache::put('user-is-offline-' . $user->id, true, $expiresAt);

		Cache::forget('user-is-busy-' . $user->id);

		$user->update(['is_logged_in' => 0]);

		$this->guard()->logout();

		$request->session()->invalidate();

		return response()->json(true);
	}

    
}
