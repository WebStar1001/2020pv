<?php

namespace App\Http\Controllers;

use App\Country;
use App\Events\BalanceUpdated;
use App\Notifications\AccountDeclined;
use App\Role;
use App\User;
use App\Http\Resources\UserResource;
use App\Http\Requests\Admin\StoreUsersRequest;
use App\Http\Requests\Admin\UpdateUsersRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Notifications\Notifiable;


class UsersController extends Controller
{
	use Notifiable;

    public function __construct()
    {
        $this->authorizeResource(User::class, 'user');
    }

    public function index(Request $request)
    {
        $this->authorize('index', User::class);

	    $users = User::with(['role'])
	                 ->where(function($query) use ($request) {
	                 	if ($request->role_id) {
		                    $query->where('role_id', $request->role_id);
	                 	}
	                 })
	                 ->where(function($query) use ($request) {
	                 	$query->where('display_name', 'LIKE', '%'.$request->search.'%')
	                          ->orWhere('email', 'LIKE', '%'.$request->search.'%')
	                          ->orWhere('id', $request->search);
	                 })
	                 ->offset($request->offset)
	                 ->limit($request->limit)
	                 ->orderBy($request->sort, $request->order)
	                 ->get();

	    $total = User::with(['role'])
	                 ->where(function($query) use ($request) {
		                 if ($request->role_id) {
			                 $query->where('role_id', $request->role_id);
		                 }
	                 })
	                 ->where(function($query) use ($request) {
		                 $query->where('display_name', 'LIKE', '%'.$request->search.'%')
		                       ->orWhere('email', 'LIKE', '%'.$request->search.'%')
		                       ->orWhere('id', $request->search);
	                 })
	                 ->count();

	    return [
	    	'data' => new UserResource($users, Auth::user()),
		    'total' => $total
	    ];
    }

    public function show(User $user, Request $request)
    {
        $user = User::with(['role', 'horoscope', 'masterService', 'bankDetail'])->findOrFail($user->id);
        $user->price_per_minute = $user->price_per_minute / 100;

        if ($user->isAdvisor()) {
	        $sales = $user->payments()->sum('amount');

	        $daily_sales = $user->payments()->where('created_at', '>=', now()->subDay())->sum('amount');

	        $last_payout = $user->payoutItems()
	                            ->where(['transaction_status' => 'SUCCESS'])
	                            ->latest()
	                            ->first();

	        $user->readings_count = $user->chatSessions()->count() + $user->readings;
			$user->clients = $user->chatSessions()->distinct('customer_id')->count('customer_id');
			$user->lifetime_earnings = $user->payoutItems()->where(['transaction_status' => 'SUCCESS'])->sum('amount');
	        $user->sales = $sales;
	        $user->daily_sales = $daily_sales;
	        $user->withdrawn = $last_payout ? $last_payout->amount : 0;
        }

	    if ($user->isCustomer()) {
		    $user->readings_count = $user->chatSessions()->count() + $user->readings;
		    $user->added_funds = $user->paypalPayments()->where(['status' => 1])->sum('amount') / 100;
	    }

        return new UserResource($user, Auth::user());
    }

    public function store(StoreUsersRequest $request)
    {
        $data = $request->all();

        if ($request->has('price_per_minute')) {
	        $data['price_per_minute'] = $data['price_per_minute'] ? $data['price_per_minute'] * 100 : null;
        }

	    $data['payment_email'] = $data['email'];
	    $data['password'] = hash('md5', time());
	    $data['api_token'] = Str::random(60);
	    $data['approved'] = 1;
	    $data['avatar'] = $request->hasFile('avatar') ? $request->file('avatar')->store('avatars') : null;

        $user = User::create($data);

	    $slug_is_busy = User::where(['slug' => $this->slugify($data['display_name'])])->count();

	    if ($slug_is_busy) {
		    $user->update(['slug' => $this->slugify($data['display_name']) . '-' . $user->id ]);
        } else {
		    $user->update(['slug' => $this->slugify($data['display_name'])]);
        }

	    $user->sendPasswordSetNotification();

        return (new UserResource($user))->response()->setStatusCode(201);
    }

