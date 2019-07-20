<?php

namespace App\Models;

use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Store extends Model {

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
		'name', 'street1', 'street2', 'city', 'state', 'country', 'contact_number', 'email', 'timezone', 'currency', 'user_id',
	];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = [

	];

	public function layout() {
		return $this->belongsTo('App\Models\Layout', 'layout_id');
	}

	public function country_detail() {
		return $this->hasOne('App\Models\Country', 'code', 'country');
	}

	public function user() {
		return $this->belongsTo('App\Models\User', 'user_id');
	}

}
