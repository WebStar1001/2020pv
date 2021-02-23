<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class PayoutItem extends Model
{
    protected $fillable = [
    	'user_id',
	    'payout_id',
	    'payout_item_id',
	    'transaction_id',
	    'transaction_status',
	    'amount'
    ];

	public function user()
	{
		return $this->belongsTo(User::class);
	}

}
