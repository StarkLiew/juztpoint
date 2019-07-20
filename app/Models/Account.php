<?php

namespace App\Models;

use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Account extends Model {

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
		'name', 'registerid', 'email', 'gstid', 'phone', 'address', 'fax', 'contact', 'note', 'supplier',
	];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = [

	];

	protected $appends = ['locked'];

	public function transactions() {
		return $this->hasMany('App\TranHeader', 'account_id');
	}
	public function appointments() {
		return $this->hasMany('App\Appointment', 'customer_id');
	}

	public function user() {
		return $this->belongsTo('App\User', 'user_id');
	}

	public function getLockedAttribute() {
		if (\App\Models\SystemLocker::where('item_table', 'accounts')->where('item_id', $this->id)->count()) {
			return true;
		} else {
			return false;
		}

	}

}
