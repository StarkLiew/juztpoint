<?php

namespace App\Models;

use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Product extends Model {

	use SoftDeletes, Notifiable, UsesTenantConnection;

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
		'name', 'cat_id', 'discount', 'tax_id', 'commission_id', 'note', 'user_id', 'consumable', 'stock', 'service', 'sellable', 'sku', 'assistant',
	];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = [

	];
	protected $appends = ['locked'];

	public function details() {
		return $this->hasMany('App\Models\TranDetail', 'detail_id');
	}

	public function taxRate() {
		return $this->belongsTo('App\Models\TaxRate', 'tax_id');
	}

	public function category() {
		return $this->belongsTo('App\Models\Category', 'cat_id');
	}

	public function prices() {
		return $this->hasMany('App\Models\Price', 'prod_id');
	}
	public function inventories() {
		return $this->hasMany('App\Models\Inventory', 'ref_prod_id');
	}

	public function inventory() {
		return $this->belongsTo('App\Models\Inventory', 'prod_id');
	}

	public function user() {
		return $this->belongsTo('App\Models\User', 'user_id');
	}
	public function commissionRate() {
		return $this->belongsTo('App\Models\CommissionRate', 'commission_id');
	}

	public function getLockedAttribute() {
		if (\App\Models\SystemLocker::where('item_table', 'products')->where('item_id', $this->id)->count()) {
			return true;
		} else {
			return false;
		}

	}
}
