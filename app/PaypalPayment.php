<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaypalPayment extends Model
{
	/**
	 * Fields that are mass assignable
	 *
	 * @var array
	 */
	protected $fillable = [
		'user_id',
		'payment_id',
		'transaction_id',
		'payment_response',
		'amount',
		'balance_before',
		'balance_after',
		'status'
	];

	/**
	 * A message belong to a user
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function user()
	{
		return $this->belongsTo(User::class);
	}
}
