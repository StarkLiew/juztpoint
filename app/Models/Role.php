<?php 
namespace App\Models;

use Zizaco\Entrust\EntrustRole;

class Role extends EntrustRole
{

	public function permission_role()
    {
        return $this->hasMany('App\Models\PermissionRole', 'permission_id');
    } 

}