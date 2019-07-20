<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class PermissionRole extends Model
{

  

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
 
      protected $table = "permission_role";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [

    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
  
    ];

    public function permission()
    {
        return $this->belongsTo('App\Models\Permission', 'permission_id');
    }

    public function role()
    {
        return $this->belongsTo('App\Models\Role', 'role_id');
    }


}
