<?php

namespace App\Models;

use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Inventory extends Model {

	use SoftDeletes, Notifiable, UsesTenantConnection;
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
		'ref_prod_id', 'prod_id', 'unit',
	];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = [

	];

	public function product() {
		return $this->belongsTo('App\Models\Product', 'prod_id');
	}

}
