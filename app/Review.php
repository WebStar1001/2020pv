<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
    	'session_id',
	    'email_subject_id',
	    'customer_id',
	    'advisor_id',
	    'rating',
	    'text'
    ];

	public function customer()
	{
		return $this->hasOne(User::class, 'id', 'customer_id');
    }

	public function chatSession()
	{
		return $this->belongsTo(ChatSession::class, 'session_id', 'id');
    }

	public function emailsubject()
	{
		return $this->belongsTo(EmailSubject::class, 'email_subject_id', 'id');
	}
}
