<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class EmailSubject extends Model
{
    protected $fillable = [
    	'customer_id',
	    'advisor_id',
	    'subject',
	    'deleted'
    ];

	public function customer()
	{
		return $this->hasOne(User::class, 'id', 'customer_id');
	}

	public function advisor()
	{
		return $this->hasOne(User::class, 'id', 'advisor_id');
	}

	public function messages()
	{
		return $this->hasMany(EmailMessage::class);
	}

	public function reports()
	{
		return $this->hasMany(Report::class);
	}

	public function unreadMessages()
	{
		return $this->messages()->where(['receiver_id' => auth()->id(), 'read_status' => 0]);
	}

	public function amountEarned()
	{
		return $this->messages()->where(['invoice_accepted' => 1])->sum('invoice_amount');
	}

}
