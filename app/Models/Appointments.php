<?php

namespace App\Models;

use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Appointments extends Model {
	use SoftDeletes, Notifiable, UsesTenantConnection;

	/**
	 * The attributes that should be mutated to dates.
	 *
	 * @var array
	 */
	protected $dates = ['appoint_date', 'deleted_at'];
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

	public function details() {
		return $this->hasMany('App\AppointmentDetails', 'appointment_id');
	}
	public function account() {
		return $this->belongsTo('App\Account', 'account_id');
	}
	public function user() {
		return $this->belongsTo('App\User', 'user_id');
	}
}
