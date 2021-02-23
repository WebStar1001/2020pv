<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
	/**
	 * Fields that are mass assignable
	 *
	 * @var array
	 */
	protected $fillable = [
		'chat_session_id',
		'email_subject_id',
		'comment'
	];

	public function chatSession()
	{
		return $this->belongsTo(ChatSession::class)->with('customer', 'advisor');
	}

	public function emailSubject()
	{
		return $this->belongsTo(EmailSubject::class)->with('customer', 'advisor');
	}
}
