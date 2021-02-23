<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class PendingPaymentEmail extends Model
{
    protected $fillable = [
    	'user_id',
    	'email',
	    'pending'
    ];

	public function user()
	{
		return $this->belongsTo(User::class);
	}

}
