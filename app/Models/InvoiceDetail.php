<?php

namespace App\Models;

use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;


class InvoiceDetail extends Model
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
    protected $appends = ['taxcode'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
           'line','invoice_id', 'product_id', 'appointment_id', 'user_id', 'qty', 'price', 'discount', 'disType', 'total', 'tax_id', 'tax',  'tax_rate', 'amount'
    ];

    //public static $FIRE_EVENTS = true;
    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
  
    ];

    public function invoice()
    {
        return $this->belongsTo('App\Models\Invoice', 'invoice_id');
    }
    public function product()
    {
        return $this->belongsTo('App\Models\Product', 'product_id');
    }
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function account()
    {
        return $this->belongsTo('App\Models\Account', 'customer_id');
    }

    public function appointment(){
         return $this->belongsTo('App\Models\Appointment','appointment_id');
    }

    public function taxrate(){
         return $this->belongsTo('App\Models\TaxRate',  'tax_id');
    }


    public function commissions(){
         return $this->hasMany('App\Models\Commission','inv_detail_id');
    }

  /*  protected static function boot()
    {
        parent::boot();

        static::creating(function($modal) {
 
             if($modal->appointment_id !== ''){
                $found = $modal->where('appointment_id', $modal->appointment_id)->count();    
         
                if($found > 0) 
                   return false; 

             }

             return true;
        });
    }  */ 

    public function getTaxcodeAttribute(){

        $result = \App\Models\TaxRate::select('code')->where('id', $this->tax_id)->first();

        if( $result){
          return $result->code;
        }

        return "";
     
    }


}
