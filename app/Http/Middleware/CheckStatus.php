<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CheckStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
    	if (Auth::check()) {
    		$user = Auth::user();

		    if ($user->deleted) {
			    Auth::logout();

			    return redirect('/login')->with('error', 'Account is deleted');
		    }

		    if ($user->blocked) {
			    Auth::logout();

			    return redirect('/login')->with('error', 'Account is blocked');
		    }

		    if (!$user->approved && !$user->decline_reason) {
			    Auth::logout();

			    return redirect('/login')->with('error', 'Account is not approved yet');
		    }

		    if ($user->decline_reason) {
			    Auth::logout();

			    return redirect('/login')->with('error', 'Account is not approved.<br><strong>Reason</strong>: ' . $user->decline_reason);
		    }
	    }

        return $next($request);
    }
}
