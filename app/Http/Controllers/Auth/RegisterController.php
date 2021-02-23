<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\Admin\StoreAdvisorRequest;
use App\Http\Requests\Admin\StoreCustomerRequest;
use App\Role;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('guest');
	}

	/**
	 * Handle a registration request for the application.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function register(StoreCustomerRequest $request)
	{
		event(new Registered($user = $this->create($request)));

		Auth::login($user);

		return response()->json($user);
	}

	/**
	 * Handle a registration request for the application.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function registerAdvisor(StoreAdvisorRequest $request)
	{
		event(new Registered($user = $this->create($request)));

		return response()->json($user);
	}

	protected function create(Request $request)
	{
		$data = $request->all();

		if ($data['role_id'] == Role::getCustomerId() || $data['role_id'] == Role::getAdvisorId()) {
			$data['payment_email'] = $data['email'];
			$data['password']      = isset( $data['password'] ) ? $data['password'] : hash( 'md5', time() );
			$data['api_token']     = Str::random( 60 );

			if ( $data['role_id'] == Role::getAdvisorId() ) {
				$data['price_per_minute']      = isset( $data['price_per_minute'] ) ? $data['price_per_minute'] : 100;
				$data['commission_percentage'] = isset( $data['commission_percentage'] ) ? $data['commission_percentage'] : 60;
			} else {
				$data['approved'] = 1;
			}

			$data['subscribed'] = isset( $data['subscribed'] ) ? (bool) $data['subscribed'] : true;
			$data['ip']         = $this->getIp();

			$data['avatar'] = $request->hasFile( 'avatar' ) ? $request->file( 'avatar' )->store( 'avatars' ) : null;
			$data['resume'] = $request->hasFile( 'resume' ) ? $request->file( 'resume' )->store( 'resumes' ) : null;

			$user = User::create( $data );

			$slug_is_busy = User::where(['slug' => $this->slugify($data['display_name'])])->count();

			if ($slug_is_busy) {
				$user->update(['slug' => $this->slugify($data['display_name']) . '-' . $user->id ]);
			} else {
				$user->update(['slug' => $this->slugify($data['display_name'])]);
			}

			if ( ! $data['display_name'] ) {
				$user->update( [ 'display_name' => 'user' . $user->id ] );
			}

			return $user;
		}

		return null;
	}

	private function slugify($string){
		return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string), '-'));
	}

	private function getIp() {
		foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key){
			if (array_key_exists($key, $_SERVER) === true){
				foreach (explode(',', $_SERVER[$key]) as $ip){
					$ip = trim($ip); // just to be safe
					if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false){
						return $ip;
					}
				}
			}
		}

		return null;
	}
}
