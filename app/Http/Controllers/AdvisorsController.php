<?php

namespace App\Http\Controllers;

use App\Http\Resources\ReviewResource;
use App\Review;
use App\Role;
use App\Service;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdvisorsController extends Controller
{
	public function getAdvisors(Request $request)
	{
		$columns = [
			'users.id',
			'users.role_id',
			'users.display_name',
			'users.slug',
			'users.avatar',
			'users.price_per_minute',
			'users.highlight',
			'users.description',
			'users.created_at',
			'users.is_logged_in',
			'users.top_advisor',
			'users.rank',
			'users.readings',
			'master_service_id',
		];

		$advisors = User::leftJoin('user_services', 'users.id', '=', 'user_services.user_id')
		                ->with(array('masterService'=>function($query){
			                $query->select('id', 'title', 'slug');
		                }))
		                ->where([
			                'users.role_id' => Role::getAdvisorId(),
			                'users.approved' => 1,
			                'users.blocked' => 0,
			                'users.deleted' => 0
		                ])
		                ->where(function($query) use ($request) {
			                if ($request->availability) {
				                $query->where('users.is_logged_in', $request->availability === 'online');
			                }

			                if ($request->minPrice) {
				                $query->where('users.price_per_minute', '>=', $request->minPrice * 100);
			                }

			                if ($request->maxPrice) {
				                $query->where('users.price_per_minute', '<=', $request->maxPrice * 100);
			                }

		                })
						->where(function($query) use ($request) {
							if ($request->search) {
								$query->where( 'display_name', 'LIKE', '%' . $request->search . '%' );
							}
						})
		                ->select($columns)
						->limit($request->limit)
		                ->offset($request->offset)
		                ->distinct('users.id')
		                ->groupBy('users.id')
		                ->orderBy('users.is_logged_in', 'desc')
		                ->orderBy('rank', 'desc')
		                ->when($request->sort, function ($query, $sort) {
			                if ($sort === 'id') {
				                return $query->orderBy( 'users.id', 'desc' );
			                }

			                if ($sort === 'rating') {
				                return $query->orderBy( 'users.rating', 'desc' );
			                }

			                if ($sort === 'top-paid') {
				                return $query->orderBy( 'users.sales', 'desc' );
			                }

			                if ($sort === 'low-paid') {
				                return $query->orderBy( 'users.sales', 'asc' );
			                }

			                return $query;
		                })
		                ->get();

		foreach ($advisors as $key => $advisor) {
			$advisors[$key]->status = $advisor->status();
			$advisors[$key]->rating = $advisor->rating();
			$advisors[$key]->chatSessionsCount = $advisor->chatSessions()->count() + $advisor->readings;
			$advisors[$key]->reviewsTotal = $advisor->reviews()->count();
		}

		$total = User::where([
			             'role_id' => Role::getAdvisorId(),
			             'approved' => 1,
			             'blocked' => 0,
			             'deleted' => 0
		             ])
		             ->where(function($query) use ($request) {
			             if ($request->availability) {
				             $query->where('users.is_logged_in', $request->availability === 'online');
			             }

		             })
		             ->where(function($query) use ($request) {
		             	if ($request->search) {
		             		$query->where( 'display_name', 'LIKE', '%' . $request->search . '%' );
		             	}
		             })
		             ->distinct('users.id')
		             ->count('users.id');

		return response()->json([
			'advisors' => $advisors,
			'total' => $total,
			'max_price_per_minute' => User::max('price_per_minute')
		]);
	}

    public function getCategoryAdvisors(Request $request, Service $service)
    {
	    $columns = [
		    'users.id',
		    'users.role_id',
		    'users.display_name',
		    'users.slug',
		    'users.avatar',
		    'users.price_per_minute',
		    'users.highlight',
		    'users.description',
		    'users.created_at',
		    'users.is_logged_in',
		    'users.top_advisor',
		    'users.rank',
		    'users.readings',
		    'master_service_id',
	    ];

	    $advisors = User::leftJoin('user_services', 'users.id', '=', 'user_services.user_id')
		                ->with(array('masterService'=>function($query){
			                $query->select('id', 'title', 'slug');
		                }))
	                    ->where([
	    	                'users.role_id' => Role::getAdvisorId(),
		                    'users.approved' => 1,
		                    'users.blocked' => 0,
		                    'users.deleted' => 0
	                    ])
					    ->where(function($query) use ($service) {
						    $query->where('users.master_service_id', $service->id)
						          ->orWhere('user_services.service_id', $service->id);
			            })
					    ->where(function($query) use ($request) {
						    if ($request->availability) {
							    $query->where('users.is_logged_in', $request->availability === 'online');
						    }

						    if ($request->minPrice) {
							    $query->where('users.price_per_minute', '>=', $request->minPrice * 100);
						    }

						    if ($request->maxPrice) {
							    $query->where('users.price_per_minute', '<=', $request->maxPrice * 100);
						    }

					    })
		                ->select($columns)
		                ->limit($request->limit)
	                    ->offset($request->offset)
		                ->distinct('users.id')
		                ->groupBy('users.id')
		                ->orderBy('users.is_logged_in', 'desc')
		                ->orderBy('rank', 'desc')
	                    ->when($request->sort, function ($query, $sort) {
	                    	if ($sort === 'id') {
			                    return $query->orderBy( 'users.id', 'desc' );
		                    }

		                    if ($sort === 'rating') {
			                    return $query->orderBy( 'users.rating', 'desc' );
		                    }

		                    if ($sort === 'top-paid') {
			                    return $query->orderBy( 'users.sales', 'desc' );
		                    }

		                    if ($sort === 'low-paid') {
			                    return $query->orderBy( 'users.sales', 'asc' );
		                    }

		                    return $query;
		                })
		                ->get();

	    foreach ($advisors as $key => $advisor) {
		    $advisors[$key]->status = $advisor->status();
		    $advisors[$key]->rating = $advisor->rating();
		    $advisors[$key]->chatSessionsCount = $advisor->chatSessions()->count() + $advisor->readings;
		    $advisors[$key]->reviewsTotal = $advisor->reviews()->count();
	    }

	    $total = User::leftJoin('user_services', 'users.id', '=', 'user_services.user_id')
	                 ->where([
	                 	'role_id' => Role::getAdvisorId(),
					    'approved' => 1,
					    'blocked' => 0,
					    'deleted' => 0
	                ])
				    ->where(function($query) use ($service) {
					    $query->where('users.master_service_id', $service->id)
					          ->orWhere('user_services.service_id', $service->id);
				    })
				    ->where(function($query) use ($request) {
					    if ($request->availability) {
						    $query->where('users.is_logged_in', $request->availability === 'online');
					    }

				    })
				    ->distinct('users.id')
				    ->count('users.id');

	    return response()->json([
	    	'advisors' => $advisors,
		    'total' => $total,
		    'max_price_per_minute' => User::max('price_per_minute')
	    ]);
    }

	public function getProfile(Request $request, User $user)
	{
		if ($user->isAdvisor()) {
			$data = $user->only([
				'id',
				'role_id',
				'display_name',
				'slug',
				'avatar',
				'price_per_minute',
				'description',
				'experience',
				'qualification',
				'country',
				'experience_years',
				'highlight',
				'about_services',
				'created_at',
				'top_advisor',
				'readings'
			]);

			$data['status']         = $user->status();
			$data['horoscope']      = $user->horoscope;
			$data['master_service'] = $user->masterService;
			$data['services'] = $user->services;
			$data['languages']      = $user->languages;
			$data['rating'] = $user->rating();
			$data['chatSessionsCount'] = $user->chatSessions()->count() + $user->readings;
			$data['reviews'] = $user->reviews()
									->where(function($query) use ($request) {
										if ($request->rating) {
											$query->where('rating', '=', $request->rating);
										}
									})
			                        ->limit($request->limit)
			                        ->offset($request->offset)
									->orderBy($request->sort, $request->order)
			                        ->get();
			$data['reviewsTotal'] = $user->reviews()->count();

			return response()->json($data);
		}

		return response()->json(false);
    }

	public function blockCustomer(Request $request, User $user)
	{
		$advisor = auth()->user();

		$advisor->blockedCustomers()->firstOrCreate(['customer_id' => $user->id]);
    }

	public function unblockCustomer(Request $request, User $user)
	{
		$advisor = auth()->user();

		$advisor->blockedCustomers()->where(['customer_id' => $user->id])->delete();
	}

	public function getFeaturedAdvisors(Request $request)
	{
		$limit = strlen($request->featuredAdvisorsLimit) ? $request->featuredAdvisorsLimit : 4;
		$offset = strlen($request->featuredAdvisorsOffset) ? $request->featuredAdvisorsOffset : 0;
		$sort = $request->featuredAdvisorsSort ? $request->featuredAdvisorsSort : 'rank';
		$order = $request->featuredAdvisorsOrder ? $request->featuredAdvisorsOrder : 'desc';

		$featured_advisors = $this->getAdvisorsByFilter($limit, $offset, $sort, $order);

//		$limit = strlen($request->topRatedAdvisorsLimit) ? $request->topRatedAdvisorsLimit : 4;
//		$offset = strlen($request->topRatedAdvisorsOffset) ? $request->topRatedAdvisorsOffset : 0;
//		$sort = $request->topRatedAdvisorsSort ? $request->topRatedAdvisorsSort : 'rank';
//		$order = $request->topRatedAdvisorsOrder ? $request->topRatedAdvisorsOrder : 'desc';
//
//		$top_rated_advisors = $this->getAdvisorsByFilter($limit, $offset, $sort, $order);
//
//		$limit = strlen($request->topPaidAdvisorsLimit) ? $request->topPaidAdvisorsLimit : 4;
//		$offset = strlen($request->topPaidAdvisorsOffset) ? $request->topPaidAdvisorsOffset : 0;
//		$sort = $request->topPaidAdvisorsSort ? $request->topPaidAdvisorsSort : 'rank';
//		$order = $request->topPaidAdvisorsOrder ? $request->topPaidAdvisorsOrder : 'desc';
//
//		$top_paid_advisors = $this->getAdvisorsByFilter($limit, $offset, $sort, $order);
//
//		$limit = strlen($request->newAdvisorsLimit) ? $request->newAdvisorsLimit : 4;
//		$offset = strlen($request->newAdvisorsOffset) ? $request->newAdvisorsOffset : 0;
//		$sort = $request->newAdvisorsSort ? $request->newAdvisorsSort : 'rank';
//		$order = $request->newAdvisorsOrder ? $request->newAdvisorsOrder : 'desc';
//
//		$new_advisors = $this->getAdvisorsByFilter($limit, $offset, $sort, $order);

		$total = User::where(['role_id' => Role::getAdvisorId()])
		                               ->where('is_logged_in', 1)
		                               ->whereNotNull( 'master_service_id' )
		                               ->count();

		return response()->json([
			'featured_advisors' => $featured_advisors,
//			'top_rated_advisors' => $top_rated_advisors,
//			'top_paid_advisors' => $top_paid_advisors,
//			'new_advisors' => $new_advisors,
			'total' => $total
		]);
	}

	private function getAdvisorsByFilter($limit, $offset, $sort, $order)
	{
		$columns = [
			'users.id',
			'users.role_id',
			'users.display_name',
			'users.slug',
			'users.avatar',
			'users.price_per_minute',
			'users.highlight',
			'users.created_at',
			'users.master_service_id',
			'users.is_logged_in',
			'users.top_advisor',
			'users.rank',
			'users.readings'
		];

		$advisors = User::with( array(
			'masterService' => function ( $query ) {
				$query->select( 'id', 'title', 'slug' );
			}
		) )
		    ->where( [ 'role_id' => Role::getAdvisorId() ] )
		    ->where( 'is_logged_in', 1 )
		    ->whereNotNull( 'master_service_id' )
		    ->select( $columns )
		    ->limit( $limit )
		    ->offset( $offset )
		    ->orderBy( $sort, $order )
		    ->get();

		foreach ($advisors as $key => $advisor) {
			$advisors[$key]->status = $advisor->status();
			$advisors[$key]->rating = $advisor->rating();
			$advisors[$key]->chatSessionsCount = $advisor->chatSessions()->count() + $advisor->readings;
			$advisors[$key]->reviewsTotal = $advisor->reviews()->count();
		}

		return $advisors;
	}

	public function getReviews(User $user, Request $request)
	{
		$reviews = $user->reviews()
		                        ->with('customer')
		                        ->limit($request->limit)
		                        ->offset($request->offset)
		                        ->orderBy($request->sort, $request->order)
		                        ->get();

		return [
			'data' => new ReviewResource($reviews, auth()->user()),
			'total' => $user->reviews()->count()
		];
	}

	public function getReview(Review $review, Request $request)
	{
		return new ReviewResource($review, auth()->user());
	}

	public function updateReview(Review $review, Request $request)
	{
		$review->update([
			'rating' => $request->rating,
			'text' => $request->text
		]);

		return response()->json(true);
	}

	public function destroyReview(Review $review)
	{
		$review->delete();

		return response(null, 204);
	}

}
