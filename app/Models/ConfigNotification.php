<?php

namespace App\Models;

use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class ConfigNotification extends Model {

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
		'enable_notification', 'notification_delay', 'enable_reminders', 'advance_reminder', 'reminders_subject', 'reminders_message', 'reminders_sms', 'enable_conformation', 'conformation_subject', 'conformation_message', 'conformation_sms', 'enable_reschedules', 'reschedules_subject', 'reschedules_message', 'reschedule_sms', 'enable_cancelations', 'cancelations_subject', 'cancelations_message', 'cancelations_sms', 'enable_thanks', 'thanks_subject', 'thanks_message', 'thanks_sms', 'user_id',
	];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = [

	];
	public function user() {
		return $this->belongsTo('App\User', 'user_id');
	}

}
