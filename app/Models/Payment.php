<?php

namespace App\Models;

use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Payment extends Model {

	use SoftDeletes, Notifiable, UsesTenantConnection;

	/**
	 * The attributes that should be mutated to dates.
	 *
	 * @var array
	 */
	protected $dates = ['deleted_at'];
	protected $primaryKey = 'id'; // or null

	protected $appends = ['payment_type_name'];
	public $incrementing = true;
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'pay_type_id', 'invoice_id', 'reference', 'voucher_id', 'amount', 'change', 'received', 'note', 'paid', 'pay_date', 'user_id',
	];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = [

	];
	public function user() {
		return $this->belongsTo('App\Models\User', 'user_id');
	}
	public function invoice() {
		return $this->belongsTo('App\Models\Invoice', 'invoice_id');
	}
	public function voucher() {
		return $this->belongsTo('App\Models\Voucher', 'voucher_id');
	}
	public function paymentType() {
		return $this->belongsTo('App\Models\PaymentType', 'pay_type_id');
	}

	public function getPaymentTypeNameAttribute() {
		$result = \App\Models\PaymentType::select('name')->where('id', $this->pay_type_id)->first();

		if ($result) {
			return $result->name;
		}

		return "";
	}

}
