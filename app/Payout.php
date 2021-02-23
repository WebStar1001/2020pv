<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Payout extends Model
{
    protected $fillable = [
    	'sender_batch_id',
	    'payout_batch_id',
	    'batch_status'
    ];

	public function cronHistory()
	{
		return $this->hasOne(PayoutCronHistory::class);
	}

}
