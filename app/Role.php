<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Role
 *
 * @package App
 * @property string $title
*/
class Role extends Model
{
    protected $fillable = ['title'];
    protected $hidden = [];


    public static function getSuperAdminId() {
        return constants('roles.super_admin_id');
    }
    public static function getAdminId() {
        return constants('roles.admin_id');
    }
    public static function getCustomerId() {
        return constants('roles.customer_id');
    }
    public static function getAdvisorId() {
        return constants('roles.advisor_id');
    }

}
