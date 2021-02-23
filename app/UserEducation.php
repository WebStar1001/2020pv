<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class UserEducation extends Model
{
    protected $fillable = ['user_id', 'education_id'];

	public function education()
	{
		return $this->belongsTo(Education::class);
	}

}
