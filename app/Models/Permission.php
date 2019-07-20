<?php 

namespace App\Models;

use Zizaco\Entrust\EntrustPermission;

class Permission extends EntrustPermission
{

    public function permission_role()
    {
        return $this->hasMany('App\Models\PermissionRole', 'permission_id');
    } 
}