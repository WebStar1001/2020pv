<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class UserService extends Model
{
    protected $fillable = ['user_id', 'service_id'];

	public function service()
	{
		return $this->belongsTo(Service::class);
	}

}
