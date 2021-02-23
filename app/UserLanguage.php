<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class UserLanguage extends Model
{
    protected $fillable = ['user_id', 'language_id'];

	public function language()
	{
		return $this->belongsTo(Language::class);
	}
}
