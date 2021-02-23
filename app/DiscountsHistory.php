<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class DiscountsHistory extends Model
{
    protected $fillable = [
    	'discount_id',
	    'customer_id',
	    'advisor_id',
	    'chat_session_id',
    ];

}
