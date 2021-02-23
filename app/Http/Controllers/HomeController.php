<?php

namespace App\Http\Controllers;

use App\Discount;
use App\Http\Requests;
use App\Providers\AuthServiceProvider;
use App\Role;
use App\Setting;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function($request, $next)  {
            $policies = AuthServiceProvider::getPolicies();
            $user = Auth::user();
            foreach ($policies as  $frontend_name => $backend_name) {
                $policies[$frontend_name] = [
                    'index' => $user->can('index', $backend_name),
                    'create' => $user->can('create', $backend_name),
                ];
            }
            $user->rights = $policies;

            return $next($request);
        });
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
	    $user = null;
	    $user_discount = 0;
	    $discount = null;
	    $discount_history = [];
	    $discount_active = false;

	    if (Auth::check()) {
		    $user = User::where(['id' => auth()->id()])->with('masterService')->first();

		    $user->update([
			    'last_activity' => now(),
			    'is_logged_in' => Cache::has( 'user-is-offline-' . auth()->id() ) ? 0 : 1
		    ]);

		    $user->emailNotification()->delete();

		    if ($user->isCustomer()) {
			    if ( $user->created_at->diffInDays( now() ) <= 14 ) {
				    $discount = Discount::where( [ 'for_new_users' => 1 ] )->latest()->first();
			    } else if ( $user->created_at->diffInDays( now() ) > 14 ) {
				    $discount = Discount::where( [ 'for_new_users' => 0 ] )->latest()->first();
			    }

			    if ($discount && $discount->active) {
				    $discount_active = true;

				    $user_discount = $discount->discount;

				    $discount_history = $user->discountHistory()
				                             ->where(['discount_id' => $discount->id])
				                             ->pluck('advisor_id');
			    }
		    }
	    } else {
		    $discount = Discount::where(['for_new_users' => 1])->latest()->first();
		    $user_discount = $discount && $discount->active ? $discount->discount : 0;
	    }

	    $roles = [
		    'SUPERADMIN' => Role::getSuperAdminId(),
		    'ADMIN' => Role::getAdminId(),
		    'CUSTOMER' => Role::getCustomerId(),
		    'ADVISOR' => Role::getAdvisorId(),
	    ];

	    $settings = Setting::all()->pluck('value', 'key')->toArray();

	    return view('home', compact(
		    'roles',
		    'user',
		    'settings',
		    'user_discount',
		    'discount_history',
		    'discount_active'
	    ));
    }

}
