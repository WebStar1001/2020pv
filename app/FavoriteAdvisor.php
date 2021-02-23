<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class FavoriteAdvisor extends Model
{
    protected $fillable = [
    	'customer_id',
	    'advisor_id'
    ];

	public function customer()
	{
		return $this->hasOne(User::class, 'id', 'customer_id');
	}

	public function advisor()
	{
		return $this->hasOne(User::class, 'id', 'advisor_id');
	}
}
