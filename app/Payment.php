<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
	/**
	 * Fields that are mass assignable
	 *
	 * @var array
	 */
	protected $fillable = [
		'user_id',
		'relationship_id',
		'type',
		'amount',
		'balance'
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
