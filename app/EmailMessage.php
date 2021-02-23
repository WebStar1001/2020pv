<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class EmailMessage extends Model
{
    protected $fillable = [
    	'email_subject_id',
	    'sender_id',
	    'receiver_id',
	    'message',
	    'invoice_amount',
	    'invoice_accepted',
	    'invoice_rejected',
	    'invoice_cancelled',
	    'read_status',
	    'review_left'
    ];

	public function sender()
	{
		return $this->belongsTo(User::class);
	}

}
