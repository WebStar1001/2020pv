<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    protected $fillable = [
	    'discount',
	    'active',
	    'for_new_users',
    ];

}
