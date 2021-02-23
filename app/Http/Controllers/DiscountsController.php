<?php

namespace App\Http\Controllers;

use App\ChatSession;
use App\Discount;
use App\DiscountsHistory;
use App\Http\Resources\DiscountResource;
use Illuminate\Http\Request;

class DiscountsController extends Controller
{
	public function __construct()
	{

	}

	public function getDiscounts(Request $request)
	{
		$discounts = Discount::offset($request->offset)
		                     ->limit($request->limit)
		                     ->orderBy($request->sort, $request->order)
		                     ->get();

		$total = Discount::count();

		return [
			'data' => new DiscountResource($discounts, auth()->user()),
			'total' => $total
		];
	}

	public function getDiscount(Discount $discount)
	{
		return (new DiscountResource($discount))->response()->setStatusCode(201);
	}

	public function store(Request $request)
	{
		Discount::where(['for_new_users' => $request->for_new_users])->update(['active' => 0]);

		$discount = Discount::create($request->all());

		return (new DiscountResource($discount))->response()->setStatusCode(201);
	}

	public function update(Discount $discount, Request $request)
	{
		$discount->update($request->all());

		return (new DiscountResource($discount))->response()->setStatusCode(201);
	}

	public function destroy(Discount $discount)
	{
		$discount->delete();

		return response(null, 204);
	}
}
