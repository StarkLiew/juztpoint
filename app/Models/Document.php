<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Document extends Model {

	use Notifiable, SoftDeletes;
	/**
	 * The attributes that should be mutated to dates.
	 *
	 * @var array
	 */
	protected $dates = ['deleted_at'];
	protected $primaryKey = 'id'; // or null
	public $incrementing = true;
	protected $casts = [
		'properties' => 'array',
		'discount' => 'array',
	];
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'reference', 'account_id', 'store_id', 'terminal_id', 'shift_id', 'transact_by', 'date', 'type', 'status', 'discount', 'discount_amount', 'service_charge', 'tax_amount', 'rounding', 'charge', 'refund', 'received', 'change', 'note', 'properties',
	];

	protected $allowedFilters = ['reference', 'status'];
	protected $allowedSorts = ['reference', 'status'];

	public function __construct(array $attributes = []) {
		$id = Auth::id();
		$this->table = "user_{$id}_" . $this->getTable();
		parent::__construct($attributes);
	}

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = [

	];

	public function items() {
		return $this->hasMany('App\Models\Item', 'trxn_id')->with(['tax'])->where(function ($query) {
			$query->where('type', 'item')->orWhere('type', 'pitem');
		});
	}
	public function payments() {
		return $this->hasMany('App\Models\Item', 'trxn_id')->where('type', 'payment');
	}
	public function commissions() {
		return $this->hasMany('App\Models\Item', 'trxn_id')->where('type', 'commission');
	}
	public function store() {
		return $this->belongsTo('App\Models\Setting', 'store_id')->where('type', 'store');
	}
	public function terminal() {
		return $this->belongsTo('App\Models\Setting', 'terminal_id')->where('type', 'terminal');
	}
	public function account() {
		return $this->belongsTo('App\Models\Account', 'account_id');
	}
	public function customer() {
		return $this->belongsTo('App\Models\Account', 'account_id');
	}

	public function teller() {
		return $this->belongsTo('App\Models\User', 'transact_by');
	}

}
