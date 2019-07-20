<?php

namespace App\Models;

use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Receive extends Model {
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
		'reference', 'account_id', 'user_id', 'date', 'type', 'gross', 'discount', 'tax', 'nett', 'note',
	];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = [

	];

	protected static function boot() {
		parent::boot();

		/* static::saved(function(Invoice $model) {

			             self::calcCommission($model);
		*/
		/*Event::listen('eloquent.saved: App\Model\Invoice', function ($model) {
			                    Log::info( $model);
			               self::calcCommission($model);
		*/

	}

	public function details() {
		return $this->hasMany('App\Models\ReceiveDetail', 'receive_id');
	}
	public function supplier() {
		return $this->belongsTo('App\Models\Account', 'account_id');
	}
	public function user() {
		return $this->belongsTo('App\Models\User', 'user_id');
	}

}
