<?php

namespace App\Models;

use Carbon\Carbon;
use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Invoice extends Model {
	use SoftDeletes, Notifiable, UsesTenantConnection;

	/**
	 * The attributes that should be mutated to dates.
	 *
	 * @var array
	 */
	protected $dates = ['deleted_at'];
	protected $primaryKey = 'id'; // or null
	public $incrementing = true;

	protected $appends = ['paid', 'due', 'locked'];

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
		return $this->hasMany('App\Models\InvoiceDetail', 'invoice_id');
	}
	public function customer() {
		return $this->belongsTo('App\Models\Account', 'account_id');
	}
	public function user() {
		return $this->belongsTo('App\Models\User', 'user_id');
	}

	public function payments() {
		return $this->hasMany('App\Models\Payment', 'invoice_id');
	}

	public function getDateAttribute($date) {
		return Carbon::parse($date)->toAtomString();
	}

	public function getDueAttribute() {
		$received = \App\Models\Payment::where('invoice_id', $this->id)->sum('received');
		$change = \App\Models\Payment::where('invoice_id', $this->id)->sum('change');
		$due = round(($this->nett - ($received - $change)) * 20) / 20;
		return $due;

	}

	public function getPaidAttribute() {
		if ($this->getDueAttribute() <= 0) {
			return true;
		} else {
			return false;
		}

	}
	public function getLockedAttribute() {
		if (\App\Models\Payment::where('invoice_id', $this->id)->count('received') > 0) {
			return true;
		} else {
			return false;
		}

	}

}