	private function slugify($string){
		return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string), '-'));
	}

    public function update(UpdateUsersRequest $request, User $user)
    {
	    $data = $request->all();

	    if ($request->has('price_per_minute')) {
		    $data['price_per_minute'] = $data['price_per_minute'] ? $data['price_per_minute'] * 100 : null;
	    }

	    if ($request->hasFile('avatar')) {
	    	$this->deleteAvatar($user);

		    $data['avatar'] = $request->file('avatar')->store('avatars');
	    } elseif (isset($data['avatar']) && $data['avatar'] == 'null') {
		    $this->deleteAvatar($user);

	    	$data['avatar'] = null;
	    }

        $user = User::findOrFail($user->id);

        $user->update($data);

        return (new UserResource($user))
            ->response()
            ->setStatusCode(202);
    }

    public function destroy(User $user)
    {
        $user = User::findOrFail($user->id);

        $this->deleteAvatar($user);
	    $this->deleteResume($user);

        $user->delete();

        return response(null, 204);
    }

	public function changeStatus(Request $request)
	{
		$user = auth()->user();

		Cache::forget('user-is-busy-' . $user->id);

		if ($request->status == 'online') {
			Cache::forget('user-is-offline-' . $user->id);
			$user->update(['is_logged_in' => 1]);
		} else {
			$expiresAt = Carbon::now()->addHours(24);
			Cache::put('user-is-offline-' . $user->id, true, $expiresAt);

			$user->update(['is_logged_in' => 0]);
		}

		return response()->json(true);
    }

    private function deleteAvatar($user)
    {
	    if ($user->avatar && Storage::exists($user->avatar)) {
		    Storage::delete($user->avatar);
	    }
    }

	private function deleteResume($user)
	{
		if ($user->resume && Storage::exists($user->resume)) {
			Storage::delete($user->resume);
		}
	}

	public function getAdvisors()
	{
		$advisors = User::where(['role_id' => Role::getAdvisorId()])->get();

		return response()->json($advisors);
    }

	public function getCustomers()
	{
		$customers = User::where(['role_id' => Role::getCustomerId()])->get();

		return response()->json($customers);
	}

	public function getFreeMinutesForAdmin(User $user1, User $user2)
	{
		if ($user1->isCustomer()) {
			$free_minutes = $user1->freeMinutes()->where(['advisor_id' => $user2->id])->first();
		} elseif ($user2->isCustomer()) {
			$free_minutes = $user2->freeMinutes()->where(['advisor_id' => $user1->id])->first();
		}

		$minutes = $free_minutes ? $free_minutes->free_seconds / 60 : 0;

		return response()->json($minutes);
	}

	public function updateFreeMinutes(Request $request, User $user1, User $user2)
	{
		if ($user1->isCustomer()) {
			$user1->freeMinutes()->updateOrCreate(
				['advisor_id' => $user2->id],
				['free_seconds' => $request->minutes * 60]
			);
		} elseif ($user2->isCustomer()) {
			$user2->freeMinutes()->updateOrCreate(
				['advisor_id' => $user1->id],
				['free_seconds' => $request->minutes * 60]
			);
		}
	}

	public function approveAccount(Request $request, User $user)
	{
		$user->update(['approved' => 1]);

		$user->sendPasswordSetNotification('account_approved');

		return response()->json(true);
	}

	public function declineAccount(Request $request, User $user)
	{
		$user->update(['decline_reason' => $request->decline_reason]);

		$user->notify(new AccountDeclined($request->decline_reason));

		return response()->json(true);
	}

	public function countries()
	{
		return response()->json(Country::all());
	}

	public function changeBalance(Request $request, User $user)
	{
		if ($user->status() !== 'busy') {
			$new_balance = $request->operator === '+' ? $user->balance + $request->amount : $user->balance - $request->amount;

			$user->update( [ 'balance' => $new_balance ] );

			broadcast(new BalanceUpdated($user));

			$user->payments()->create( [
				'relationship_id' => 0,
				'type'            => $request->note,
				'amount'          => $request->operator . $request->amount,
				'balance'         => $user->balance
			] );
		} else {
			return response()->json('User status is busy');
		}

		return response()->json(true);
	}

	public function getFreeMinutes(Request $request, User $advisor)
	{
		$customer = auth()->user();
		$free_seconds = 0;

		if ($customer->balance > 0) {
			$customer_added_money = true;
		} else {
			$customer_added_money = $customer->paypalPayments()->where(['status' => 1])->first() ? true : false;
		}

		if ($advisor->free_minutes_enabled && $customer_added_money) {
			$free_minutes = $customer->freeMinutes()->where(['advisor_id' => $advisor->id])->first();

			if ($free_minutes) {
				$free_seconds = $free_minutes->free_seconds;
			} else {
				$free_seconds = 180;
			}
		}

		return response()->json($free_seconds);
	}

	public function changePayoutsEnabled(Request $request)
	{
		auth()->user()->update([
			'payouts_enabled' => $request->payouts_enabled
		]);
	}

	public function getMyClients(Request $request)
	{
		$advisor = auth()->user();

		$customer_ids = $advisor->chatSessions()->groupBy('customer_id')->pluck('customer_id');

		$columns = [
			'users.id',
			'users.slug',
			'users.display_name',
			'users.avatar',
			'users.created_at',
			'users.is_logged_in',
			'users.top_advisor',
		];

		$my_clients = User::whereIn('users.id', $customer_ids)
		                  ->where(function($query) use ($request) {
		                  	$query->where('display_name', 'LIKE', '%'.$request->search.'%');
		                  })
		                  ->select($columns)
		                  ->offset($request->offset)
		                  ->limit($request->limit)
		                  ->get();

		foreach ($my_clients as $key => $customer) {
			$my_clients[$key]['status'] = $customer->status();
			$my_clients[$key]['blocked'] = $advisor->blockedCustomers()->where(['customer_id' => $customer->id])->first() ? true : false;
		}

		$total = User::whereIn('id', $customer_ids)->count();

		return response()->json([
			'data' => $my_clients,
			'total' => $total
		]);
	}

	public function getMyPsychics(Request $request)
	{
		$customer = auth()->user();

		$advisors_ids = $customer->favoriteAdvisors()->groupBy('advisor_id')->pluck('advisor_id');

		$columns = [
			'id',
			'role_id',
			'display_name',
			'slug',
			'avatar',
			'price_per_minute',
			'created_at',
			'is_logged_in',
			'top_advisor',
			'master_service_id'
		];

		$my_psychics = User::with(array('masterService'=>function($query){
			$query->select('id', 'title', 'slug');
		}))
		                   ->whereIn('id', $advisors_ids)
		                   ->where(function($query) use ($request) {
		                   	$query->where('display_name', 'LIKE', '%'.$request->search.'%');
		                   })
		                   ->select($columns)
		                   ->offset($request->offset)
		                   ->limit($request->limit)
		                   ->get();

		foreach ($my_psychics as $key => $advisor) {
			$my_psychics[$key]->status = $advisor->status();
		}

		$total = User::whereIn('id', $advisors_ids)->count();

		return response()->json([
			'data' => $my_psychics,
			'total' => $total
		]);
	}

	public function addOrRemoveFavoriteAdvisor(Request $request, User $advisor)
	{
		$customer = auth()->user();

		$favorite_advisor = $customer->favoriteAdvisors()->where(['advisor_id' => $advisor->id])->first();

		if ($favorite_advisor) {
			$favorite_advisor->delete();
		} else {
			$customer->favoriteAdvisors()->create(['advisor_id' => $advisor->id]);
		}
	}

	public function updateActivity()
	{
		auth()->user()->update([
			'last_activity' => now(),
			'is_logged_in' => Cache::has( 'user-is-offline-' . auth()->id() ) ? 0 : 1
		]);
	}

	public function loginAsUser(Request $request, User $user)
	{
		Auth::login($user);

		$expiresAt = Carbon::now()->addHours(24);
		Cache::put('logged-as-user-' . $user->id, true, $expiresAt);

		return response()->json(true);
	}

	public function loginAsSuperadmin(Request $request, User $user)
	{
		if (Cache::has('logged-as-user-' . $request->user_id)) {
			Auth::login( $user );

			Cache::forget( 'logged-as-user-' . $request->user_id );

			return response()->json( true );
		}

		return response()->json( false );
	}

	public function getUser(Request $request, User $user)
	{
		return response()->json($user);
	}

}
