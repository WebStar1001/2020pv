<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class ChatPause extends Model
{
    protected $fillable = [
	    'chat_session_id',
	    'ended_at',
	    'reason',
	    'created_at'
    ];

    protected $dates = ['ended_at'];

}
