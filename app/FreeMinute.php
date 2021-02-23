<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FreeMinute extends Model
{
	/**
	 * Fields that are mass assignable
	 *
	 * @var array
	 */
	protected $fillable = [
		'advisor_id',
		'customer_id',
		'free_seconds',
	];

	/**
	 * A message belong to a user
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function user()
	{
		return $this->belongsTo(User::class, 'customer_id');
	}
}
