<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;


class Country extends Model
{



    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $primaryKey = 'id'; // or null
    public $incrementing = true;
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

    public function stores()
    {
        return $this->hasMany('App\Models\Store', 'country', 'code');
    }

}
