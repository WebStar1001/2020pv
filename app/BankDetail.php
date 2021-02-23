<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class BankDetail extends Model
{
    protected $fillable = [
	    'user_id',
	    'full_name',
	    'address',
	    'zipcode',
	    'iban',
	    'swift',
	    'bank_name'
    ];

}
