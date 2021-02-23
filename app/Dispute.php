<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Dispute extends Model
{
    protected $fillable = [
    	'user_id',
	    'dispute_id',
	    'reason',
	    'status',
	    'dispute_amount'
    ];

	public function user()
	{
		return $this->belongsTo(User::class);
	}
}
