<?php

namespace App\Models;

use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;


class ReceiveDetail extends Model
{
 use SoftDeletes , Notifiable, UsesTenantConnection;
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
    protected $primaryKey = 'id'; // or null
    public $incrementing = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
           'line','receive_id', 'product_id',  'user_id', 'qty', 'price', 'discount', 'disType', 'total', 'tax_id', 'tax',  'tax_rate', 'amount'
    ];


    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
  
    ];

    public function receive()
    {
        return $this->belongsTo('App\Models\Receive', 'receive_id');
    }
    public function product()
    {
        return $this->belongsTo('App\Models\Product', 'product_id');
    }
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function taxRate(){
         return $this->belongsTo('App\Models\taxRate','tax_id');
    }



}
