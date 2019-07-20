<?php

namespace App\Models;

use Carbon\Carbon;
use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Appointment extends Model {

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
		'customer_id', 'appoint_datetime', 'status', 'user_id', 'note',
	];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = [

	];

	protected $appends = ['locked'];

	public function user() {
		return $this->belongsTo('App\Models\User', 'user_id');
	}
	public function customer() {
		return $this->belongsTo('App\Models\Account', 'customer_id');
	}

	public function details() {
		return $this->hasMany('App\Models\AppointmentDetail', 'appointment_id');
	}

	public function getAppointDatetimeAttribute($date) {

		return Carbon::parse($date)->toAtomString();

	}

	public function getLockedAttribute() {
		if (\App\Models\InvoiceDetail::where('appointment_id', $this->id)->count()) {
			return true;
		} else {
			return false;
		}

	}

}
